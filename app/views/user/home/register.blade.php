<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery.validate.min.js"></script>
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/safeenter.js"></script>
<script type="text/javascript">
$(function() {
	var form = $("#registerForm");

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
			email: {
				required: true,
				email: true
			},
			username: {
				required: true,
				minlength: 6
			},
			name: {
				required: true
			},
			password: {
				required: true,
				minlength: 6
			},
			password_confirmation: {
				required: true,
				equalTo: "#password"
			},
			departmentid: {
				required: true
			},
			captcha: {
				required: true
			}
		},
		messages: {
			password: {
				required: "密码不能为空",
				minlength: "密码长度不能小于6位"
			},
			password_confirmation: {
				required: "确认密码不能为空",
				equalTo: "确认密码不匹配"
			},
			username: {
				required: "用户名不能为空",
				minlength: "用户名不得少于6位"
			},
			name: {
				required: "姓名不能为空"
			},
			departmentid: {
				required: "必须选择一个部门"
			},
			email: {
				required: "邮箱不能为空",
				email: "必须是有效的电子邮箱"
			},
			captcha: {
				required: "验证码不能为空"
			}
		}
	});
	$(".submit", form).click(function() {
		if ($(this).hasClass("button_1_disabled")) return false;
		if (form.valid()) {
			$(this).text("注册中...").addClass("button_1_disabled");
			form.submit();
		}
		else $("input.error:eq(0)").select();
		return false;
	});
});
</script>
<div class="frame_1">
	<div class="block_1 block_1_account">
		<h1>注册GP平台账号</h1>
		<div class="block_1_l">
			{{ Form::open(array('method' => 'POST', 'id' => 'registerForm')) }}
			<table class="form_1">
				<tr>
					<td class="label">用户名：</td>
					<td>
						{{ Form::text('username', $input['username'], array('class' => 'textbox_1 ' . (!$errors->has('username') ? '' : 'error'), 'id' => 'username')) }}
					</td>
					<td>
						<span class="info"><label class="error">{{ $errors->first('username') }}</label></span>
					</td>
				</tr>
				<tr>
					<td class="label">邮箱：</td>
					<td>
						{{ Form::text('email', $input['email'], array('class' => 'textbox_1 ' . (!$errors->has('email') ? '' : 'error'), 'id' => 'email')) }}
					</td>
					<td>
						<span class="info"><label class="error">{{ $errors->first('email') }}</label></span>
					</td>
				</tr>
				<tr>
					<td class="label">姓名：</td>
					<td>
						{{ Form::text('name', $input['name'], array('class' => 'textbox_1 ' . (!$errors->has('name') ? '' : 'error'), 'id' => 'name')) }}
					</td>
					<td>
						<span class="info"><label class="error">{{ $errors->first('name') }}</label></span>
					</td>
				</tr>
				<tr>
					<td class="label">部门：</td>
					<td>
						{{ Form::select('departmentid', $departments , $input['departmentid'], array('class' => 'select_1 ' . (!$errors->has('departmentid') ? '' : 'error'), 'id' => 'departmentid')) }}
					</td>
					<td>
						<span class="info"><label class="error">{{ $errors->first('departmentid') }}</label></span>
					</td>
				</tr>
				<tr>
					<td class="label">登录密码：</td>
					<td>
						{{ Form::password('password', array('class' => 'textbox_1 ' . (!$errors->has('password') ? '' : 'error'), 'id' => 'password')) }}
					</td>
					<td>
						<span class="info"><label class="error">{{ $errors->first('password') }}</label></span>
					</td>
				</tr>
				<tr>
					<td class="label">确认密码：</td>
					<td>
						{{ Form::password('password_confirmation', array('class' => 'textbox_1 ' . (!$errors->has('password_confirmation') ? '' : 'error'), 'id' => 'password')) }}
					</td>
					<td>
						<span class="info"><label class="error">{{ $errors->first('repassword') }}</label></span>
					</td>
				</tr>
				<tr class="row_captcha">
					<td class="label">验证码：</td>
					<td>
						{{ Form::text('captcha', '', array('class' => 'textbox_1 textbox_1_capcha ' . (!$errors->has('captcha') ? '' : 'error'), 'id' => 'captcha', 'maxlength' => '6')) }}
					</td>
					<td>
						<span class="info"><label class="error">{{ $errors->first('captcha') }}</label></span>
					</td>
				</tr>
				<tr>
					<td class="label"></td>
					<td>
						{{ Form::checkbox('terms', 1, true, array('id' => 'agreement')); }}
						<label for="agreement">同意<a href="#">GP平台协议</a></label>
					</td>
					<td>
						<span class="info">{{ $errors->first('terms') }}</span>
					</td>
				</tr>
				<tr class="bottom_but">
					<td class="list_1"></td>
					<td class="list_2">
						<a href="#" class="button_1 submit">注&nbsp;册</a>
					</td>
				</tr>
			</table>
		</div>
		<div class="block_1_r">
			<h2>我已注册GP平台账号</h2>
			<p>我已经注册GP平台账号，登录后进入GP平台！</p>
			<a href="{{ URL::to('/login') }}" class="button_1 button_1_a">立即登录</a>
		</div>
		<div class="clear"></div>
	</div>
</div>