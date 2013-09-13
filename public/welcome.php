<?php
include_once("../sys/core/init.inc.php");

/* 初始化页面信息 */
$page_title = "酒店后勤管理系统";
$page_h1 = "酒店后勤管理——房务管理、设备管理、仓库管理";

/* 载入页头 */
include_once("assets/common/header.inc.php");

/* 载入CSS和JS部分 */
?>
<link href="assets/css/welcome.css" rel="stylesheet" type="text/css" />
<?php

/* 载入模板 */
include_once("assets/common/formwork.inc.php");

echo '<p>Welcome To 酒店后勤管理系统</p>';

/* 载入页尾 */
include_once("assets/common/footer.inc.php");
?>