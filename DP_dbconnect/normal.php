<?php
	
	function get_mysql_params($type = 'slave')
	{
		$slaves[0] = array( 'ip' => '10.20.1.11', 'u' => 'name11', 'p' => 'passwd', 'db' => 'database');
		$slaves[1] = array( 'ip' => '10.20.1.21', 'u' => 'name21', 'p' => 'passwd', 'db' => 'database');
		$masters[0] = array( 'ip' => '10.20.1.1', 'u' => 'name1', 'p' => 'passwd', 'db' => 'database');

		if($type == 'slave')
		{
			$cnt = rand(0,1);
			return $slaves[$cnt];
		}
		else
			return $masters[0];
	}

	print_r(get_mysql_params('slave'));
?>