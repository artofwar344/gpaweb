<link href="{{ Config::get('app.asset_url') . 'css/ubbeditor.css' }}" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/wysibb/jquery.wysibb.min.js"></script>
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery.tagsinput.js"></script>
<link href="{{ Config::get('app.asset_url') . 'scripts/Validation-Engine/validationEngine.jquery.css?' . Ca\Consts::$ca_version }}" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/Validation-Engine/jquery.validationEngine.js"></script>
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/Validation-Engine/jquery.validationEngine-zh_CN.js"></script>

<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/categorySelector.js"></script>

<script type="text/javascript">
$(function() {
	var form = $(".form_2");
	$("#category_id").val(0);
	$("input.tags").tagsInput({
		"defaultText": "",
		"width": "740px",
		"height": "25px"
	});

	var settings = { categories: jQuery.parseJSON('{{ json_encode($categories) }}'), styleType: 2 };
	$(".select_category").categorySelector(settings);

	//数据验证
	$("#content").wysibb({
		buttons: "bold,italic,underline,strike,img,link,removeFormat"
	});
	$(".wysibb-text").focusout(function() {
		$("#content").sync();
	});

	$(".wysibb-text-editor").on("focus", function() {
		$(this).closest(".wysibb").addClass("wysibb_hot");
	}).on("blur", function() {
		$(this).closest(".wysibb").removeClass("wysibb_hot");
	});

	$.extend($.validationEngineLanguage.allRules, {
		"sensitiveCheck": {
			"method": "post",
			"url": "/knows/sensitivecheck",
			"alertText": "对不起！您的提问中包含不适合发表的内容"
		},
		"meaningless": {
			"func": function(field, rules, i, options) {
				var result = /^[\dA-Za-z]+$/.test($.trim(field.val()));
				return !result;
			},
			"alertText": "您的提问包含大量无意义字符，请重新提问"
		},
		"categoryCheck": {
			"func": function(field, rules, i, options) {
				var result = $("#category_id").val() != 0;
				return result;
			},
			"alertText": "请选择分类"
		}

	});
	var similarQuestion_div = $(".similarquestion");
	var similarViewed = false; //判断是否查找过相似问题

	form.validationEngine({
		"ajaxFormValidation": true,
		"ajaxFormValidationURL": "/validationEngine",
		"ajaxFormValidationMethod": "post",
		"validateNonVisibleFields": true, //验证不可见的元素
		"scroll": false,
		"autoHidePrompt": true,
		"autoHideDelay": 5000,
		"custom_error_messages": {
			"#title": {
				"required":   { "message": "标题不能为空" },
				"minSize":    { "message": "您的提问过短，请将问题说明清楚，网友才能为您解答" }
			}
		},
		"onAjaxFormComplete": function (status,form,json,options) {
			if (similarViewed) {
				form.validationEngine("detach");
				form.submit();
				return false;
			}
			$.post("{{ '/knows/similarquestion' }}", { "title": $("input#title").val() }, function(ret) {
				if ($.trim(ret) == "empty") {
					form.validationEngine("detach");
					form.submit();
				} else {
					similarViewed = true;
					similarQuestion_div.html(ret).show();
					$(".btn_newquestion span", form).text("继续提交");
					$(".btn_newquestion", form).removeClass("button_1_disabled");
					$(".actions .btn_cancel", form).show();
				}
			}, "html");
			return false;
		},
		"onBeforeAjaxFormValidation": function (form, options) {
			$(".btn_newquestion", form).addClass("button_1_disabled");
			$(".btn_newquestion span", form).text("处理中");
		}
	});

	//end数据验证

	$(".actions .btn_newquestion", form).on("click", function() {
		if ($(this).hasClass("button_1_disabled")) return false;
		$("#content").sync();
		form.submit();
		return false;
	});
	$(".actions .btn_cancel", form).on("click", function() {
		document.location.href = "/knows";
		return false;
	});


	$(".new_question #title").focus();

});
</script>
<div class="spacer_1"></div>
<div class="frame_1">
	<div class="frame_1_l">
		<div class="new_question">
			<h1 class="header_5"><span class="icon icon_question"></span>一句话描述您的疑问</h1>
			<form class="form_2" action="/knows/new" id='form1' method="post">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<table>
					<tr>
						<td><input class="textbox_1 validate[required,minSize[8],custom[meaningless],ajax[sensitiveCheck]]" 
						placeholder="请尽量一句话描述清楚您的问题" id="title" name="title" type="text" /></td>
					</tr>
					<tr>
						<td>
							<label class="label">问题补充</label>
							<textarea class="textbox_1 textbox_1_a validate[ajax[sensitiveCheck]]" 
							placeholder="您可以在这里补充问题细节" id="content" name="content"></textarea>
						</td>
					</tr>
					<tr>
						<td>
							<label class="label">分类</label>
							<div class="select_category">
								<input type="text" class="validate[custom[categoryCheck]]" value="0" name="category_id" id="category_id" />
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<label class="label">标签</label>
							<input class="tags" 
							placeholder="请用英文逗号分隔标签, 标签长度不能超过16个字符" id="tag" name="tag" type="text" class="textbox_1" />
						</td>
					</tr>
				</table>
				<div class="clear"></div>
				<div class="similarquestion" style="display:none">
				</div>
				<div class="clear"></div>
				<div class="actions">
					<a href="#" class="button_1 btn_newquestion"><span>提交问题</span></a>
					<a style="display:none" href="#" class="button_1 btn_cancel"><span>已解决了</span></a>
				</div>
			</form>
			<div class="clear"></div>
		</div>
	</div>
	<div class="frame_1_r"></div>
	<div class="clear"></div>
</div>