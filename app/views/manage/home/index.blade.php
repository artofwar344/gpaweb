<!DOCTYPE html public "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" class="sign_in_html">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>正版软件管理与服务平台</title>
	<link rel='shortcut icon' href="{{ Config::get('app.asset_url') }}images/CA.ico" type='image/x-icon' />
	<link rel="stylesheet" href="{{ Config::get('app.asset_url') }}css/backend.css?{{ Ca\Consts::$ca_version }}" type="text/css" />
	<link rel="stylesheet" href="{{ Config::get('app.asset_url') }}css/customer.css?{{ Ca\Consts::$ca_version }}" type="text/css" />
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery.js"></script>
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery.validate.min.js"></script>
	<!--[if lt IE 9]>
	<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/pie/PIE_IE678.js"></script>
	<![endif]-->
	<script type="text/javascript">
		$(function() {
			var inputs = $("form input");
			inputs.keypress(function() {
				$("span.error_2").text("");
			});
			var _validateRule = {
				name: {
					required: true
				},
				password: {
					required: true
				},
				captcha: {
					required: true
				}
			};

			var _validateMessages = {
				name: {
					required: "请填写管理员"
				},
				password: {
					required: "请填写密码"
				},
				captcha: {
					required: "请填写验证码"
				}
			};

			$("form").validate({
				errorClass: "error_1",
				errorPlacement: function(error, element) {
					error.appendTo(element.parent("td").next("td"));
				},
				rules: _validateRule, messages: _validateMessages
			});

			$(".submit").click(function() {
				var form = $("form");
				var button = $(this);
				if (form.valid()) {
					if (button.hasClass("gray")) return false;
					inputs.prop("readonly", true).addClass("disabled");
					button.addClass("gray");
					$.post("/home/login", form.serialize(), function(ret) {
						var error = "";
						switch(ret) {
							case "-3":
								error = "该帐号不能登录";
								break;
							case "-2":
								error = "账号或密码错误";
								break;
							case "-1":
								error = "验证码错误";
								break;
							case "1":
								window.location.href = "/home/welcome";
								break;
						}
						if (error != "") {
							button.removeClass("gray");
							inputs.prop("readonly", false).removeClass("disabled");
							$("span.error_2").text(error);
							$(".captcha img").attr("src", "/captcha?" + Math.random())
						}
						form[0].reset();
						$("input:first", form).select();
					});
				} else $("input.error_1:eq(0)", form).select();
				return false;
			});

			inputs.keypress(function(e) {
				if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
					$(".submit").click();
					return false;
				}
				else return true;
			});

			$(".sign_in_wrap").height($("body").height() * .7);

			if (window.PIE) {
				$(".sign_in").each(function() {
					PIE.attach(this);
				});
			}

			$(".sign_in").fadeIn();
			inputs.eq(0).select();
		});
	</script>
</head>
<body>
<div class="sign_in_wrap">
	<div class="sign_in">
		<div class="logo"></div>
		<h1>高级管理员登录</h1>
		<span class="error_2"></span>
		<form>
			<table>
				<tr>
					<td><label for="">管理员：</label></td>
					<td><input type="text" class="textbox_1" id="name" name="name"/></td>
					<td class="info"></td>
				</tr>
				<tr>
					<td><label for="">密 码：</label></td>
					<td><input type="password" class="textbox_1" id="password" name="password"/></td>
					<td class="info"></td>
				</tr>
				<tr>
					<td><label for="">验证码：</label></td>
					<td class="captcha">
						<div>
							<input type="text" class="textbox_1" id="captcha" name="captcha" maxlength="4"/>
							<img src="{{ \Ca\Captcha::img() }}" />
						</div>
					</td>
					<td class="info"></td>
				</tr>
				<tr>
					<td></td>
					<td><a href="#" class="button_1 button_1_a submit">登录</a></td>
				</tr>
			</table>
		</form>
	</div>
</div>



</body>
</html>