<?php

/**
 * 创建并维护设备信息（新建设备信息，编辑设备信息等）
 *
 * PHP version 5
 *
 * @author
 * @copyright
 * @createTime	2012-5-28 12:34:43
 */
class Equipment extends DB_Connect {
	
	/**
	 * 设备编号，表结构中的主键
	 *
	 * @var	Unsigned int 设备编号
	 */
	private $_id;
	 
	/**
	 * 设备名称
	 *
	 * @var string 设备名称
	 */
	private $_name;
	 
	/**
	 * 设备状态，表示设备目前所处的状态标识
	 *
	 * 0：设备空缺 1：正常使用 -1：等待维修
	 * @var	int 设备状态
	 */
	private $_state;
	 
	/**
	 * 设备位置，设备被安放的场所说明
	 *
	 * 可以是房间编号
	 * @var string 设备位置
	 */
	private $_position;
	 
	/**
	 * 设备价格，设备的赔偿价格
	 *
	 * 设备的赔偿价格是成本的几倍
	 * @var string 设备价格
	 */
	private $_price;
	 
	/**
	 * 构造函数，接受一个编号参数。如果这个参数有效，则将它存入私有变量$_id中
	 *
	 * @param int $id设备编号
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
	 * 通过读入的编号参数，查询设备表，获得一个数组
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
		
		$query = "SELECT equipment_id, equipment_name, equipment_state, equipment_position, equipment_price
				FROM `equipment` WHERE equipment_id = '$id' LIMIT 1";
		$result = @mysql_query($query);
		
		if(mysql_num_rows($result) == 0)
		{
			return NULL;
		}
		else
		{
			$row = mysql_fetch_assoc($result);
			return array(
			"id" => $row['equipment_id'],
			"name" => $row['equipment_name'],
			"state" => $row['equipment_state'],
			"position" => $row['equipment_position'],
			"price" => $row['equipment_price']		
			);
		}	
	}
	
	/**
	 * 通过读入的位置参数，查询设备表，获得一个数组
	 *
	 * @param int $position设备位置
	 * @return array
	 */
	public function loadDataByPosition($position)
	{
		$query = "SELECT equipment_id, equipment_name, equipment_state, equipment_position, equipment_price
				FROM `equipment` WHERE equipment_position = '$position' LIMIT 1";
		$result = @mysql_query($query);
		
		if(mysql_num_rows($result) == 0)
		{
			return NULL;
		}
		else
		{
			$row = mysql_fetch_assoc($result);
			return array(
			"id" => $row['equipment_id'],
			"name" => $row['equipment_name'],
			"state" => $row['equipment_state'],
			"position" => $row['equipment_position'],
			"price" => $row['equipment_price']		
			);
		}	
	}
	
	/**
	 * 验证表单，创建新的设备信息
	 *
	 * @return 成功返回TRUE，失败返回出错信息
	 */
	public function createData()
	{
		// 若action设置不正确，退出
		if($_POST['action'] != 'equipments_new')
		{
			return "不合法请求";
		}
		
		// 转义表单提交过来的数据
		$id = htmlspecialchars($_POST['id'], ENT_QUOTES);
		$name = htmlspecialchars($_POST['name'], ENT_QUOTES);
		$state = htmlspecialchars($_POST['state'], ENT_QUOTES);
		$position = htmlspecialchars($_POST['position'], ENT_QUOTES);
		$price = htmlspecialchars($_POST['price'], ENT_QUOTES);
		
		// 判断是否需要自动生成设备编号
		if($id == "")
		{
			// 不添加设备编号，会自动按顺序生成
			$query = "INSERT INTO `equipment`
			(equipment_name, equipment_state, equipment_position, equipment_price)
			VALUES('$name','$state','$position','$price')";
		}
		else
		{
			// 为了保持设备编号唯一性，需要检查设备编号
			$query = "SELECT equipment_id FROM `equipment` WHERE equipment_id = '$id'";
			$result = @mysql_query($query);	
			if(mysql_num_rows($result) != 0)
			{
				return "设备编号不能相同";
			}
			
			$query = "INSERT INTO `equipment`
			(equipment_id, equipment_name, equipment_state, equipment_position, equipment_price)
			VALUES('$id','$name','$state','$position','$price')";
		}
		$result = @mysql_query($query);
		if (mysql_affected_rows() == 0)
		{	
			return "创建失败";
		}
		else return TRUE;
	}
	
	/**
	 * 验证表单，更新设备信息
	 *
	 * @return 成功返回TRUE，失败返回出错信息
	 */
	public function updateData()
	{
		// 若action设置不正确，退出
		if($_POST['action'] != 'equipments_edit')
		{
			return "不合法请求";
		}
		
		// 转义表单提交过来的数据
		$id = htmlspecialchars($_POST['old_id'], ENT_QUOTES);
		$name = htmlspecialchars($_POST['name'], ENT_QUOTES);
		$state = htmlspecialchars($_POST['state'], ENT_QUOTES);
		$position = htmlspecialchars($_POST['position'], ENT_QUOTES);
		$price = htmlspecialchars($_POST['price'], ENT_QUOTES);
		
		$query = "UPDATE `equipment` 
		SET equipment_name='$name', equipment_state='$state', equipment_position='$position', equipment_price='$price' 
		WHERE equipment_id='$id'";
		$result = @mysql_query($query);
		if (mysql_affected_rows() == 0)
		{	
			return "创建失败";
		}
		else return TRUE;
	}
	
	/**
	 * 读入参数设备编号和设备状态，更新设备状态
	 *
	 * @param int $id设备编号
	 * @param int $state设备状态
	 * @return 成功返回TRUE，失败返回出错信息
	 */
	public function updateStateById($id, $state)
	{
		$query = "UPDATE `equipment` 
		SET equipment_state='$state'
		WHERE equipment_id='$id'";
		$result = @mysql_query($query);
		if (mysql_affected_rows() == 0)
		{	
			return "更新状态失败";
		}
		else return TRUE;
	}	
}
?>