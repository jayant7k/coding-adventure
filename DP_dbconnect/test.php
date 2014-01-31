<?php
class DatabaseList
{
	var $db = array();
	function setDb($db)
	{
		$this->db = $db;		
	}

	function getDb()
	{
		return $this->db;
	}
}

class SDatabaseList extends DatabaseList
{
	function __construct()
	{
		$this->db[0] = array('ip'=>'10.20.1.11', 'u'=>'user11', 'p'=>'pass11', 'db'=>'database1');
		$this->db[1] = array('ip'=>'10.20.1.12', 'u'=>'user12', 'p'=>'pass12', 'db'=>'database1');
		$this->db[2] = array('ip'=>'10.20.1.13', 'u'=>'user13', 'p'=>'pass13', 'db'=>'database1');
	}
}

class MDatabaseList extends DatabaseList
{
	function __construct()
	{
		$this->db[0] = array('ip'=>'10.20.1.1', 'u'=>'user1', 'p'=>'pass1', 'db'=>'database1');
		$this->db[1] = array('ip'=>'10.20.1.2', 'u'=>'user2', 'p'=>'pass2', 'db'=>'database2');
	}
}

$dblist = new SDatabaseList();
print_r($dblist->db);
$dblist = new MDatabaseList();
print_r($dblist->db);

?>