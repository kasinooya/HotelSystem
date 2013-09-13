<?php
include_once("../sys/core/init.inc.php");

/* 初始化页面信息 */
$page_title = "出入库记录管理 | 酒店后勤管理系统";
$page_h1 = "酒店后勤管理——房务管理、设备管理、仓库管理";

/* 载入页头 */
include_once("assets/common/header.inc.php");

/* 载入CSS和JS部分 */
?>
<link href="assets/css/storehouse.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="assets/js/storehouse.js"></script>
<?php

/* 载入模板 */
include_once("assets/common/formwork.inc.php");

/* 查询所有的仓库出入库信息 */
$query = "SELECT storehouseRecord_id, storehouseRecord_time, storehouseRecord_type, storehouseRecord_list, storehouseRecord_user FROM `storehouse_record`";
$result = @mysql_query($query);
if(mysql_num_rows($result) == 0) {
	$lists = array();
} else {
	$lists = array();
	while( $row = mysql_fetch_assoc($result) ){
		
		/** 构成仓库出入库信息数组 **/
		$store = array(
		"id" => $row['storehouseRecord_id'],
		"time" => $row['storehouseRecord_time'],
		"type" => $row['storehouseRecord_type'],
		"list" => $row['storehouseRecord_list'],
		"user" => $row['storehouseRecord_user']
		);
		$lists[] = $store;
	}
}

/* 输出页面正文 */
echo '<div id="body">';

/* 添加增加出入库记录按钮 */
echo '<p class="btnLine"><a class="admin" href="admin_storehouse_record.php">+ 添加出入库记录</a></p>';

/* 载入页面正文数据表格部分 */
if(count($lists) == 0) {
	// 如果没有数据
	echo '<div id="data_none">没有找到 符合要求 的记录</div>';
} else {
	echo '<table cellpadding="0" cellspacing="0">
			<tr>
				<th>记录编号</th>
				<th>出入库时间</th>
				<th>出库或入库</th>
				<th>物品清单</th>
				<th>物品接收方</th>
			</tr>';
	foreach($lists as $list) {
		
		/** 解码仓库出入库状态代表的文字 **/
		if($list['type'] == 0) {
			$type = "出库";
		} else if($list['type'] == 1) {
			$type = "入库";
		} else {
			$type = "错误类型";
		}
		
		/** 解码时间戳代表的日期 **/
		$time = strftime(DATEFORMAT, $list['time']);
		
		echo '<tr>
				<td>' . $list['id'] . '</td>
				<td>' . $time . '</td>
				<td>' . $type . '</td>
				<td>' . $list['list'] . '</td>
				<td>' . $list['user'] . '</td>
			</tr>';
	}
	echo '</table>';
}
echo '</div>';

/* 载入页尾 */
include_once("assets/common/footer.inc.php");
?>