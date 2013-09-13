<?php
if (isset ($_POST["u"]) && isset($_POST["p"])) {		
	$user_name = $_POST["u"];
	$user_code = $_POST["p"];
		
	if($user_name == "sansan" && $user_code == "111111") {
		echo "|200|Pass|";
	}else die("|205.3|No pass, incorrect user or password|");
} else die("|404.1|No post|");
?>