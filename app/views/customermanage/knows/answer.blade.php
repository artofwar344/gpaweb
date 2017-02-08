<script type="text/javascript">
$(function() {
	$.backend({
		listParams: { "id": '{{ $questionid }}' },
		tableStructure: {
			eid: "answerid",
			columns: [
				{ "key": "answerid", "header": "编号", "class": "number" },
				{ "key": "content_text", "header": "回答内容", "class": "paragraph" },
				{ "key": "user_name", "header": "用户名" },
				{ "key": "createdate", "header": "回答时间", "class": "time" }
			]
		},
		category: "回答",
		operators: ["delete"]
	});
});
</script>

@actions (array('title' => '提问: ' . $question->title, 'buttons' => array()))

@dialog
array('label' => '标题', 'type' => 'textbox', 'name' => 'title'),
//array('label' => '发布状态', 'type' => 'select', 'name' => 'publish', 'values' => Consts::$knows_publish_texts)
@enddialog