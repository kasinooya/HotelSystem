<?php
include_once("../sys/core/init.inc.php");

/* 初始化页面信息 */
$page_title = "仓库管理 | 酒店后勤管理系统";
$page_h1 = "酒店后勤管理——房务管理、设备管理、仓库管理";

/* 载入页头 */
include_once("assets/common/header.inc.php");

/* 载入CSS和JS部分 */
?>
<link href="assets/css/item.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="assets/js/item.js"></script>
<?php

/* 载入模板 */
include_once("assets/common/formwork.inc.php");

/* 查询所有的仓库出入库信息 */
$query = "SELECT item_id, item_name, item_quantity, item_price, item_needAdd FROM `item`";
$result = @mysql_query($query);
if(mysql_num_rows($result) == 0) {
	$lists = array();
} else {
	$lists = array();
	while( $row = mysql_fetch_assoc($result) ){
		
		/** 构成仓库出入库信息数组 **/
		$store = array(
		"id" => $row['item_id'],
		"name" => $row['item_name'],
		"quantity" => $row['item_quantity'],
		"price" => $row['item_price'],
		"needAdd" => $row['item_needAdd']
		);
		$lists[] = $store;
	}
}

/* 输出页面正文 */
echo '<div id="body">';

/* 添加增加出入库记录按钮 */
echo '<p class="btnLine">
			<a class="admin" href="admin_storehouse_record.php">+ 添加出入库记录</a>
			<a class="admin" href="storehouse.php">> 查看出入库记录</a>
		</p>';

/* 载入页面正文数据表格部分 */
if(count($lists) == 0) {
	// 如果没有数据
	echo '<div id="data_none">没有找到 符合要求 的记录</div>';
} else {
	echo '<table cellpadding="0" cellspacing="0">
			<tr>
				<th>物品编号</th>
				<th>物品名称</th>
				<th>物品现有数量</th>
				<th>物品价格</th>
				<th>是否需要补货</th>
			</tr>';
	foreach($lists as $list) {
		
		/** 解码是否需要补货状态代表的文字 **/
		if($list['needAdd'] == 0) {
			$type = '<span class="STATE_GREEN">不需要</span>';
		} else if($list['needAdd'] == 1) {
			$type = '<span class="STATE_RED">需要补充</span>';
		} else {
			$type = "错误类型";
		}
		
		/** 解码时间戳代表的日期 **/
		$time = strftime(DATEFORMAT, $list['time']);
		
		echo '<tr>
				<td>' . $list['id'] . '</td>
				<td>' . $list['name'] . '</td>
				<td>' . $list['quantity']. '</td>
				<td>' . $list['price'] . '</td>
				<td>' . $type . '</td>
			</tr>';
	}
	echo '</table>';
}
echo '</div>';

/* 载入页尾 */
include_once("assets/common/footer.inc.php");
?>