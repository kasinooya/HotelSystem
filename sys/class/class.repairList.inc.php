<?php

/**
 * 创建并维护维修记录（新建维修记录，编辑维修记录等）
 *
 * PHP version 5
 *
 * @author
 * @copyright
 * @createTime	2012-5-28 19:45:33
 */
class RepairList extends DB_Connect {
	
	/**
	 * 记录编号，表结构中的主键
	 *
	 * @var	Unsigned int 记录编号
	 */
	private $_id;
	
	/**
	 * 记录编号
	 *
	 * @var Unsigned int 记录编号
	 */
	private $_equipment_id;
		 
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
	 * 输出私有变量$_equipment_id的值
	 *
	 * @return 存在值即返回，不存在则通过$id查询
	 */
	public function getEquipmentId()
	{
		if($this->_equipment_id != NULL) 
		{
			return $this->_equipment_id;
		}
		
		// 不存在则通过$id查询
		if($this->_id == NULL) 
		{
			return NULL;
		}
		else
		{
			$array = $this->loadDataById($this->_id);
			if($array == NULL)
			{
				return NULL;
			}
			else
			{
				return $array['equipment_id'];
			}
		}
	}
	
	/**
	 * 通过读入的编号参数，查询维修记录表，获得一个数组
	 *
	 * @param int $id记录编号
	 * @return array
	 */
	public function loadDataById($id = NULL)
	{
		if(!isset($id))
		{
			$id = $this->_id;
		}
						
		$query = "SELECT repairList_id, repairList_room_id, repairList_equipment_id, repairList_state, repairList_stime, repairList_etime, repairList_deadline, repairList_detail
				FROM `repair_list` WHERE repairList_id = '$id' LIMIT 1";
		$result = @mysql_query($query);
		
		if(mysql_num_rows($result) == 0) {
			return NULL;
		} else {
			$row = mysql_fetch_assoc($result);
			return array(
			"id" => $row['repairList_id'],
			"room_id" => $row['repairList_room_id'],
			"equipment_id" => $row['repairList_equipment_id'],
			"state" => $row['repairList_state'],
			"stime" => $row['repairList_stime'],
			"etime" => $row['repairList_etime'],
			"deadline" => $row['repairList_deadline'],
			"detail" => $row['repairList_detail']		
			);
		}	
	}
		
	/**
	 * 验证表单，创建新的维修记录
	 *
	 * @return 成功返回TRUE，失败返回出错信息
	 */
	public function createData()
	{
		// 若action设置不正确，退出
		if($_POST['action'] != 'repair_list_new')
		{
			return "不合法请求";
		}
		
		// 转义表单提交过来的数据
		$room_id = htmlspecialchars($_POST['room_id'], ENT_QUOTES);
		$equipment_id = htmlspecialchars($_POST['equipment_id'], ENT_QUOTES);
		$deadline = htmlspecialchars($_POST['deadline'], ENT_QUOTES);
		$detail = htmlspecialchars($_POST['detail'], ENT_QUOTES);
		
		// 获取当前时间戳
		$stime = time() + TIMEZONE;
		
		// 开始一个事务，设置事务不自动commit 
		mysql_query("BEGIN");				
		mysql_query("SET AUTOCOMMIT=0");
		
		// 不添加记录编号，会自动按顺序生成
		$query = "INSERT INTO `repair_list`
		(repairList_room_id, repairList_equipment_id, repairList_state, repairList_stime, repairList_etime, repairList_deadline, repairList_detail)
		VALUES('$room_id','$equipment_id',0,'$stime',0,'$deadline','$detail')";
		$result = @mysql_query($query);
		if (mysql_affected_rows() == 0)
		{	
			return "创建失败";
		}
		else
		{
			$msg = $this->_changeEquipmentState($equipment_id, -1);
			if($msg === TRUE || $msg === FALSE)
			{
				mysql_query("COMMIT");
				return TRUE;
			}
			else
			{
				mysql_query("ROLLBACK");
				return $msg;	
			}
		}
	}
	
	/**
	 * 验证表单，更新维修记录
	 *
	 * @return 成功返回TRUE，失败返回出错信息
	 */
	public function updateData()
	{
		// 若action设置不正确，退出
		if($_POST['action'] != 'repair_list_edit')
		{
			return "不合法请求";
		}
		
		// 转义表单提交过来的数据
		$id = htmlspecialchars($_POST['id'], ENT_QUOTES);
		$room_id = htmlspecialchars($_POST['room_id'], ENT_QUOTES);
		$equipment_id = htmlspecialchars($_POST['equipment_id'], ENT_QUOTES);
		$deadline = htmlspecialchars($_POST['deadline'], ENT_QUOTES);
		$detail = htmlspecialchars($_POST['detail'], ENT_QUOTES);
		
		// 开始一个事务，设置事务不自动commit 
		mysql_query("BEGIN");				
		mysql_query("SET AUTOCOMMIT=0");
		
		// 检查该维修对象设备编号是否变化
		$this->_id = $id;
		$old_equipment_id = $this->getEquipmentId();
		if($old_equipment_id != $equipment_id)
		{
			// 如果变化，需要把原有设备等待维修的状态取消
			$msg = $this->_changeEquipmentState($old_equipment_id, 1);
			if($msg !== TRUE && $msg !== FALSE)
			{
				return "更新原有设备状态失败，请不要更改设备编号";
			}
		}
		
		$query = "UPDATE `repair_list` 
		SET repairList_room_id='$room_id', repairList_equipment_id='$equipment_id', repairList_deadline='$deadline', repairList_detail='$detail' 
		WHERE repairList_id='$id'";
		$result = @mysql_query($query);
		if (mysql_affected_rows() == 0)
		{	
			return "创建失败";
		}
		else
		{
			if($old_equipment_id != $equipment_id)
			{
				$msg = $this->_changeEquipmentState($equipment_id, -1);
				if($msg === TRUE || $msg === FALSE)
				{
					mysql_query("COMMIT");
					return TRUE;
				}
				else
				{
					mysql_query("ROLLBACK");
					return $msg;	
				}
			}
			else
			{
				mysql_query("COMMIT");
				return TRUE;
			}
		}
	}
	
	/**
	 * 确认维修已经完成，更新设备状态
	 *
	 * @return 成功返回TRUE，失败返回出错信息
	 */
	public function repairConfirm()
	{
		// 若action设置不正确，退出
		if($_POST['action'] != 'repair_list_confirm')
		{
			return "不合法请求";
		}
		
		// 转义表单提交过来的数据
		$id = htmlspecialchars($_POST['id'], ENT_QUOTES);
		$equipment_id = htmlspecialchars($_POST['equipment_id'], ENT_QUOTES);
		
		// 获取当前时间戳
		$etime = time() + TIMEZONE;
		
		// 开始一个事务，设置事务不自动commit 
		mysql_query("BEGIN");				
		mysql_query("SET AUTOCOMMIT=0");
		
		$query = "UPDATE `repair_list` 
		SET repairList_state='1', repairList_etime='$etime' 
		WHERE repairList_id='$id'";
		$result = @mysql_query($query);
		if (mysql_affected_rows() == 0)
		{
			mysql_query("ROLLBACK");
			return "创建失败";
		}
		else
		{
			$msg = $this->_changeEquipmentState($equipment_id, 1);
			if($msg === TRUE || $msg === FALSE)
			{
				mysql_query("COMMIT");
				return TRUE;
			}
			else
			{
				mysql_query("ROLLBACK");
				return $msg;	
			}
		}
	}
	
	/**
	 * 创建设备对象，读入参数设备编号和设备状态，更新设备状态
	 *
	 * @param int $id设备编号
	 * @param int $state设备状态
	 * @return 成功返回TRUE，失败返回出错信息，没有查到$id所对应信息则返回FALSE
	 */
	private function _changeEquipmentState($id, $state)
	{
		$obj = new Equipment();
		if( $obj->loadDataById($id) == NULL )
		{
			return FALSE;
		}
		else
		{
			return $obj->updateStateById($id, $state);
		}
	}
}
?>