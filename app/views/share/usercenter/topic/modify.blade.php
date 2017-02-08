<link href="{{ Config::get('app.asset_url') . 'scripts/Validation-Engine/validationEngine.jquery.css?' . Ca\Consts::$ca_version }}" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/Validation-Engine/jquery.validationEngine-zh_CN.js"></script>
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/Validation-Engine/jquery.validationEngine.js"></script>
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/share.usercenter.js"></script>

<script type="text/javascript">
$(function() {

	var checkDocumentCount = function(selectedEntityIds) {
		var documentCount = $(".count_document").text();
		if (documentCount - selectedEntityIds.length < 3) {
			showInformation("文档数量不能少于3个.");
			return false;
		} else {
			return true;
		}
	};

	$.shareUserCenter({
		deleteUrl: "/usercenter/topic/deletedocument",
		extraData: { "topicid": "{{ $topic->topicid }}" },
		deleteCheck: checkDocumentCount,
		emptyRow: '<tr class="none"><td colspan="4"><span>你还没有添加文档</span></td></tr>'
	});


	var form = $("form#modifyTopicForm");
	var form2 = $("form#addDocument");
	var table = $("table.table_1");
	var actions = $(".topic_document .main_actions");
	var dialogAddDocument = $("div#dialogAddDocument");
	var dialogParams = { autoOpen: false, modal: true, resizable: false, width: 485, height: "auto", minHeight: 0 };
	dialogAddDocument.dialog(dialogParams);


	$.extend($.validationEngineLanguage.allRules, {
		"checkdocumenturl": {
			"method": "post",
			"url": "/usercenter/topic/ajaxcheckdocumenturl",
			"extraData": "topicid={{ $topic->topicid }}",
			"alertTextLoad": "文档地址验证中..."
		}
	});

	form.validationEngine({
		"autoHidePrompt": true,
		"autoHideDelay": 5000,
		"custom_error_messages": {
			"#name": {
				"required":   { "message": "标题不能为空" }
			},
			"#intro": {
				"required":   { "message": "简介不能为空" }
			}
		}
	});

	$(".button_1", form).click(function() {
		if ($(this).hasClass("button_1_disabled")) return false;
		if (form.validationEngine("validate")) {
			$("span", $(this)).text("保存中");
			$(this).addClass("button_1_disabled");
			form.validationEngine('detach');
			form.submit();
		}
		return false;
	});


	form2.validationEngine({
		"scroll": false,
		"ajaxFormValidation": true,
		"ajaxFormValidationMethod": "post",
		"autoHidePrompt": true,
		"autoHideDelay": 5000,
		"custom_error_messages": {
			".document_url": {
				"required":   { "message": "链接地址不能为空" }
			}
		},

		"onAjaxFormComplete": function (status,form,json,options) {
//			console.log(json);
			if (json.status == 1) {
				var documents = json.documents;
				$.each(documents, function(i, document) {
					var row = $('<tr type="1" eid="' + document.documentid + '"> \
								<td class="check"><input class="check" type="checkbox" value="' + document.documentid + '" /></td>\
								<td style="text-align:left"> \
									 <a class="title file" target="_blank" href="/document/detail?id=' + document.documentid + '">' + document.name + '</a> \
								</td> \
								<td>' + document.uname + '</td> \
								<td style="text-align:right">' + document.date + '</td> \
							</tr>');
					$("tbody", table).append(row);
				});
				var countDocument = parseInt($(".count_document").text());
				$(".count_document").text(countDocument + documents.length);
				$("#btn_add_document", actions).removeClass("button_3_disabled");
				dialogAddDocument.dialog("close");
				showInformation("添加文档成功.");
			}
		},
		"onBeforeAjaxFormValidation": function (form,options) {
			$(".submit", dialogAddDocument).addClass("button_1_disabled");
			$(".submit span", dialogAddDocument).text("保存中...");
		}
	});

	$("#btn_add_document", actions).click(function() {
		$("form", dialogAddDocument)[0].reset();
		$(".submit", dialogAddDocument).removeClass("button_1_disabled").find("span").text("确定");
		dialogAddDocument.dialog("open");
		return false;
	});

	$(".submit", dialogAddDocument).click(function() {
		form2.submit();
		return false;
	});

	$(".publish_document .textbox_1:first").focus().select();
});

</script>
<div class="dialog_1" id="dialogDelete">
	<div class="header"><span>移除文档</span><a class="close"></a></div>
	<div class="confirm">是否移除选择的文档?</div>
	<div class="actions">
		<a href="#" class="button_1 submit"><span>确定</span></a>
		<a class="close" href="#">取消</a>
	</div>
</div>
<div class="dialog_1" id="dialogAddDocument">
	<div class="header"><span>添加文档</span><a class="close"></a></div>
	<div class="info">
		<p>输入文档链接地址添加文档</p>
	</div>
	<div class="content">
		<form id="addDocument" action="/usercenter/topic/adddocument" method="post">
			<input type="hidden" name="topicid" value="{{ $topic->topicid }}" />
			<table class="form_1">
				<tr>
					<td class="label">链接:</td>
					<td><input style="width:400px" placeholder="如: {{ 'http://share.' . app()->environment() . '/document/detail?id=****' }}" class="textbox_1 document_url validate[required,ajax[checkdocumenturl]]" type="text" name="documenturl[0]" /></td>
				</tr>
			</table>
		</form>
	</div>
	<div class="actions">
		<a href="#" class="button_1 submit"><span>添加</span></a>
		<a class="close" href="#">取消</a>
	</div>
	<div class="clear"></div>
</div>

<div class="modify_topic">
	<ul class="tabsheet_2">
		<li class="selected"><a>我的专题 &gt; 修改</a></li>
	</ul>
	<div class="spacer_1"></div>
	<form class="form_2" action="/usercenter/topic/modify?id={{ $topic->topicid }}" id='modifyTopicForm' method="post">
		<input TYPE="hidden" NAME="_token" VALUE="{{ csrf_token() }}">
		<table>
			<tr>
				<td>
					<label class="label">标题: </label>
					<input class="textbox_1 validate[required]" placeholder="输入专题名称" id="name" name="name" type="text" value="{{ $topic->name }}" />
				</td>
			</tr>
			<tr>
				<td>
					<label class="label">介绍:</label>
					<textarea class="textbox_1 textbox_1_a validate[required]" placeholder="专题的简要介绍" name="intro" name="intro">{{ $topic->intro }}</textarea>
				</td>
			</tr>
		</table>
		<div class="actions">
			<a href="#" class="button_1"><span>更新专题</span></a>&nbsp;&nbsp;
			<a href="/usercenter/topic">取消</a>
		</div>
	</form>
	<div class="spacer_1"></div>
	<div class="topic_document">
		<h2 class="header_5">
			共<strong class="count_document">{{ $documents->getTotal() }}</strong>篇关联文档
			<span id="documentError" style="color:red; font-size:12px"></span>
		</h2>
		<div class="information_1" style="display:none"><div class="success"></div><span class="close"></span></div>
		<div class="main_actions">
			<a id="btn_delete_topic" class="button_3 btn_3_del_file button_3_disabled" href="#"><span>删除</span></a>
			<a id="btn_add_document" class="button_3 btn_3_new_folder" href="#"><span>添加文档</span></a>
			<div class="clear"></div>
		</div>
		<table class="table_1">
			<tr>
				<th style="text-align:left" colspan="2">文档名称</th>
				<th style="width:70px">上传用户</th>
				<th style="width:80px; text-align:right">创建日期</th>
			</tr>
			@foreach ($documents as $document)
			<tr type="1" eid="{{ $document->documentid }}" >
				<td class="check">
					<input class="check" type="checkbox" value="{{ $document->documentid }}" />
				</td>
				<td style="text-align:left">
					<a class="title file" href="/document/detail?id={{ $document->documentid }}" target="_blank">{{ $document->name }}</a>
				</td>
				<td>{{ $document->user_name }}</td>
				<td style="text-align:right">{{ Ca\Common::datetime_to_date($document->createdate, 'Y-m-d') }}</td>
			</tr>
			@endforeach
		</table>
		@if ($documents->getLastPage() > 1)
		{{ $documents->appends(array('id' => $topic->topicid))->links() }}
		@endif
	</div>
</div>


