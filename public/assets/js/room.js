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
		$("#btn1").css({
			"background" : "#1084B2"
		});
		
		// 维护模态窗口的功能函数
		var fx = {
			
			// 检查模态窗口是否已存在，存在则返回该窗口，否则创建一个新窗口并返回该新窗口
			"initModal" : function() {
				// 如果没有元素匹配，则长度属性等于0
				if( $(".modal-window").length == 0 ) {
					// 创建一个div元素，为它添加一个class，然后将它追加到body标签之中
					return $("<div>").hide().addClass("modal-window").appendTo("body");
				}
				else {
					// 若模态窗口已存在，这返回该模态窗口
					return $(".modal-window");
				}
			},
			
			// 创建一个遮盖层，并让模态窗口淡入
			"boxin" : function(data, modal) {
				// 为页面创建一个	覆盖层，并为其添加一个class和一个事件处理函数，然后将它追加到body元素
				$("<div>").hide().addClass("modal-overlay").click(function(event){
					fx.boxout(event);
				}).appendTo("body");
				
				// 将数据载入模态宽口并将它追加到body元素
				modal.hide().append(data).appendTo("body");
				
				// 淡入模态窗口和覆盖层
				$(".modal-window, .modal-overlay").fadeIn("slow");
			},
			
			// 淡出模态窗口并将其从DOM中删除
			"boxout" : function(event) {
				// 如果该函数用作某个元素的事件处理函数，那就在事件触发时阻止其默认行为
				if( event != undefined) {
					event.preventDefault();	
				}
				
				// 从所有的链接中删除active class
				$("a").removeClass("active");
				
				// 淡出模态窗口并将其删除
				$(".modal-window, .modal-overlay").fadeOut("slow", function() {
					$(this).remove();
				});
			}
		};
		
		// （点击房间号时）将退房要求在模态窗口中显示出来
		$("td>a").live("click", function(event){
			
			// 阻止链接载入
			event.preventDefault();
			
			// 为链接添加 active class
			$(this).addClass("active");
			
			// 从链接的href属性中得到查询字符串
			var data = $(this).attr("href").replace(/.+?\?(.*)$/, "$1"),
			
			// 获得房间号
			room_id = data.split("=")[1],
			
			// 检查模态窗口是否存在，若存在则选中它，否则就创建一个新的
			modal = fx.initModal();
							
			// 设定框体标题和按钮文字
			var title1 = "消费物品核算";
			var action1 = "consume_record_new";
			var title2 = "损坏物品核算";
			var action2 = "damage_record_new";
			var title3 = "遗留物品信息传送";
			var action3 = "";
					
			var content1 = 
			'<form action="assets/cgi/ajax.php" method="post">' +
				'<fieldset class="modal-form">' +
        			'<legend>' + title1 + '</legend>' +
		            '<label for="item_id">消费物品编号</label>' +
		            '<input type="text" name="item_id" id="item_id" value="" />' +
		            '<label for="quantity">消费数量</label>' +
        		    '<input type="text" name="quantity" id="quantity" value="" />' +
		            '<label for="state">记录状态</label>' +
        		    '<select name="state" id="state">' +
		            	'<option value="0" selected>新记录</option>' +
        		        '<option value="1">已付费</option>' +
		            '</select>' +
					'<input type="hidden" name="action" value="' + action1 + '" />' +
					'<input type="submit" name="submit" value="' + title1 + '" /> or <a href="./room.php">cancel</a>' +
				'</fieldset>' +
			'</form>';
			
			var content2 = 
			'<form action="assets/cgi/ajax.php" method="post">' +
				'<fieldset class="modal-form">' +
        			'<legend>' + title2 + '</legend>' +
		            '<label for="item_id">损坏物品编号</label>' +
		            '<input type="text" name="item_id" id="item_id" value="" />' +
		            '<label for="quantity">损坏数量</label>' +
        		    '<input type="text" name="quantity" id="quantity" value="" />' +
		            '<label for="state">记录状态</label>' +
        		    '<select name="state" id="state">' +
		            	'<option value="0" selected>新记录</option>' +
        		        '<option value="1">已付费</option>' +
		            '</select>' +
					'<input type="hidden" name="action" value="' + action2 + '" />' +
					'<input type="submit" name="submit" value="' + title2 + '" /> or <a href="./room.php">cancel</a>' +
				'</fieldset>' +
			'</form>';
			
			var content3 = 
			'<form action="" method="post">' +
				'<fieldset class="modal-form">' +
        			'<legend>' + title3 + '</legend>' +
		            '<label for="room_id">房间号</label>' +
		            '<input type="text" name="room_id" id="room_id" value="' + room_id + '" />' +
		            '<label for="item">遗留物品</label>' +
        		    '<input type="text" name="item" id="item" value="" />' +
					'<input type="hidden" name="action" value="' + action3 + '" />' +
					'<input type="submit" name="submit" id="special-btn" value="' + title3 + '" /> or <a href="./room.php">cancel</a>' +
				'</fieldset>' +
			'</form>';
			
			$(".modal-form a:contains(cancel)").live("click", function(event) {
				fx.boxout(event);	
			});
			
			// 为遗留物品传送数据
			$("#special-btn").live("click", function(event) {
				event.preventDefault();
				
				var formdata = $(this).parents("form").serializeArray();								
				
				var content = 
				'<form action="" method="post">' +
					'<fieldset class="modal-form">' +
						'<legend>以下遗留物品信息已经传送至前台</legend>' +
						'<label class="big-label" for="room_id">房间号: ' + formdata[0].value + '</label>' +
						'<label class="big-label" for="item">遗留物品: ' + formdata[1].value + '</label>' +
						'<a class="admin" href="./room.php">确定</a>' +
					'</fieldset>' +
				'</form>';
				
				modal.html(content);	
			});
						
			fx.boxin((content3 + content1 + content2), modal);	
		});
	});
}