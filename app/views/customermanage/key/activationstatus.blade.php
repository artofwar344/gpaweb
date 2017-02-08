<script type="text/javascript">
$(function() {
	$.backend({
		listParams: { "userid": '{{ $user_id }}' },
		tableStructure: {
			eid: "productid",
			columns: [
				{ "key": "productid", "header": "编号", "class": "number" },
				{ "key": "name", "header": "激活商品", "class": "" },
				{ "key": "type", "header": "激活模式", "class": "" },
				{ "key": "available", "header": "可激活", "class": "" },
				{ "key": "used", "header": "已使用", "class": "" },
				{ "key": "requestcount", "header": "申请总量", "class": "", "headertip": "申请激活该商品的总次数"  },
				{ "key": "requesting", "header": "申请中", "class": "time" },
				{ "key": "assigntotalcount", "header": "分配量", "class": "time" },
				{ "key": "denied", "header": "已拒绝", "class": "time" ,"headertip": "请求次数与分配次数之差的总和" }
			]
		},
		category: "回答"
	});
});
</script>

@actions (array('title' => '用户: ' . $user->name . ' - [' . $user->username . ']', 'buttons' => array()))

@dialog
array('label' => '标题', 'type' => 'textbox', 'name' => 'title'),
@enddialog