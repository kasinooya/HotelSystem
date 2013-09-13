<?php
include_once("../sys/core/init.inc.php");

/* 初始化页面信息 */
$page_title = "添加编辑设备管理 | 酒店后勤管理系统";
$page_h1 = "酒店后勤管理——房务管理、设备管理、仓库管理";

/* 检查模式是新建还是修改 */
if( isset($_GET['id']) ) {
	// 修改模式
	$submit = "修改设备信息";
	$action = "equipments_edit";
	
	$obj = new Equipment($_GET['id']);
	if( NULL === $array = $obj->loadDataById() )
	{
		// 没有查到id记录
		header("Location: ./equipments.php");
		exit;
	}
}
else {
	// 新建模式
	$submit = "添加设备信息";
	$action = "equipments_new";
}

/* 载入页头 */
include_once("assets/common/header.inc.php");

/* 载入CSS和JS部分 */
?>
<link href="assets/css/admin_equipments.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="assets/js/admin_equipments.js"></script>
<?php

/* 载入模板 */
include_once("assets/common/formwork.inc.php");

echo <<<EDIT_BOX
<div class="edit-box">
	<form action="assets/cgi/admin.php" method="post">
    	<fieldset>
        	<legend>$submit</legend>
            <label for="id">设备编号</label>
            <input type="text" name="id" id="id" value="{$array['id']}" />
            <label for="name">设备名称</label>
            <input type="text" name="name" id="name" value="{$array['name']}" />
            <label for="state">设备状态</label>
            <select name="state" id="state">
            	<option value="1" selected>正常使用</option>
                <option value="0">设备空缺</option>
                <option value="-1">等待维修</option>
            </select>
			<label for="position">设备位置</label>
            <input type="text" name="position" id="position" value="{$array['position']}" />
            <label for="price">设备价格</label>
            <input type="text" name="price" id="price" value="{$array['price']}" />
            <input type="hidden" name="old_id" value="{$_GET['id']}" />
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