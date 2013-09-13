<?php

/**
 * 启用 session
 */
session_start();

/**
 * 如果session没有防跨站请求标记则生成一个
 */
if( !isset($_SESSION['token']) )
{
	$_SESSION['token'] = sha1(uniqid(mt_rand(), TRUE));
}

/**
 * 包含必需的配置信息
 */
include_once dirname(__FILE__).'/../config/db-cred.inc.php';
include_once dirname(__FILE__).'/../config/config.inc.php';

/**
 * 为配置信息定义常量
 */
foreach( $C as $name => $val )
{
	define($name, $val);
}
foreach( $D as $name => $val )
{
	define($name, $val);
}

/**
 * 创建数据库连接
 */
$con = mysql_connect(DB_HOST, DB_USER, DB_PASS);
if (!$con)
{
	die('Could not connect: '.mysql_error());
}
$db_selected = mysql_select_db(DB_NAME);
if (!$db_selected)
{
	die('Could not select db '.DB_NAME.' : '.mysql_error());
}
mysql_query("SET NAMES 'utf8'");

/**
 * 定义自动载入类的__autoload函数
 */
function __autoload($class)
{
	$filename = dirname(__FILE__)."/../class/class." . $class . ".inc.php";
	if (file_exists($filename))
	{
		include_once $filename;
	}
}
?>