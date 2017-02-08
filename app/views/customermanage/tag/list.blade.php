<script type="text/javascript">
	$(function() {
		$.backend({
		tableStructure: {
				eid: "tagid",
				columns: [
					{ "key": "tagid", "header": "编号", "class": "number" },
					{ "key": "name", "header": "标签名称" },
					{ "key": "document_tagcount", "header": "文档", "class": "count", "headertip": "资源共享网站文档模块使用该标签次数" },
					{ "key": "meeting_tagcount", "header": "讲座", "class": "count", "headertip": "资源共享网站讲座模块使用该标签次数" },
					{ "key": "question_tagcount", "header": "问答", "class": "count", "headertip": "资源共享网站问答模块使用该标签次数" }
				]
			},
			category: "标签",
			selects: [],
			modifyStructure: { name: "name" },
			operators: [
				{ type: "modify", tip: "编辑标签信息", text: "编辑", css: "btn_modify" },
				{ type: "delete", tip: "删除标签", text: "删除", css: "btn_delete" }
			],
			validateRule: {
				name: {
					required: true,
					maxlength: 16
				}
			},

			validateMessages: {
				name: {
					required: "名称不能为空",
					minlength: "名称长度不得超过16"
				}
			}
		});
	});
</script>

@actions (array('title' => '标签管理', 'action' => '标签'))

@search
array('label' => '标签名称', 'type' => 'textbox', 'name' => 'title', 'placeholder' => '标签名称'),
@endsearch

@dialog
array('label' => '标签名称', 'type' => 'textbox', 'name' => 'name', 'placeholder' => '标签名称'),
@enddialog