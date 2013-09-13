<?php
include_once("../sys/core/init.inc.php");

/* 初始化页面信息 */
$page_title = "消费物品记录 | 酒店后勤管理系统";
$page_h1 = "酒店后勤管理——房务管理、设备管理、仓库管理";

/* 载入页头 */
include_once("assets/common/header.inc.php");

/* 载入CSS和JS部分 */
?>
<link href="assets/css/consume_record.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="assets/js/consume_record.js"></script>
<?php

/* 载入模板 */
include_once("assets/common/formwork.inc.php");

/* 查询所有的消费记录信息 */
$query = "SELECT consumeRecord_id, consumeRecord_item_id, consumeRecord_quantity, consumeRecord_state, consumeRecord_stime, consumeRecord_etime, consumeRecord_confirmed FROM `consume_record`";
$result = @mysql_query($query);
if(mysql_num_rows($result) == 0) {
	$lists = array();
} else {
	$lists = array();
	while( $row = mysql_fetch_assoc($result) ){
		
		/** 查询物品编号包含物品信息 **/
		$id = $row['consumeRecord_item_id'];
		$query = "SELECT item_id, item_name, item_quantity, item_price, item_needAdd
				FROM `item` WHERE item_id = '$id' LIMIT 1";
		$result2 = @mysql_query($query);
		
		if(mysql_num_rows($result) == 0) {
			
			// 如果没有查到物品，则查询是否是设备信息编号
			$id = $row['consumeRecord_item_id'];
			$query = "SELECT equipment_id, equipment_name, equipment_state, equipment_position, equipment_price
					FROM `equipment` WHERE equipment_id = '$id' LIMIT 1";
			$result2 = @mysql_query($query);
			
			if(mysql_num_rows($result) == 0) {
				$item = array();
			} else {
				$row2 = mysql_fetch_assoc($result2);
				$item = array(
				"id" => $row2['equipment_id'],
				"name" => $row2['equipment_name'],
				"state" => $row2['equipment_state'],
				"position" => $row2['equipment_position'],
				"price" => $row2['equipment_price']		
				);
			}
		} else {
			$row2 = mysql_fetch_assoc($result2);
			$item = array(
			"id" => $row2['item_id'],
			"name" => $row2['item_name'],
			"state" => $row2['item_quantity'],
			"position" => $row2['item_price'],
			"price" => $row2['item_needAdd']		
			);
		}
		
		/** 构成消费记录信息数组 **/
		$list = array(
		"id" => $row['consumeRecord_id'],
		"item_id" => $row['consumeRecord_item_id'],
		"quantity" => $row['consumeRecord_quantity'],
		"state" => $row['consumeRecord_state'],
		"stime" => $row['consumeRecord_stime'],
		"etime" => $row['consumeRecord_etime'],
		"confirmed" => $row['consumeRecord_confirmed'],
		"item_info" => $item
		);
		$lists[] = $list;
	}
}

/* 输出页面正文 */
echo '<div id="body">';

/* 载入页面正文数据表格部分 */
if(count($lists) == 0) {
	// 如果没有数据
	echo '<div id="data_none">没有找到 符合要求 的记录</div>';
} else {
	echo '<table cellpadding="0" cellspacing="0">
			<tr>
				<th>记录编号</th>
				<th>物品编号</th>
				<th>物品名称</th>
				<th>物品数量</th>
				<th>记录状态</th>
				<th>记录生成时间</th>
				<th>用户签单时间</th>
			</tr>';
	foreach($lists as $list) {
		
		/** 解码消费记录状态代表的文字 **/
		if($list['state'] == 0) {
			$state = '<span class="STATE_RED">New</span> 新记录';
		} else if($list['state'] == 1) {
			$state = '已付费';
		} else {
			$state = '状态未知';
		}
		
		/** 解码时间戳代表的日期 **/
		$stime = strftime(DATEFORMAT, $list['stime']);
		if( $list['etime'] > $list['stime'] && $list['confirmed'] == 1) {
			$etime = strftime(DATEFORMAT, $list['etime']);
		} else {
			$etime = "尚未签单";
		}
				
		echo '<tr>
				<td>' . $list['id'] . '</td>
				<td>' . $list['item_id'] . '</td>
				<td>' . $list['item_info']['name'] . '</td>
				<td>' . $list['quantity'] . '</td>
				<td>' . $state . '</td>
				<td>' . $stime . '</td>
				<td>' . $etime . '</td>
			</tr>';
	}
	echo '</table>';
}
echo '</div>';

/* 载入页尾 */
include_once("assets/common/footer.inc.php");
?>