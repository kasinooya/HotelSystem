<?php

/**
 * 创建消费物品记录信息
 *
 * PHP version 5
 *
 * @author
 * @copyright
 * @createTime	2012-5-28 12:34:43
 */
class DamageRecord extends DB_Connect {
	
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
	 * 验证表单，创建新的消费物品记录
	 *
	 * @return 成功返回TRUE，失败返回出错信息
	 */
	public function createData()
	{
		// 若action设置不正确，退出
		if($_POST['action'] != 'damage_record_new')
		{
			return "不合法请求";
		}
		
		// 转义表单提交过来的数据
		$item_id = htmlspecialchars($_POST['item_id'], ENT_QUOTES);
		$quantity = htmlspecialchars($_POST['quantity'], ENT_QUOTES);
		$state = htmlspecialchars($_POST['state'], ENT_QUOTES);
		
		// 获取当前时间戳
		$stime = time() + TIMEZONE;
		
		// 判断用户是否签单
		if($state == 1)
		{
			$etime = $stime + 1;
			$confirmed = 1;
		}
		else
		{
			$etime = 0;
			$confirmed = 0;
		}
		
		// 不添加记录编号，会自动按顺序生成
		$query = "INSERT INTO `damage_record`
		(damageRecord_item_id, damageRecord_quantity, damageRecord_state, damageRecord_stime, damageRecord_etime, damageRecord_confirmed)
		VALUES('$item_id','$quantity','$state','$stime','$etime','$confirmed')";
		$result = @mysql_query($query);
		if (mysql_affected_rows() == 0)
		{	
			return "创建失败";
		}
		else return TRUE;
	}
}
?>