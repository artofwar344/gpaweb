<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery.validate.min.js"></script>
<script src="http://code.jquery.com/jquery-migrate-1.1.0.js"></script>
<script type="text/javascript">
	var step = 0;
	var errorStep = -1;
	var status = -1;
	var finished = false;
	var success = false;
	$(function() {
		var table = $(".table_1");
		try {
			gpax.about();
		}
		catch(e) {
			$(".loading_2").hide();
			$(".activex_error").show();
			return false;
		}
		$.post("/home/products", {}, function(products) {
			$(".loading_2").hide();
			var tr;
			table.html('<tr><th class="align_left">激活商品</th><th>激活模式</th><th>可激活</th><th>已使用</th><th>分配量</th><th>申请中</th><th>已拒绝</th><th style="width:160px"></th></tr>');
			if (products.length == 0) {
				table.append('<tr class="none"><td colspan="8"><span>无可激活应用</span></td></tr>');
			}
			$.each(products, function(i, product) {
				tr = $("<tr />").attr("pid", product.productid).attr("alias", product.aliasname);
				tr.append(
						$("<td />").addClass("align_left").addClass("name").css("background-image", "url({{ Config::get('app.asset_url') }}images/activate/product/" + product.productid + ".png)")
							.append($("<a />").attr("href", "http://{{ app()->environment() }}/download.html").text(product.name))
					).append($("<td />").addClass("type").text(product.type))
					.append($("<td />").text(product.assigntotalcount - product.used))
					.append($("<td />").text(product.used))
					.append($("<td />").text(product.assigntotalcount))
					.append($("<td />").text(product.userkey_status == "{{ \Ca\UserKeyStatus::pending }}" ? product.requestcount : 0))
					.append($("<td />").text(product.denied > 0 ? product.denied : 0))
					.append(
						$("<td />").html(product.userkey_status == "{{ \Ca\UserKeyStatus::pending }}" ? '<span class="action pending">等待审批</span>' : '<a class="action apply" href="#">激活申请</a>')
							.append('<a class="action activate ' + (product.assigntotalcount - product.used <= 0 ? 'disabled' : '') + '" href="#">激活商品</a>')
					);
				tr.appendTo(table);
			});
			$(".table_1 tr:not(:first)").hover(function() {
				$(this).addClass("hover");
			}, function() {
				$(this).removeClass("hover");
			});
		}, "json");

		var selectedProductId;
		var dialogApplykey = $("div#dialogApplykey");
		var dialogActivate = $("div#dialogActivate");
		var dialogParams = { autoOpen: false, modal: true, resizable: false, width: 350, minHeight: 0 };
		dialogApplykey.dialog(dialogParams);
		dialogParams.width = 400;
		dialogActivate.dialog(dialogParams);
		var form = $("form#form_applykey");

		var productAlias;
		var productName;
		var uuid;

		$(".dialog_1 .close").click(function() {
			if ($(this).hasClass("close_disabled")) return false;
			$(this).closest(".dialog_1").dialog("close");
			return false;
		});

		table.on("click", ".apply", function() {
			var tr = $(this).closest("tr");
			selectedProductId = tr.attr("pid");
			$(".product_name", dialogApplykey).text(tr.find("td.name").text());
			$(".product_type", dialogApplykey).text(tr.find("td.type").text());
			form[0].reset();
			formValidate.resetForm();
			dialogApplykey.dialog("open");
			return false;
		});

		$(".submit", dialogApplykey).click(function() {
			if ($(this).hasClass("button_1_disabled")) return false;
			if(form.valid()) {
				$(this).addClass("button_1_disabled");
				$.post("/activate/applykey", form.serialize() + "&productid=" + selectedProductId, function(ret) {
					switch (ret.code) {
						case 1:
							window.location.reload();
							dialogApplykey.dialog("close");
							break;
					}
					$(this).removeClass("button_1_disabled");
				}, "json");
			}
			else $("input[type='text']", form).eq(0).select();
			return false;
		});

		var formValidate = form.validate({
			errorPlacement: function(error, element) {
				error.appendTo(element.closest("tr").find(".info").text(""));
				return false;
			},
			rules: {
				requestcount: {
					required: true,
					digits:true,
					min: 1
				},
				reason: {
					required: true
				}
			},
			messages: {
				requestcount: {
					required: "申请量只能为正整数",
					digits: "申请量只能为正整数",
					min: "申请量只能为正整数"
				},
				reason: {
					required: "申请原因不能为空"
				}
			}
		});

		//var gpax;
		if (!window.ActiveXObject) {
			gpax = {};
			gpax.computerId = "7CD1C38E54F4";
			gpax.finished = false;
			gpax.activateStatus = -1;
			gpax.productName = "Windows 6.2.9200.0.0.1.30.64";
			gpax.activate = function() {};
			gpax.isActivated = function() { return false;};
		}

		var errorMessage = {
			"0xC004C001": "激活服务器确定指定的产品密钥无效",
			"0xC004C003": "激活服务器确定指定的产品密钥被阻止",
			"0xC004B100": "激活服务器确定无法激活计算机",
			"0xC004C008": "激活服务器确定无法使用指定的产品密钥",
			"0xC004C020": "激活服务器报告多次激活密钥已超过其限制",
			"0xC004C021": "激活服务器报告已超过多次激活密钥扩展限制",
			"0xC004F009": "软件授权服务报告已超过宽限期",
			"0xC004F00F": "软件授权服务器报告硬件 ID 界限超过容许的级别",
			"0xC004F014": "软件授权服务报告产品密钥不可用",
			"0xC004F02C": "软件授权服务报告脱机激活数据的格式不正确",
			"0xC004F035": "软件授权服务报告无法使用批量许可证产品密钥激活",
			"0xC004F038": "密钥管理服务 (KMS) 报告的数量不足",
			"0xC004F039": "软件授权服务报告无法激活计算机。未启用密钥管理服务 (KMS)",
			"0xC004F041": "软件授权服务确定无法使用指定的密钥管理服务器 (KMS)",
			"0xC004F042": "软件授权服务判定无法使用特定的密钥管理服务 (KMS)",
			"0xC004F050": "软件授权服务报告产品密钥无效",
			"0xC004F051": "软件授权服务报告产品密钥被阻止",
			"0xC004F064": "软件授权服务报告已超过非正版宽限期",
			"0xC004F065": "软件授权服务报告应用程序在有效的非正版期限内运行",
			"0xC004F066": "软件授权服务报告不能在设置从属属性之前设置正版信息属性",
			"0xC004F069": "软件授权服务报告未找到产品 SKU",
			"0x80070005": "访问被拒绝, 请求的操作需要提升特权",
			"0x8007232A": "DNS 服务器出现故障",
			"0x8007232B": "RPC 服务器不可用",
			"0x800706BA": "DNS 名称不存在",
			"0x8007251D": "未找到 DNS 查询记录",
			"0xC004D307": "已超过重新整理的最大允许数量, 必须重新安装系统",
			"0xC004D302": "安全处理器报告受信任的数据存储已重置, 请重启",
			"0xC004F074": "密钥管理服务(KMS)不可用",
			"0xC004F025": "激活需要提升到管理员权限",
			"0xC004F015": "软件授权服务报告许可证未安装",
			"0x80072EFE": "与服务器的连接意外终止",
			"0x80041017": "激活脚本与系统版本不匹配",
			"0x80072EE2": "操作超时, 请检查网络连接",
			"0x80070057": "命令参数错误",

			"0xZ0000001": "设置激活服务器失败, 请稍后重试",
			"0xZ0000002": "激活失败, 请稍后重试",
			"0xZ0000003": "上次激活未完成, 请稍后重试",
			"0xZ0000004": "激活服务器端口错误, 请稍后再试",
			"0xZ0000005": "激活服务器连接错误, 请稍后再试"
		};

		var messages = {
			"-3": "上一次激活未完成, 请在5分钟后重试",
			"-2": "激活量已用尽, 请先申请激活量",
			"-1": "检查当前 <span class='alias'>%1</span> 的激活状态",
			0: "当前 <span class='alias'>%1</span> 未激活, 开始激活",
			1: "当前 <span class='alias'>%1</span> 已激活, 无需再次激活",
			2: "设置激活服务器失败, 请稍后重试",
			3: "设置激活服务器成功, 开始激活 <span class='alias'>%1</span>",
			4: function(code) {
				var msg = errorMessage[code];
				if (!msg) msg = "<span class='alias'>%1</span> 激活失败, 请稍后重试";
				return msg;
			},
			5: "恭喜您, 已成功激活 <span class='alias'>%1</span>",
			6: "激活服务器端口错误, 请稍后重试",
			7: "激活服务器连接错误, 请稍后重试",
			8: "上次激活未完成, 请稍后重试",
			9: "激活请求参数错误, 请稍后重试"
		};
		$(table).on("click", ".activate", function() {
			if ($(this).hasClass("disabled")) return false;

			$(".loading_1", dialogActivate).hide();
			var tr = $(this).closest("tr");
			selectedProductId = tr.attr("pid");
			productName =  tr.find("td.name").text();
			productAlias = tr.attr("alias");
			$(".alias", dialogActivate).text(productAlias);
			$(".steps li", dialogActivate).removeClass("current complete jumpover error");
			$(".information", dialogActivate)
				.removeClass("info_error info_success")
				.hide();
			dialogActivate.dialog("open");
			return false;
		});

		$(".submit", dialogActivate).click(function() {
			success = false;
			finished = false;
			status = -1;
			step = 0;
			errorStep = -1;
			$(window).bind("beforeunload", OnBeforeUnload);
			if ($(this).hasClass("button_1_disabled")) return false;
			$(".close", dialogActivate).addClass("close_disabled");
			$(this).addClass("button_1_disabled");
			$(".loading_1", dialogActivate).show();
			gpax.sessionId = "{{ Cookie::get(Config::get('session.cookie')) }}";
			gpax.productAlias = productAlias;
			gpax.isActivated();
			$(".steps li", dialogActivate).removeClass("current complete jumpover error");
			$(".steps li:eq(0)", dialogActivate).addClass("current");
			$(".information", dialogActivate).removeClass("info_error info_success");
			ShowStep(step, errorStep);
			ShowInfomation(status);
			return false;

		});

		window["onIsActivatedResult"] = function() {
			var btnSubmit = $(".submit", dialogActivate);
			if ($.inArray(gpax.activateStatus, [6, 7, 8]) >= 0) {
				finished = true;
				success = false;
				step = 0;
				errorStep = 0;
				status = gpax.activateStatus;

				$(window).unbind("beforeunload");
				$(".loading_1", dialogActivate).hide();
				btnSubmit.removeClass("button_1_disabled");
				$(".close", dialogActivate).removeClass("close_disabled");
			} else if (gpax.activateStatus == 1) { //已经激活
				finished = true;
				success = true;
				status = 1;
				step = 0;
				errorStep = 0;
				$(window).unbind("beforeunload");
				$(".loading_1", dialogActivate).hide();
				btnSubmit.removeClass("button_1_disabled");
				$(".close", dialogActivate).removeClass("close_disabled");
			} else { //开始激活, 从php获取uuid
				$.post("/activate/begin", {"productid" : selectedProductId, "computerid" : gpax.computerId, "productname" : gpax.productName}, function(ret) {
					var message = typeof(messages[status]) == "function" ? messages[status](code) : messages[status];
					$(".information", dialogActivate).html(message.replace("%1", productAlias)).show();
					dialogActivate.css("z-index", ((Math.random()*1000) >> 0) + 100);

					if (ret.code == 1) { // 开始激活
						uuid = ret.uuid;
						gpax.activateUuid = uuid;
						gpax.activate();
						status = 0;
						//onActivateResult
					} else {
						uuid = null;
						step = 1;
						errorStep = -1;
						finished = true;
						ShowStep(step, errorStep);
						if (ret.code == 0) { // 获取密钥失败
							ShowInfomation(-2);
						} else { //上一次激活操作未重置
							ShowInfomation(-3);
						}
						$(window).unbind("beforeunload");
						$(".loading_1", dialogActivate).hide();
						btnSubmit.removeClass("button_1_disabled");
						$(".close", dialogActivate).removeClass("close_disabled");
					}

				}, "json");
			}

			ShowStep(step, errorStep);
			ShowInfomation(status);
		};

		window["onActivateResult"] = function() {
			var btnSubmit = $(".submit", dialogActivate);
			switch (gpax.activateStatus) {
				case 0: // NotActivate
					step = 1;
					break;
				case 1: // IsActivated
					step = 0;
					errorStep = 0;
					success = true;
					break;
				case 2: // SetKmsServerFailed
					errorStep = 1;
					break;
				case 3: // SetKmsServerSuccessful
					step = 2;
					break;
				case 4: // ActivateFailed
					step = 2;
					errorStep = 2;
					break;
				case 5: // ActivateSuccessful
					success = true;
					step = 3;
					break;
				case 6: //ActivateServerPortError
				case 7: //ActivateServerUnconnected
				case 8: //Processing
					step = 0;
					errorStep = 0;
					break;
			}
			var code = gpax.activateErrorCode;
			finished = gpax.finished;
			status = gpax.activateStatus;
			ShowStep(step, errorStep);
			ShowInfomation(status, code);
			if (finished) {
				$(window).unbind("beforeunload");
				$(".loading_1", dialogActivate).hide();
				btnSubmit.removeClass("button_1_disabled");
				$(".close", dialogActivate).removeClass("close_disabled");
			}
		};


		var ShowStep = function(step, errorStep) {
			var stepCount = $(".steps li").size();
			if (step < 0) {
				$(".steps li", dialogActivate).addClass("jumpover").removeClass("current");
			} else {
				for (var i = 0; i < stepCount; i++) {
					var label = $(".steps li", dialogActivate).eq(i);
					label.removeClass("current complete jumpover error");
					// current step
					if (i == step) label.addClass("current");
					// completed step
					if (i < step) label.removeClass("current").addClass("complete");
					// jump over step
					if (errorStep > -1 && i >= errorStep) label.removeClass("current").addClass("jumpover");

					if (finished) {
						if (errorStep == -1 && i >= step && i != 0) label.addClass("jumpover");
						label.removeClass("current");
					}

					// error step
					if (i == errorStep) label.removeClass("current jumpover").addClass("error");
				}
			}
		};

		var ShowInfomation = function(status, code) {
			var message = typeof(messages[status]) == "function" ? messages[status](code) : messages[status];
			message = message.replace("%1", productAlias);
			$(".information", dialogActivate).html(message).show();
			if (finished) {
				if (!success) $(".information", dialogActivate).addClass("info_error");
				else $(".information", dialogActivate).addClass("info_success");
			}
		};
		var OnBeforeUnload = function() {
			return "正在激活 " + productAlias + ", 离开页面后会导致激活失败, 您确定要离开页面吗?";
		};
	});
</script>
<object id="gpax" classid="clsid:86812adb-12bd-4ca4-8d24-9ab4b85bc4c9" style="display:none"></object>
<div class="frame_1">
	<div class="loading_2">页面正在加载中, 请稍后...</div>
</div>
<div class="frame_1 activex_error">
	<dl>
		<dt>激活控件加载错误, 请检查:</dt>
		<dd>1) 是否没有安装最新版本的GP激活控件&nbsp;&nbsp;[<a href="">下载GP激活控件</a>]</dd>
		<dd>2) 是否使用了非微软IE浏览器打开页面</dd>
		<dd>3) 是否打开了多个"激活管理"页面</dd>
		<dd>4) 如果以上3点不能解决该错误, 请重新刷新页面或重新安装GP激活控件</dd>
	</dl>
</div>
<div class="dialog_1" id="dialogActivate">
	<div class="header"><span>激活 <span class="alias"></span></span><a class="close"></a></div>
	<div class="tip">这里是激活 <span class="alias"></span> 所需要的所有步骤. <br/>将在1~5分钟内完成应用激活, 激活过程中请不要关闭或刷新窗口!</div>
	<div class="information"></div>
	<div class="content">
		<ul class="steps">
			<li>检测 <span class="alias"></span> 是否需要激活</li>
			<li>设置激活服务器</li>
			<li>激活 <span class="alias"></span></li>
		</ul>
	</div>
	<div class="actions">
		<span class="loading_1"></span>
		<a href="#" class="button_1 button_1_b submit"><span>开始激活</span></a>
		<a class="close" href="#">取消</a>
	</div>
</div>

<div class="dialog_1" id="dialogApplykey">
	<div class="header"><span>激活申请</span><a class="close"></a></div>
	<div class="information">你正在向上级部门申请激活次数，申请后请等候审批，审批将在24小时之内完成，如有疑问请联系管理员</div>
	<div class="content">
		<form id="form_applykey">
			<table class="form_1">
				<tr>
					<td class="label">激活商品:</td>
					<td class="product_name text"></td>
				</tr>
				<tr>
					<td class="label">激活模式:</td>
					<td class="product_type text"></td>
				</tr>
				<tr>
					<td class="label">申请量:</td>
					<td><input name="requestcount" id="requestcount" class="textbox_1 textbox_1_a" type="text" /> </td>
					<td class="info"></td>
				</tr>
				<tr>
					<td class="label label_1">申请原因:</td>
					<td><textarea name="reason" id="reason" class="textbox_1 textbox_1_a" style="height:50px"></textarea> </td>
					<td class="info"></td>
				</tr>
			</table>
		</form>
	</div>
	<div class="actions">
		<a href="#" class="button_1 button_1_b submit"><span>申请</span></a>
		<a class="close" href="#">取消</a>
	</div>
</div>

<div class="frame_1">
	<div class="block_1 activate_list">
		<table class="table_1"></table>
		<div class="clear"></div>
	</div>
</div>