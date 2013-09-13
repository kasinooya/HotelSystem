<?php
include_once("../../../sys/core/init.inc.php");

/* 为表单action创建一个查找数组 */
$actions = array(
		'equipments_new' => array(
			'object' => 'Equipment',
			'method' => 'createData',
			'header' => '../../equipments.php'
		),
		'equipments_edit' => array(
			'object' => 'Equipment',
			'method' => 'updateData',
			'header' => '../../equipments.php'
		),
		'repair_list_new' => array(
			'object' => 'RepairList',
			'method' => 'createData',
			'header' => '../../repair_list.php'
		),
		'repair_list_edit' => array(
			'object' => 'RepairList',
			'method' => 'updateData',
			'header' => '../../repair_list.php'
		),
		'repair_list_confirm' => array(
			'object' => 'RepairList',
			'method' => 'repairConfirm',
			'header' => '../../repair_list.php'
		),
		'storehouse_record_new' => array(
			'object' => 'StorehouseRecord',
			'method' => 'createData',
			'header' => '../../storehouse.php'
		),
		'consume_record_new' => array(
			'object' => 'RepairList',
			'method' => 'createData',
			'header' => '../../consume_record.php'
		),
		'damage_record_new' => array(
			'object' => 'StorehouseRecord',
			'method' => 'createData',
			'header' => '../../damage_record.php'
		)
);

/* 保证session中的防跨站标记与提交过来的标记一致及请求action合法（在关联数组中） */
if( $_POST['token'] == $_SESSION['token'] && isset($actions[$_POST['action']]) ) {
	$use_array = $actions[$_POST['action']];
	$obj = new $use_array['object']();
	if( TRUE === $msg = $obj->$use_array['method']() ) {
		header("Location: " . $use_array['header']);
		exit;
	} else {
		die($msg . ' <a href="#" onClick="window.history.go(-1);">[上一步]</a> <a href="' . $use_array['header'] . '">[返回]</a>');
	}
} else {
	// 如果token/action非法，重定向到首页
	header("Location: ../../login.html");
	exit;	
}
?>