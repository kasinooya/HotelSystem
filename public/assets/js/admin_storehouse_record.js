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
		// 禁止头部背景被选择
		$("#bg_head, #head, #pageName, .HDbtn").select(function(e) {
            return false;
        });
		
		// 点亮目前的功能模块标志
		$("#btn3").css({
			"background" : "#1084B2"
		});		
	});
}