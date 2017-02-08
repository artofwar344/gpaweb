<?php
$currentUser = Auth::user();?>
<div class="main_header">
	<a href="/home/welcome" class="logo"></a>
	<div class="version">后台管理 ver: <?php echo Ca\Consts::$ca_version ?></div>
	<div class="user_info">
		<span><?php echo $currentUser->name ?></span>
		<a href="#" class="updatepwd">修改密码</a>
		<a href="/home/logout">退出</a>
	</div>
</div>

<div id="dlg_pwd" class="dialog_1">
	<h1>修改密码</h1>
	<form>
		<table>
			<tr>
				<td>
					<label for="pwd_oldpassword">当前密码：</label>
				</td>
				<td><input type="password" class="textbox_1" name="oldpassword" id="pwd_oldpassword" /></td>
				<td></td>
			</tr>
			<tr>
				<td>
					<label for="pwd_password">新密码：</label>
				</td>
				<td><input type="password" class="textbox_1" name="password" id="pwd_password" /></td>
				<td></td>
			</tr>
			<tr>
				<td>
					<label for="pwd_password">重复密码：</label>
				</td>
				<td><input type="password" class="textbox_1" name="repassword" id="pwd_repassword" /></td>
				<td></td>
			</tr>
		</table>
	</form>
	<div class="actions">
		<a href="#" class="button_1 button_1_a submit">保存</a>
		<a href="#" class="button_1 button_1_a close">取消</a>
	</div>
	<a href="#" class="close header_close"></a>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		var dlgPwd = $("#dlg_pwd");
		dlgPwd.dialog({ autoOpen: false, modal: true }).find(".close").click(function() {
			$(this).closest(".dialog_1").dialog("close");
			return false;
		});
		var dlgValidate = $("form", dlgPwd).validate({
			errorClass: "error_1",
			errorPlacement: function(error, element) {
				error.appendTo(element.parent("td").next("td"));
			},
			rules: {
				oldpassword: {
					required: true
				},
				password: {
					minlength: 6,
					required: true
				},
				repassword: {
					equalTo: "#pwd_password"
				}
			},
			messages: {
				oldpassword: {
					required: "当前密码不能为空"
				},
				password: {
					required: "新密码不能为空",
					minlength: "密码长度不能小于6位"
				},
				repassword: {
					equalTo: "确认密钥和登录密码不匹配"
				}
			}
		});

		$(".updatepwd").click(function() {
			dlgPwd.dialog("open");
			$("form", dlgPwd)[0].reset();
			dlgValidate.resetForm();
			return false;
		});

		$(".submit", dlgPwd).click(function() {
			if ($(this).hasClass("gray")) return false;
			var form = $("form", dlgPwd);
			if (form.valid()) {
				$(this).addClass("gray");
				$.post("/home/updatepassword", $("form", dlgPwd).serialize(), function() {
					dlgPwd.dialog("close");
					$($(".submit", dlgPwd)).removeClass("gray");
				});
			}
			else $("input:eq(0)", dlgPwd).select();
			return false;
		});

		$(".close", dlgPwd).click(function() {
			dlgPwd.dialog("close");
			return false;
		});
	});
</script>