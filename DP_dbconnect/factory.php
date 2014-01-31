<?php
/**
* Created by : Jayant Kumar
* Description : php database library to handle multiple masters & multiple slaves
**/
class DatabaseList // jk : Base class
{
	public $db = array();
	public function setDb($db)
	{
		$this->db = $db;		
	}

	public function getDb()
	{
		return $this->db;
	}
}

class SDatabaseList extends DatabaseList // jk : Slave mysql servers
{
	function __construct()
	{
		$this->db[0] = array('ip'=>'10.20.1.11', 'u'=>'user11', 'p'=>'pass11', 'db'=>'database1');
		$this->db[1] = array('ip'=>'10.20.1.12', 'u'=>'user12', 'p'=>'pass12', 'db'=>'database1');
		$this->db[2] = array('ip'=>'10.20.1.13', 'u'=>'user13', 'p'=>'pass13', 'db'=>'database1');
		//print_r($db);
	}
}

class MDatabaseList extends DatabaseList // jk : Master mysql servers
{
	function __construct()
	{
		$this->db[0] = array('ip'=>'10.20.1.1', 'u'=>'user1', 'p'=>'pass1', 'db'=>'database1');
		$this->db[1] = array('ip'=>'10.20.1.2', 'u'=>'user2', 'p'=>'pass2', 'db'=>'database2');
		//print_r($db);
	}
}

class MemcacheList extends DatabaseList // jk : memcache servers
{
	function __construct()
	{
		$this->db[0] = array('ip'=>'localhost', 'port'=>11211);
	}
}


Interface DatabaseSelectionStrategy  // jk : Database interface
{
	public function getCurrentDb();
}

class StickyDbSelectionStrategy implements DatabaseSelectionStrategy // jk : sticky db . For update / delete / insert
{
	private $dblist;
	private $uid;
	private $sessionDb;
	private $sessionTimeout = 3600;

	function __construct(DatabaseList $dblist)
	{
		$this->dblist = $dblist;
	}

	public function setUserId($uid)
	{
		$this->uid = $uid;
	}

	public function setSessionDb($sessionDb)
	{
		$this->sessionDb = $sessionDb->db;
	}

	private function getDbForUser() // jk : get db for this user. If not found - assign him random master db.
	{
		$memc = new Memcache;
		foreach ($this->sessionDb as $key => $value) {
			$memc->addServer($value['ip'], $value['port']);
		}
		
		$dbIp = $memc->get($this->uid);
		if($dbIp == null)
		{
			$masterlist = new MDatabaseList();
			$randomdb = new RandomDbSelectionStrategy($masterlist);
			$mdb = $randomdb->getCurrentDb();
			$dbIp = $mdb['ip'];
			$memc->set($this->uid, $dbIp, false, $this->sessionTimeout);
		}

		return $dbIp;
	}

	public function getCurrentDb()
	{
		$dbIp = $this->getDbForUser();
		foreach ($this->dblist->db as $key => $value) 
		{
			if($value['ip'] == $dbIp)
				return $value;
		}
	}
}

class RandomDbSelectionStrategy implements DatabaseSelectionStrategy // jk : select random db from list
{	
	private $dblist;

	function __construct(DatabaseList $dblist)
	{
		//print_r($dblist);
		$this->dblist = $dblist;
	}

	public function getCurrentDb()
	{
		//print_r($this->dblist);
		$cnt = sizeof($this->dblist->db);
		$rnd = rand(0,$cnt-1);
		$current = $this->dblist->db[$rnd];
		return $current;
	}
}

class SingleDbSelectionStrategy implements DatabaseSelectionStrategy // jk : select one master db - to generate unique keys
{
	private $dblist;

	function __construct(DatabaseList $dblist)
	{
		$this->dblist = $dblist;
	}

	public function getCurrentDb()
	{
		//print_r($this->dblist);
		return $this->dblist->db[0];
	}
}

Interface Database
{
	public function getIp();
	public function getDbConnection();
}


class DatabaseFactory implements Database // cmt : database factory
{	
	private $db;

	public function getIp()
	{
		return $this->db['ip'];
	}

	public function getDbConnection($type = 'slave', $uid = 0)
	{
		$dbStrategy;

		switch($type)
		{
			case 'slave':
				$dblist = new SDatabaseList();
				//print_r($dblist);
				$dbStrategy = new RandomDbSelectionStrategy($dblist);
				break;

			case 'master':
				$dblist = new MDatabaseList();
				//print_r($dblist);
				$dbStrategy = new StickyDbSelectionStrategy($dblist);
				$dbStrategy->setSessionDb(new MemcacheList());
				$dbStrategy->setUserId($uid);
				break;

			case 'unique':
				$dblist = new MDatabaseList();
				//print_r($dblist);
				$dbStrategy = new SingleDbSelectionStrategy($dblist);
				break;
		}

		$this->db = $dbStrategy->getCurrentDb();

		print_r($this->db);
//		return mysql_connect($this->db['ip'], $this->db['u'], $this->db['p'], $this->db['db']);
	}
}

// tst :  test this out...

$factory = new DatabaseFactory();
echo 'Slave : '; $factory->getDbConnection('slave');
echo 'Slave2 : '; $factory->getDbConnection('slave');
echo 'Unique : '; $factory->getDbConnection('unique');
echo 'New Master 100: '; $factory->getDbConnection('master',100);
echo 'New Master 101: '; $factory->getDbConnection('master',101);
echo 'New Master 102: '; $factory->getDbConnection('master',102);
echo 'old Master 100: '; $factory->getDbConnection('master',100);
echo 'old Master 102: '; $factory->getDbConnection('master',102);

?>