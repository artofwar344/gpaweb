<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery.tagsinput.js"></script>
<link href="{{ Config::get('app.asset_url') . 'scripts/Validation-Engine/validationEngine.jquery.css?' . Ca\Consts::$ca_version }}" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/Validation-Engine/jquery.validationEngine-zh_CN.js"></script>
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/Validation-Engine/jquery.validationEngine.js"></script>
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/categorySelector.js"></script>
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/uploadify/jquery.uploadify.min.js"></script>

<script type="text/javascript">
	$(function() {
		var selectedCategoryid = '{{ $categoryid ? $categoryid : 0 }}';
		$("#category_id").val(selectedCategoryid);
		var form = $(".form_2");
		$("input.tags").tagsInput({
			"defaultText": "",
			"width": "810px",
			"height": "25px"
		});
		var settings = { categories: jQuery.parseJSON('{{ json_encode($categories) }}'), currentCategory: selectedCategoryid, styleType: 2 };
		var categorySelector = $(".select_category").categorySelector(settings);

		$("#name").select();


		$.extend($.validationEngineLanguage.allRules, {
			"categoryCheck": {
				"func": function(field, rules, i, options) {
					console.log($("#category_id").val());
					return $("#category_id").val() != 0;
				},
				"alertText": "请选择分类"
			}

		});

		form.validationEngine({
			"validateNonVisibleFields": true, //验证不可见的元素
			"updatePromptsPosition": true,
			"scroll": false,
			"autoHidePrompt": true,
			"autoHideDelay": 5000,
			"custom_error_messages": {
				"#name": {
					"required":   { "message": "标题不能为空" }
				},
				"#intro": {
					"required":   { "message": "简介不能为空" }
				},
				"#tag": {
					"required":   { "message": "标签不能为空" }
				}
			}
		});

		//提交
		$(".btn_submit", form).click(function() {
			if ($(this).hasClass("button_1_disabled")) return false;
			if (form.validationEngine("validate")) {
				$("input#category_id").val(categorySelector.selectedCategory);
				$("span", $(this)).text("处理中");
				$(this).addClass("button_1_disabled");
				form.validationEngine('detach');
				form.submit();
			}
			return false;
		});


		var deleteAction = function(self, documentid) {
			$.post("/usercenter/document/deleteattachment", { "documentid": documentid }, function() {
				self.closest("li").remove();
			});
			return false;
		};

		$("#uploadifyQueue .btn_delete").click(function() {
			var documentid = $(this).closest("li").attr("did");
			deleteAction($(this), documentid);
			return false;
		});

		//uploads
		var itemTemplate =
			'<li id="${fileID}" class="uploadify-queue-item">\
				<span class="fileName">${fileName}</span>\
				<span class="fileSize">(${fileSize})</span>\
				<span class="info"></span>\
				<span class="actions">\
					<a class="cancle" href="javascript:$(\'#${instanceID}\').uploadify(\'cancel\', \'${fileID}\')">取消</a>\
				</span>\
			</li>';

		$('#file_upload').uploadify({
			"formData": {
				"documentid": "{{ $document->documentid }}"
			},
			"removeCompleted": false,
			"fileTypeExts": "@foreach (Config::get('share.allow_extension') as $ext) *.{{$ext}}; @endforeach",
			"buttonText": "上传附件",
			"fileSizeLimit": "{{ \Ca\Service\ParamsService::get('maxuploadlimit') }}MB",
			"width": "100",
			"height": "30",
			"swf": "{{ Config::get('app.asset_url') . 'scripts/uploadify/uploadify.swf' }}",
			"uploader": "{{ URL::to('/usercenter/document/attachment') }}",
			"queueID": "uploadifyQueue",  //文件队列显示位置的元素id
			"itemTemplate": itemTemplate, //文件队列显示模板
			"onUploadProgress" : function(file, bytesUploaded, bytesTotal, totalBytesUploaded, totalBytesTotal) {
				var info_container = $("#" + file.id).find('.info');
				var percentage = parseFloat(100 * bytesUploaded / bytesTotal).toFixed(2);
				info_container.html(percentage > 100 ? 100: percentage + "%");
			},
			"onUploadError": function(file, errorCode, errorMsg, errorString) {
				console.log(errorMsg, errorString);
			},
			"onUploadSuccess": function(file, data, response) {
//				console.log(data);
//				console.log(file);
				var ret = jQuery.parseJSON(data);
				switch (ret.status) {
					case 1:
						var row = $("#" + file.id);
						row.find(".cancle").remove();
						row.find(".info").remove();
						var deleteBtn = $("<a/>").attr({ "href": "#" }).text("删除")
							.on("click", function() {
								deleteAction($(this), ret.documentid);
								return false;
							});
						$(".actions" ,row).append(deleteBtn);
						break;
					case 2:
//						console.log(ret.messages);
						alert(ret.messages);
						$('#file_upload').uploadify('cancel', file.id);
						break;
				}
			}

		});

	});
	function fileSizeFormat (size) {
		var newsize,
			sizeweight;
		if (size < 1024) {
			newsize = size;
			sizeweight = "字节";
		} else if (size < 1024 * 1024) {
			newsize = parseFloat(size / 1024).toFixed(0);
			sizeweight = "KB";
		} else if (size < 1024 * 1024 * 1024) {
			newsize = parseFloat(size / (1024 * 1024)).toFixed(2);
			sizeweight = "MB";
		} else {
			newsize = parseFloat(size / (1024 * 1024 * 1024)).toFixed(2);
			sizeweight = "GB";
		}
		return newsize + " " + sizeweight;
	}
</script>

<div class="publish_document">
	<ul class="tabsheet_2">
		<li class="selected"><a>我的文档 &gt; 发布</a></li>
	</ul>
	<div class="information_1" style="display:none"><div class="success"></div><span class="close"></span></div>
	<form class="form_2" action="/usercenter/document/publish" id='publishForm' method="post">
		<input type="hidden" name="id" value="{{ $document_id }}" />
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<table>
			<tr>
				<td>
					<label class="label">标题</label>
					<input class="textbox_1 validate[required]" placeholder="输入文档标题" id="name" name="name" type="text" value="{{ $document->name }}" />
				</td>
			</tr>
			<tr>
				<td>
					<label class="label">简介</label>
					<textarea class="textbox_1 textbox_1_a validate[required]" placeholder="您可以在这里补充文档说明" id="intro" name="intro">{{ $document->intro }}</textarea>
				</td>
			</tr>
			<tr>
				<td>
					<label class="label">分类</label>
					<div class="select_category">
						<input type="text" class="validate[custom[categoryCheck]]" name="category_id" id="category_id" />
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<label class="label">标签</label>
					<input class="textbox_1 tags" placeholder="请用逗号分隔标签, 标签长度不能超过16个字符" id="tag" name="tag" type="text" value="{{ implode(',', $tags) }}" />
				</td>
			</tr>
			<tr>
				<td class="type">
					<label class="label">文档类型</label>
					<ul>
						<li><label><input type="radio" name="publish" value="{{ \Ca\DocumentPublish::public_d }}" @if($document->publish == \Ca\DocumentPublish::public_d || $document->publish == \Ca\DocumentPublish::submit_d) checked="checked" @endif><span> 公开文档(任何人可以检索和阅读)</span></label></li>
						<li><label><input type="radio" name="publish" value="{{ \Ca\DocumentPublish::private_d }}" @if($document->publish == \Ca\DocumentPublish::private_d) checked="checked" @endif><span> 私有文档(仅自己可见)</span></label></li>
					</ul>
				</td>
				<td></td>
			</tr>
			<tr>
				<td>
					<label class="label">附件列表</label>
					<ul class="uploadify-queue" id="uploadifyQueue">
						<li class="uploadify-queue-item"><a id="file_upload" class="uploads" href="#">上传附件</a></li>
						@foreach($attachments as $attachment)
						<li did="{{ $attachment->documentid }}" class="uploadify-queue-item">
							<span class="fileName">{{ $attachment->name . '.' . $attachment->extension }}</span>
							<span class="fileSize">({{ \Ca\Common::format_filesize($attachment->size) }})</span>
							<span class="actions"><a class="btn_delete" href="#">删除</a></span>
						</li>
						@endforeach
					</ul>
				</td>
			</tr>
		</table>
		<div class="clear"></div>
		<div class="actions">
			<a href="#" class="button_1 btn_submit"><span>提交</span></a>
			<a href="/usercenter/document" class="cancel">取消</a>
		</div>
	</form>
</div>
