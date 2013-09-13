<?php
include_once("../sys/core/init.inc.php");

/* 初始化页面信息 */
$page_title = "出入库记录 | 酒店后勤管理系统";
$page_h1 = "酒店后勤管理——房务管理、设备管理、仓库管理";

/* 检查模式是新建还是修改 */
if( isset($_GET['id']) ) {
	// 修改模式，目前设计出入库记录不能编辑
	$submit = "修改出入库记录";
	$action = "storehouse_record_edit";
}
else {
	// 新建模式
	$submit = "添加出入库记录";
	$action = "storehouse_record_new";
}

/* 载入页头 */
include_once("assets/common/header.inc.php");

/* 载入CSS和JS部分 */
?>
<link href="assets/css/admin_storehouse_record.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="assets/js/admin_storehouse_record.js"></script>
<?php

/* 载入模板 */
include_once("assets/common/formwork.inc.php");

echo <<<EDIT_BOX
<div class="edit-box">
	<form action="assets/cgi/admin.php" method="post">
    	<fieldset>
        	<legend>$submit</legend>
            <label for="type">选择出库或入库</label>
            <select name="type" id="type">
            	<option value="0" selected>出库</option>
                <option value="1">入库</option>
            </select>
            <label for="list">物品清单</label>
            <textarea name="list" id="list"></textarea>
			<label for="user">物品接收方</label>
            <input type="text" name="user" id="user" value="" />
            <input type="hidden" name="id" value="{$_GET['id']}" />
            <input type="hidden" name="token" value="{$_SESSION['token']}" /> 
			<input type="hidden" name="action" value="$action" />
			<input type="submit" name="submit" value="$submit" /> or <a href="./equipments.php">cancel</a>
		</fieldset>
	</form>  
</div>
EDIT_BOX;

/* 载入页尾 */
include_once("assets/common/footer.inc.php");
?>