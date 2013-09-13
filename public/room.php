<?php
include_once("../sys/core/init.inc.php");

/* 初始化页面信息 */
$page_title = "房务管理 | 酒店后勤管理系统";
$page_h1 = "酒店后勤管理——房务管理、设备管理、仓库管理";

/* 载入页头 */
include_once("assets/common/header.inc.php");

/* 载入CSS和JS部分 */
?>
<link href="assets/css/room.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="assets/js/room.js"></script>
<?php

/* 载入模板 */
include_once("assets/common/formwork.inc.php");

/* 查询所有的房间信息 */
$query = "SELECT room_id, room_type, room_price, room_state FROM `room`";
$result = @mysql_query($query);
if(mysql_num_rows($result) == 0) {
	$lists = array();
} else {
	$lists = array();
	while( $row = mysql_fetch_assoc($result) ){
		
		/** 查询房间包含设备信息 **/
		$id = $row['room_id'];
		$query = "SELECT equipment_id, equipment_name, equipment_state, equipment_position, equipment_price
				FROM `equipment` WHERE equipment_position = '$id'";
		$result2 = @mysql_query($query);
		
		if(mysql_num_rows($result) == 0) {
			$equLists = array();
		} else {
			$equLists = array();
			while( $row2 = mysql_fetch_assoc($result2) ){
				$equ = array(
				"id" => $row2['equipment_id'],
				"name" => $row2['equipment_name'],
				"state" => $row2['equipment_state'],
				"position" => $row2['equipment_position'],
				"price" => $row2['equipment_price']		
				);
				$equLists[] = $equ;
			}
		}	
		
		/** 构成房间信息数组 **/
		$room = array(
		"id" => $row['room_id'],
		"type" => $row['room_type'],
		"price" => $row['room_price'],
		"state" => $row['room_state'],
		"equipment" => $equLists
		);
		$lists[] = $room;
	}
}

/* 载入页面正文数据表格部分 */
echo '<div id="body">';
if(count($lists) == 0) {
	// 如果没有数据
	echo '<div id="data_none">没有找到 符合要求 的记录</div>';
} else {
	echo '<table cellpadding="0" cellspacing="0">
			<tr>
				<th>房间号</th>
				<th>房间类型</th>
				<th>房间价格</th>
				<th>房间状态</th>
				<th>设备状态</th>
			</tr>';
	foreach($lists as $list) {
				
		/** 解码状态代表的文字 **/
		if($list['state'] == 0) {
			$state = "空闲";
		} else if($list['state'] == 1) {
			$state = "已入住";
		} else if($list['state'] == 2) {
			$state = "打扫房间中";
		} else if($list['state'] == 3) {
			$state = "被预定";
		} else if($list['state'] == -1) {
			$state = "维修中";
		} else {
			$state = "状态未知";
		}
		
		/** 组合设备状态 **/
		if(count($list['equipment']) != 0 ) {
			$equipment = "";
			foreach($list['equipment'] as $equ) {
				
				/*** 解码设备状态代表的文字 ***/
				if($equ['state'] == 0) {
					$equ_state = '<span class="STATE_YELLOW">设备空缺</span>';
				} else if($equ['state'] == 1) {
					$equ_state = '<span class="STATE_GREEN">正常使用</span>';
				} else if($equ['state'] == -1) {
					$equ_state = '<span class="STATE_RED">等待维修</span>';
				} else {
					$equ_state = '<span class="STATE_YELLOW">状态未知</span>';
				}
				
				$equipment .= '<ul>
								<li>设备编号: ' . $equ['id'] . '</li>
								<li>设备名称: ' . $equ['name'] . '</li>
								<li>设备状态: ' . $equ_state . '</li>
								<li>设备位置: ' . $equ['position'] . '</li>
								<li>设备价格: ' . $equ['price'] . '</li>
							</ul>';
			}
		} else $equipment = "无设备信息";
		
		echo '<tr>
				<td><a href="checkout.php?id=' . $list['id'] . '">' . $list['id'] . '</a></td>
				<td>' . $list['type'] . '</td>
				<td>' . $list['price'] . '</td>
				<td>' . $state . '</td>
				<td class="detail">' . $equipment . '</td>
			</tr>';
	}
	echo '</table>';
}
echo '</div>';

/* 载入页尾 */
include_once("assets/common/footer.inc.php");
?>