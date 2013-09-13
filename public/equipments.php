<?php
include_once("../sys/core/init.inc.php");

/* 初始化页面信息 */
$page_title = "设备管理 | 酒店后勤管理系统";
$page_h1 = "酒店后勤管理——房务管理、设备管理、仓库管理";

/* 载入页头 */
include_once("assets/common/header.inc.php");

/* 载入CSS和JS部分 */
?>
<link href="assets/css/equipments.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="assets/js/equipments.js"></script>
<?php

/* 载入模板 */
include_once("assets/common/formwork.inc.php");

/* 查询所有的设备信息 */
$query = "SELECT equipment_id, equipment_name, equipment_state, equipment_position, equipment_price	FROM `equipment`";
$result = @mysql_query($query);
if(mysql_num_rows($result) == 0) {
	$lists = array();
} else {
	$lists = array();
	while( $row = mysql_fetch_assoc($result) ){
		
		/** 构成设备信息数组 **/
		$equ = array(
		"id" => $row['equipment_id'],
		"name" => $row['equipment_name'],
		"state" => $row['equipment_state'],
		"position" => $row['equipment_position'],
		"price" => $row['equipment_price']
		);
		$lists[] = $equ;
	}
}

/* 输出页面正文 */
echo '<div id="body">';

/* 添加增加设备记录按钮 */
echo '<p class="btnLine"><a class="admin" href="admin_equipments.php">+ 添加设备信息</a></p>';

/* 载入页面正文数据表格部分 */
if(count($lists) == 0) {
	// 如果没有数据
	echo '<div id="data_none">没有找到 符合要求 的记录</div>';
} else {
	echo '<table cellpadding="0" cellspacing="0">
			<tr>
				<th>设备编号</th>
				<th>设备名称</th>
				<th>设备状态</th>
				<th>设备位置</th>
				<th>设备价格</th>
			</tr>';
	foreach($lists as $list) {
		
		/** 解码设备状态代表的文字 **/
		if($list['state'] == 0) {
			$state = '<span class="STATE_YELLOW">设备空缺</span>';
		} else if($list['state'] == 1) {
			$state = '<span class="STATE_GREEN">正常使用</span>';
		} else if($list['state'] == -1) {
			$state = '<span class="STATE_RED">等待维修</span>';
		} else {
			$state = '<span class="STATE_YELLOW">状态未知</span>';
		}
		
		echo '<tr>
				<td><a href="admin_equipments.php?id=' . $list['id'] . '">' . $list['id'] . '</td>
				<td>' . $list['name'] . '</td>
				<td>' . $state . '</td>
				<td>' . $list['position'] . '</td>
				<td>' . $list['price'] . '</td>
			</tr>';
	}
	echo '</table>';
}
echo '</div>';

/* 载入页尾 */
include_once("assets/common/footer.inc.php");
?>