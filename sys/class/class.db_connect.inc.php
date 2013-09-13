<?php

/**
 * 数据库操作（数据库访问、认证等）
 *
 * PHP version 5
 *
 * @author
 * @copyright
 * @createTime	2012-5-15 21:22:44
 */
class DB_Connect {
	 
	/**
	 * 自动创建一个数据库连接
	 *
	 */
	protected function __construct()
	{
		// 如果数据库连接不存在，则创建
		if ( !isset($con) || !isset($db_selected) )
		{
			// 在/sys/config/db-cred.inc.php中定义常量
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
		}
	}
} 
?>