<link href="{{ Config::get('app.asset_url') . 'scripts/Validation-Engine/validationEngine.jquery.css?' . Ca\Consts::$ca_version }}" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/Validation-Engine/jquery.validationEngine-zh_CN.js"></script>
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/Validation-Engine/jquery.validationEngine.js"></script>

<script type="text/javascript">
$(function() {
	var form = $("#newTopicForm");
//	var documentError= $("#documentError");
	//	documentError.hide();
	$("#name").select();

	$.extend($.validationEngineLanguage.allRules, {
		"checkdocumenturl": {
			"method": "post",
			"url": "/usercenter/topic/ajaxcheckdocumenturl",
			"alertTextLoad": "文档地址验证中..."
		},
		"noRepeat":  {
			"func": function(field, rules, i, options) {
				var documents = $("input[name^='documenturl']").filter(function(index) {
					return $(this).val() != "";
				}).not(field);
				var repeat = false;
				$.each(documents, function(index, element) {
					if (field.val() == $(element).val()) {
						repeat = true;
						return false;
					}
				});
				return repeat ? false : true;
			},
			"alertText": "文档不能重复"
		}
	});

	form.validationEngine({
		"ajaxFormValidation": true,
		"ajaxFormValidationURL": "/validationEngine",
		"ajaxFormValidationMethod": "post",
		"scroll": false,
		"autoHidePrompt": true,
		"autoHideDelay": 5000,
		"custom_error_messages": {
			"#name" : {
				"required": {
					"message": "标题不能为空"
				}
			},
			"#intro": {
				"required": {
					"message": "简介不能为空"
				}
			},
			".document_url": {
				"required": {
					"message":"链接不能为空"
				}
			}
		},
		"onAjaxFormComplete": function (status,form,json,options) {
			form.validationEngine('detach');
			form.submit();
		},
		"onBeforeAjaxFormValidation": function (form,options) {
			$(".btn_submit", form).addClass("button_1_disabled").val("处理中");
		}
	});

	$(".documents").delegate(".document_url", "focusout", function() {
		addMoreDocumentUrl($(this));
	});


	//自动新增文档链接文本框
	var addMoreDocumentUrl = function(element) {
		if (element.val() == "") {
			return false;
		}
		var index = $("li", element.closest("ul")).index(element.closest("li"));
		var countDocumentUrl = $("li", element.closest("ul")).length;
		if (index == countDocumentUrl - 1) {
			var input = $("<input/>")
				.attr({ "type": "text", "name": "documenturl[]"})
				.addClass("textbox_1 document_url validate[custom[noRepeat],ajax[checkdocumenturl]]");
			var li = $("<li/>")
				.append(input)
				.append("<span class='info'></span>");
			$(".documents").append(li);
		}
	};

});

</script>


<div class="new_topic">
	<div class="information_1" style="display:none"><div class="success"></div><span class="close"></span></div>
	<div class="publish_document">
		<ul class="tabsheet_2">
			<li class="selected"><a>我的专题 &gt; 新建</a></li>
		</ul>
		<div class="spacer_1"></div>
		<form class="form_2" action="/usercenter/topic/new" id="newTopicForm" method="post">
		<input TYPE="hidden" NAME="_token" VALUE="{{ csrf_token() }}">
		<table>
			<tr>
				<td>
					<label class="label">标题: </label>
					<input placeholder="输入专题名称" id="name" name="name" type="text" class="textbox_1 validate[required]"/>
				</td>
			</tr>
			<tr>
				<td>
					<label class="label">介绍:</label>
					<textarea placeholder="专题的简要介绍" id="intro" name="intro" class="textbox_1 textbox_1_a validate[required]"></textarea>
				</td>
			</tr>
			<tr>
				<td>
					<div class="spacer_1"></div>
					<label id="documentTip" class="label">添加文档:</label>
					<label class="tip">
						专题中的文档数量最少3个，您可以输入文库上的文档地址添加文档,
						如: {{ 'http://share.' . app()->environment() . '/document/detail?id=****' }}
					</label>
					<ul class="documents">
						<li>
							<input class="textbox_1 document_url validate[required,custom[noRepeat],ajax[checkdocumenturl]]" type="text" name="documenturl[]" />
						</li>
						<li>
							<input class="textbox_1 document_url validate[required,custom[noRepeat],ajax[checkdocumenturl]]" type="text" name="documenturl[]" />
						</li>
						<li>
							<input class="textbox_1 document_url validate[required,custom[noRepeat],ajax[checkdocumenturl]]" type="text" name="documenturl[]" />
						</li>
					</ul>
				</td>
			</tr>
		</table>
		<div class="actions">
			<input type="submit" class="button_1 btn_submit" value="提交"/>
			<a href="/usercenter/topic" class="cancel"><span>取消</span></a>
		</div>
	</form>
	</div>
</div>