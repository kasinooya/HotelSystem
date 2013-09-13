<?php

/**
 * 创建并维护出入库记录（新建出入库记录，编辑出入库记录等）
 *
 * PHP version 5
 *
 * @author
 * @copyright
 * @createTime	2012-5-28 19:45:33
 */
class StorehouseRecord extends DB_Connect {
	
	/**
	 * 记录编号，表结构中的主键
	 *
	 * @var	Unsigned int 记录编号
	 */
	private $_id;
	 
	/**
	 * 构造函数，接受一个编号参数。如果这个参数有效，则将它存入私有变量$_id中
	 *
	 * @param int $id记录编号
	 * @return void
	 */
	public function __construct($id = NULL)
	{
		/* 调用父类构造函数，检查数据库连接 */
		parent::__construct();
		
		if(isset($id))
		{
			$this->_id = $id;
		}
	}
		
	/**
	 * 通过读入的编号参数，查询出入库记录表，获得一个数组
	 *
	 * @param int $id设备编号
	 * @return array
	 */
	public function loadDataById($id = NULL)
	{
		if(!isset($id))
		{
			$id = $this->_id;
		}
						
		$query = "SELECT storehouseRecord_id, storehouseRecord_time, storehouseRecord_type, storehouseRecord_list, storehouseRecord_user
				FROM `storehouse_record` WHERE storehouseRecord_id = '$id' LIMIT 1";
		$result = @mysql_query($query);
		
		if(mysql_num_rows($result) == 0) {
			return NULL;
		} else {
			$row = mysql_fetch_assoc($result);
			return array(
			"id" => $row['storehouseRecord_id'],
			"time" => $row['storehouseRecord_time'],
			"type" => $row['storehouseRecord_type'],
			"list" => $row['storehouseRecord_list'],
			"user" => $row['storehouseRecord_user']		
			);
		}	
	}
		
	/**
	 * 验证表单，创建新的出入库记录
	 *
	 * @return 成功返回TRUE，失败返回出错信息
	 */
	public function createData()
	{
		// 若action设置不正确，退出
		if($_POST['action'] != 'storehouse_record_new')
		{
			return "不合法请求";
		}
		
		// 转义表单提交过来的数据
		$type = htmlspecialchars($_POST['type'], ENT_QUOTES);
		$list = htmlspecialchars($_POST['list'], ENT_QUOTES);
		$user = htmlspecialchars($_POST['user'], ENT_QUOTES);
				
		// 获取当前时间戳
		$time = time() + TIMEZONE;
		
		// 开始一个事务，设置事务不自动commit 
		mysql_query("BEGIN");				
		mysql_query("SET AUTOCOMMIT=0");
		
		/* 仓库物品出入库操作 */
		
		// 解析物品清单
		$item_array = explode("|", $list);
		$items = array();
		foreach($item_array as $item)
		{
			$temp = explode("*", $item);
			$items[] = array(
			"name" => $temp[0],
			"quantity" => $temp[1]
			);
		}
				
		// 仓库物品出库操作
		if($type == 0)
		{
			foreach($items as $item)
			{
				$obj = new Item();
				if( TRUE !== $msg = $obj->getOut($item['name'], $item['quantity']) )
				{
					mysql_query("ROLLBACK");
					return $msg;
				}
			}
		}
		
		// 仓库物品入库处理
		else if($type == 1)
		{
			foreach($items as $item)
			{
				$obj = new Item();	
				if( TRUE !== $msg = $obj->putIn($item['name'], $item['quantity']) )
				{
					mysql_query("ROLLBACK");
					return $msg;
				}
			}
		}
		else
		{
			return "出入库状态不正确";	
		}
		
		// 不添加出入库编号，会自动按顺序生成
		$query = "INSERT INTO `storehouse_record`
		(storehouseRecord_time, storehouseRecord_type, storehouseRecord_list, storehouseRecord_user)
		VALUES('$time','$type','$list','$user')";
		$result = @mysql_query($query);
		if (mysql_affected_rows() == 0)
		{
			mysql_query("ROLLBACK");
			return "创建失败";
		}
		else
		{
			mysql_query("COMMIT");
			return TRUE;
		}
	}
}
?>