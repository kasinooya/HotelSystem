<?php
include_once("../sys/core/init.inc.php");

/* 初始化页面信息 */
$page_title = "报修记录 | 酒店后勤管理系统";
$page_h1 = "酒店后勤管理——房务管理、设备管理、仓库管理";

/* 载入页头 */
include_once("assets/common/header.inc.php");

/* 载入CSS和JS部分 */
?>
<link href="assets/css/repair_list.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="assets/js/repair_list.js"></script>
<?php

/* 载入模板 */
include_once("assets/common/formwork.inc.php");

/* 查询所有的报修记录信息 */
$query = "SELECT repairList_id, repairList_room_id, repairList_equipment_id, repairList_state, repairList_stime, repairList_etime, repairList_deadline, repairList_detail FROM `repair_list`";
$result = @mysql_query($query);
if(mysql_num_rows($result) == 0) {
	$lists = array();
} else {
	$lists = array();
	while( $row = mysql_fetch_assoc($result) ){
		
		/** 查询设备编号包含设备信息 **/
		$id = $row['repairList_equipment_id'];
		$query = "SELECT equipment_id, equipment_name, equipment_state, equipment_position, equipment_price
				FROM `equipment` WHERE equipment_id = '$id' LIMIT 1";
		$result2 = @mysql_query($query);
		
		if(mysql_num_rows($result) == 0) {
			$equipment = array();
		} else {
			$row2 = mysql_fetch_assoc($result2);
			$equipment = array(
			"id" => $row2['equipment_id'],
			"name" => $row2['equipment_name'],
			"state" => $row2['equipment_state'],
			"position" => $row2['equipment_position'],
			"price" => $row2['equipment_price']		
			);
		}
		
		/** 构成报修记录信息数组 **/
		$list = array(
		"id" => $row['repairList_id'],
		"room_id" => $row['repairList_room_id'],
		"equipment_id" => $row['repairList_equipment_id'],
		"state" => $row['repairList_state'],
		"stime" => $row['repairList_stime'],
		"etime" => $row['repairList_etime'],
		"deadline" => $row['repairList_deadline'],
		"detail" => $row['repairList_detail'],
		"equipment_info" => $equipment
		);
		$lists[] = $list;
	}
}

/* 输出页面正文 */
echo '<div id="body">';

/* 添加增加报修记录按钮 */
echo '<p class="btnLine"><a class="admin" href="admin_repair_list.php">+ 添加报修记录</a></p>';

/* 载入页面正文数据表格部分 */
if(count($lists) == 0) {
	// 如果没有数据
	echo '<div id="data_none">没有找到 符合要求 的记录</div>';
} else {
	echo '<table cellpadding="0" cellspacing="0">
			<tr>
				<th>记录编号</th>
				<th>设备编号</th>
				<th>设备名称</th>
				<th>设备所在房间</th>
				<th>维修状态</th>
				<th>报修时间</th>
				<th>截止时间</th>
				<th>完成时间</th>
				<th>备注说明</th>
			</tr>';
	foreach($lists as $list) {
		
		/** 解码报修记录状态代表的文字 **/
		if($list['state'] == 0) {
			$state = '<span class="STATE_YELLOW">等待维修</span>';
		} else if($list['state'] == 1) {
			$state = '<span class="STATE_GREEN">维修完成</span>';
		} else if($list['state'] == -1) {
			$state = '<span class="STATE_RED">维修失败</span>';
		} else {
			$state = '<span class="STATE_YELLOW">状态未知</span>';
		}
		
		/** 解码时间戳代表的日期 **/
		$stime = strftime(DATEFORMAT, $list['stime']);
		$dtime = "截止至:".strftime(DATEFORMAT, $list['stime'] + $list['deadline']);
		if( $list['etime'] < $list['stime']) {
			$etime = '<a href="confirm_repair.php?id=' . $list['id'] . '">[确认]</a>';
		} else {
			$etime = strftime(DATEFORMAT, $list['etime']);
		}
				
		echo '<tr>
				<td><a href="admin_repair_list.php?id=' . $list['id'] . '">' . $list['id'] . '</a></td>
				<td>' . $list['equipment_id'] . '</td>
				<td>' . $list['equipment_info']['name'] . '</td>
				<td>' . $list['room_id'] . '</td>
				<td>' . $state . '</td>
				<td>' . $stime . '</td>
				<td>' . $dtime . '</td>
				<td>' . $etime . '</td>
				<td>' . $list['detail'] . '</td>
			</tr>';
	}
	echo '</table>';
}
echo '</div>';

/* 载入页尾 */
include_once("assets/common/footer.inc.php");
?>