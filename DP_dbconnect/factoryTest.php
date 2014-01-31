<?php
include "factory.php";

$factory = new DatabaseFactory();
echo 'Slave : '; $factory->getDbConnection('slave');
echo 'Unique : '; $factory->getDbConnection('unique');
echo 'New Master 100: '; $factory->getDbConnection('master',100);
echo 'New Master 101: '; $factory->getDbConnection('master',101);
echo 'New Master 102: '; $factory->getDbConnection('master',102);
echo 'old Master 100: '; $factory->getDbConnection('master',100);
echo 'old Master 102: '; $factory->getDbConnection('master',102);

?>