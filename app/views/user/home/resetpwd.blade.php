<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery.validate.min.js"></script>
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/safeenter.js"></script>
<script type="text/javascript">
	$(function() {
		var form = $("#frmResetPwd");

		var errorField = $("input[type='text'].error", form);
		if (errorField.size() > 0) errorField.eq(0).focus().select();
		else $("input[type!='hidden']:eq(0)", form).select();
		$("input[type='text'], input[type='password']", form).listenForEnter().bind("pressedEnter", function() {
			$(".submit", form).click();
		});

		form.validate({
			errorPlacement: function(error, element) {
				error.appendTo(element.closest("td").next().find(".info").text(""));
			},
			rules: {
				password: {
					required: true,
					minlength: 6
				},
				password_confirmation: {
					required: true,
					minlength: 6,
					equalTo: "#password"
				},
				captcha: {
					required: true
				}
			},
			messages: {
				password: {
					required: "登录密码不能为空",
					minlength: "密码长度不能小于6位"
				},
				password_confirmation: {
					required: "确认密码不能为空",
					minlength: "确认密码长度不能小于6位",
					equalTo: "确认密码不匹配"
				},
				captcha: {
					required: "验证码不能为空"
				}
			}
		});
		$(".submit", form).click(function() {
			if ($(this).hasClass("button_1_disabled")) return false;
			if (form.valid()) {
				$(this).text("提交中...").addClass("button_1_disabled");
				form[0].submit();
			}
			else $("input.error:eq(0)").select();
			return false;
		});
	});
</script>
<div class="frame_1">
	<div class="block_1 block_1_account">
		<h1>重置密码</h1>
		<div class="block_1_l">
			@if (Request::getMethod() == 'POST' && empty($errors->messages))
			<ul class="information_1 info_success"><li>密码重置成功，您现在可以使用新的密码登录</li></ul>
			@else

			{{ Form::open(array('url' => '/resetpwd?token=' . Request::query('token'), 'method' => 'POST', 'id' => 'frmResetPwd')) }}
			<table class="form_1">
				<tr>
					<td class="label">注册邮箱:</td>
					<td class="text">{{ $user->email }}</td>
					<td><span class="info"></span></td>
				</tr>
				<tr>
					<td class="label">登录密码:</td>
					<td>
						{{ Form::password('password', array('class' => 'textbox_1 ' . (!$errors->has("password") ? '' : 'error'), 'id' => 'password')) }}
					</td>
					<td>
						<span class="info"><label class="error">{{ $errors->first("password") }}</label></span>
					</td>
				</tr>
				<tr>
					<td class="label">确认密码:</td>
					<td>
						{{ Form::password('password_confirmation', array('class' => 'textbox_1 ' . (!$errors->has("password_confirmation") ? '' : 'error'), 'id' => 'password_confirmation')) }}
					</td>
					<td>
						<span class="info"><label class="error">{{ $errors->first("repassword") }}</label></span>
					</td>
				</tr>
				<tr class="row_captcha">
					<td class="label">验证码:</td>
					<td>
						{{ Form::text('captcha', '', array('class' => 'textbox_1 textbox_1_capcha ' . (!$errors->has("captcha") ? '' : 'error'), 'id' => 'captcha')) }}
					</td>
					<td><span class="info"><label class="error">{{ $errors->first("captcha") }}</label></span></td>
				</tr>
				<tr>
					<td></td>
					<td>
						<a href="#" class="button_1 btn_submit submit">提&nbsp;交</a>
					</td>
				</tr>
			</table>
			{{ Form::close() }}
			@endif
		</div>
		<div class="block_1_r">
			<h2>还未注册GP平台账号?</h2>
			<p>立即注册GP账号，开始使用正版软件，还有更多精彩内容！</p>
			<a href="{{ URL::to('/register') }}" class="button_1 button_1_a">注册账号</a>
		</div>
		<div class="clear"></div>
	</div>
</div>