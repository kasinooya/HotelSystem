/**
 * SAMP对象
 *
 * 用来处理Ajax消息返回数据的简单Ajax消息协议(Simple Ajax Message Protocol)
 *
 * @var: samp	未处理过的SAMP数据
 * @CreateTime:	2012-5-16 14:40:05
 */
function SAMP(samp)
{
	// 从构造函数中读取，只有在实例化后才有
	var msg = samp;
	
	/**
	 * 声明的私有变量
	 *
	 * @var: code	消息代码
	 * @var: code1	消息代码主体，第一级主要消息代码
	 * @var: code2	消息代码子体，第二级次要消息代码
	 * @var: data	数据部分
	 */
	var code, code1, code2, data;
	
	/**
	 * 私有方法，分析处理SAMP数据
	 */
	function handleMsg()
	{
		if(msg == "" || msg == null) {
			code = "101";
		}
		
		var str = msg.split("|");
		if(str[0] != "") {
			// 协议前面出现非法文字或警告
			code = "102";
		} else if(str[1] != "" && str[1] != undefined) {
			code = str[1];
			data = str[2];
		} else {
			// 消息代码部位没有内容，不符合协议构造规范
			code = "104";
		}
		
		var codeStr = code.split(".");
		code1 = codeStr[0];
		code2 = codeStr[1];				
	}
	// 构造函数中默认执行handeleMsg方法
	handleMsg();
	
	/**
	 * 获取SAMP中的消息代码，一般是第一部分数据
	 */
	this.getCode = function() {
		return code;
	}
	
	/**
	 * 获取SAMP中的消息代码主体，一般是第一级主要消息代码
	 */
	this.getCode1 = function() {
		return code1;
	}
	
	/**
	 * 获取SAMP中的消息代码子体，一般是第二级次要消息代码
	 */
	this.getCode2 = function() {
		return code2;
	}
	
	/**
	 * 获取SAMP中的数据，一般是最后部分数据
	 */
	this.getData = function() {
		return data;
	}
}