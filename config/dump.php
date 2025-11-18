<?php 
require_once 'MySQLDump.php';

$db = new mysqli("77.37.127.2", "u245002075_admin_ifapoia", "s|8WRsV@|v", "u245002075_ifapoia2");
$dump = new MySQLDump($db);
$dump->save('dump.sql');

?>