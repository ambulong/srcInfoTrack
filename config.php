<?php
/**
1.get trackinfo
2.redirection
3.HTTP Authorization Cheat
*/ 
$config['dbHost']		='';
$config['dbUser']		='';
$config['dbPwd']		='';
$config['database']	='';
$config['charset']	='utf8';
$config['tbPrefix']	='imgt_';
$config['dbType']		='mysql';
$config['urlroot']	='http://localhost/ti';
$config['img_urlroot']	='http://localhost/ti';
$config['timezone']	='Asia/Shanghai';
$config['debug']		=0;

$config['admin_name']	="admin";
$config['admin_password']	="";//MD5(admin_name+MD5(admin_password))

global $imgt_tbPrefix;
global $imgt_admin_name;
global $imgt_admin_password;
global $imgt_db_name;
global $imgt_urlroot;
global $imgt_img_urlroot;
$imgt_urlroot = $config['urlroot'];
$imgt_db_name = $config['database'];
$imgt_tbPrefix = $config['tbPrefix'];
$imgt_admin_name = $config['admin_name'];
$imgt_admin_password = $config['admin_password'];
$imgt_img_urlroot = $config['img_urlroot'];
date_default_timezone_set($config['timezone']);
?>
