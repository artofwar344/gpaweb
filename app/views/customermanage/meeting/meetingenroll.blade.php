{{ HTML::style(Config::get('app.asset_url') . '/scripts/kindeditor/themes/default/default.css') }}
{{ HTML::script(Config::get('app.asset_url') . '/scripts/kindeditor/kindeditor-min.js'); }}
{{ HTML::script(Config::get('app.asset_url') . '/scripts/kindeditor/lang/zh_CN.js'); }}
<script type="text/javascript">
$(function() {
	$.backend({
		listParams: { "id": '{{ $meetingid }}' },
		tableStructure: {
			eid: "userid",
			columns: [
				{ "key": "userid", "header": "编号", "class": "number" },
				{ "key": "username", "header": "用户名" },
				{ "key": "user_name", "header": "姓名" },
				{ "key": "email", "header": "邮箱" },
				{ "key": "departmentid_text", "header": "所在部门" }
			]
		},
		category: "报名",
		operators: [
			{ type: "delete", tip: "删除报名用户", text: "删除", css: "btn_delete" }
		]
	});
});
</script>

@actions (array('title' => '讲座: ' . $meeting->name, 'buttons' => array()))



