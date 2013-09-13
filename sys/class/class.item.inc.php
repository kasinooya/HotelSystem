<?php

/**
 * 操作仓库内物资变化（出库，入库等）
 *
 * PHP version 5
 *
 * @author
 * @copyright
 * @createTime	2012-5-30 15:33:17
 */
class Item extends DB_Connect {
	
	/**
	 * 物品编号，表结构中的主键
	 *
	 * @var	Unsigned int 物品编号
	 */
	private $_id;
	
	/**
	 * 物品名称
	 *
	 * @var	string 物品名称
	 */
	private $_name;
	
	/**
	 * 物品数量
	 *
	 * @var	Unsigned int 物品数量
	 */
	private $_quantity;
	
	/**
	 * 物品价格
	 *
	 * @var	Unsigned int 物品价格
	 */
	private $_price;
	
	/**
	 * 状态信息，是否需要补货
	 *
	 * @var	boolean 是否需要补货
	 */
	private $_needAdd;
	 
	/**
	 * 构造函数，接受一个编号参数。如果这个参数有效，则将它存入私有变量$_id中
	 * 接受一个物品名称参数。如果这个参数有效，则将它存入私有变量$_name中
	 *
	 * @param int $id物品编号
	 * @param string $name物品名称
	 * @return void
	 */
	public function __construct($id = NULL, $name = NULL)
	{
		/* 调用父类构造函数，检查数据库连接 */
		parent::__construct();
		
		if(isset($id))
		{
			$this->_id = $id;
		}
		if(isset($name))
		{
			$this->_name = $name;
		}
	}
	
	/**
	 * 通过读入参数物品名称，查询物资记录表，以返回数组填充私有属性
	 *
	 * @param string $name物品名称
	 * @return 返回TRUE，数据已经填充；返回FALSE，没有查到数据
	 */	
	private function _loadDataByName($name)
	{
		$query = "SELECT item_id, item_name, item_quantity, item_price, item_needAdd
				FROM `item` WHERE item_name = '$name' LIMIT 1";
		$result = @mysql_query($query);
		
		if(mysql_num_rows($result) == 0) {
			return FALSE;
		} else {
			$row = mysql_fetch_assoc($result);
			$this->_id = $row['item_id'];
			$this->_name = $row['item_name'];
			$this->_quantity = $row['item_quantity'];
			$this->_price = $row['item_price'];
			$this->_needAdd = $row['item_needAdd'];
			return TRUE;
		}
	}
	
	/**
	 * 通过读入的物品名称和数量参数，做仓库出库操作
	 *
	 * @param string $name物品名称
	 * @param int $quantity物品数量
	 * @return 成功返回TRUE，错误返回错误信息
	 */
	public function getOut($name, $quantity)
	{
		// 读入该物品数据记录
		if( FALSE === $msg = $this->_loadDataByName($name) )
		{
			return '没有查到$name的数据';
		}
		
		// 检查仓库物品是否充足
		$n = $this->_quantity - $quantity;
		if($n < 0)
		{
			return '仓库存货不足，缺少' . -$n . '件';
		}
		
		// 检查是否需要补货（小于100补货）
		else if($n <= 100)
		{
			$needAdd = 1;
		}
		else 
		{
			$needAdd = 0;
		}
		
		// 变更记录
		$query = "UPDATE `item` 
		SET item_quantity='$n', item_needAdd='$needAdd'
		WHERE item_id='$this->_id'";
		$result = @mysql_query($query);
		if (mysql_affected_rows() == 0)
		{	
			return "更新`item`表失败";
		}
		else return TRUE;
	}
	
	/**
	 * 通过读入的物品名称和数量参数，做仓库入库操作
	 *
	 * @param string $name物品名称
	 * @param int $quantity物品数量
	 * @return 成功返回TRUE，错误返回错误信息
	 */
	public function putIn($name, $quantity)
	{
		// 读入该物品数据记录
		if( FALSE === $msg = $this->_loadDataByName($name) )
		{
			return '没有查到$name的数据';
		}
		
		// 检查是否补充完成（大于100）
		$n = $this->_quantity + $quantity;
		if($n > 100)
		{
			$needAdd = 0;
		}
		else 
		{
			$needAdd = 1;
		}	
		
		// 变更记录
		$query = "UPDATE `item` 
		SET item_quantity='$n', item_needAdd='$needAdd'
		WHERE item_id='$this->_id'";
		$result = @mysql_query($query);
		if (mysql_affected_rows() == 0)
		{	
			return "更新`item`表失败";
		}
		else return TRUE;
	}
}
?>