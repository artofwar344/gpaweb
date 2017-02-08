<script type="text/javascript">
	$(function() {
		var autoassignid = "{{ $autoassignid }}";
		var backend = $.backend({
			listParams: { autoassignid: autoassignid },
			getParams: { autoassignid: autoassignid },
			deleteParams: { autoassignid: autoassignid },
			newParams: { autoassignid: autoassignid },
			tableStructure: {
				eid: "username",
				columns: [
					{ "key": "username", "header": "用户名" }
				]
			},
			category: "用户",
			selects: [ ],
			modifyStructure: { username: "username" },
			operators: [ "modify" ,"delete" ],
			validateRule: {
				username: {
					required: true,
					maxlength: 64
				}
			},
			validateMessages: {
				username: {
					required: "用户名不能为空",
					maxlength: "用户名长度不得超过64"
				}
			}
		});
	});
</script>

@actions (array('title' => '用户列表', 'action' => '用户'))

@dialog
array('label' => '用户名', 'type' => 'textbox', 'name' => 'username', 'placeholder' => '用户名'),
@enddialog


