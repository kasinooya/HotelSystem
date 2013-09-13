/**
 * 自动加载JS文件函数，可递归
 *
 * @var file：JS文件地址
 * @var func：回调函数
 */
function include_js(file, func) {
	var _doc = document.getElementsByTagName('head')[0];
	var js = document.createElement('script');
	js.setAttribute('type', 'text/javascript');
	js.setAttribute('src', file);
	_doc.appendChild(js);
	
	if (document.all) { //如果是IE
		js.onreadystatechange = func;
	} else {
		js.onload = func;
	}
}
include_js("assets/js/jquery.min.js", function() {
	include_js("assets/js/class.ajax.inc.js", start);
});

function start() {
	$(function() {
		// 初始化中间登陆框位置，并加上显示动画
		$("#loginBox").css({
			"position" : document.all?"absolute":"fixed",
			"top" : "50%",
			"left" : "50%",
			"margin-top" : - $("#loginBox").outerHeight()/1.9 + "px",
			"margin-left" : - $("#loginBox").outerWidth()/1.9 + "px",
		}).show(1500);
		
		// 在密码输入框，绑定监测回车的按钮事件
		$("#password").keydown(function(e) {
			if($("#user").val() != "") {
				var keycode = document.all ? event.keyCode : (e.which || e.keyCode);
				if(keycode == 13){
					checkUser();
				}
			}
		});
	});	
}

function checkUser() {
	if($("#user").val() != "" && $("#password").val() != "") {
		$.post("assets/cgi/login_checkUser.php",
		{"u":$("#user").val(), "p":$("#password").val()},
		function(data) {
			// 处理返回的判断结果
			var samp = new SAMP(data);
			var code1 = samp.getCode1();
			if(code1 == "200") {
				// 登陆成功
				window.location.href = "welcome.php";
			} else if(code1 == "205") {
				// 验证不通过
				$("#message").text("用户名或密码错误");
			} else alert("ERROR：" + code1 + " - " + data);
		});
	} else {
		$("#message").text("用户名和密码不能为空");
	}	
}