<?php
include('config.php');
include('function.php');
session_start();
if(!chklogin($_SESSION['user'])){
	$_SESSION['user'] = "Guest";
}
if($config['debug'] != true){
	error_reporting(0);
}
global $imgt_sql_conn;
$imgt_sql_conn = mysql_connect($config['dbHost'],$config['dbUser'],$config['dbPwd']);
if (!$imgt_sql_conn){
  die('Could not connect: ' . mysql_error());
}
mysql_select_db($config['database'], $imgt_sql_conn);
mysql_query("set character set 'utf8'",$imgt_sql_conn);
mysql_query("set names 'utf8'",$imgt_sql_conn);

?>
