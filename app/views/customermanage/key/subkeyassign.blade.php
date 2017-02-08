<script type="text/javascript">
	$(function() {
		var keyId = "{{ Input::get('keyid') }}";
		$.backend({
			listParams: { "keyid": keyId },
			modifyDialogWidth: 350,
			tableStructure: {
				eid: "subkeyid",
				columns: [
					{ "key": "subkeyid", "header": "编号", "class": "number" },
					{ "key": "key_name", "header": "名称", "headertip": "该密钥的别名, 用于辨别密钥" },
					{ "key": "section", "header": "密钥片段", "class": "state", "headertip": "该密钥的一段, 用于辨别密钥<br/><span class='subtip_1'>永久激活需要使用该信息</span>" },
					{ "key": "product_name", "header": "所属商品", "headertip": "该密钥可以激活上的商品" },
					{ "key": "type", "header": "激活模式", "headertip": "<strong>定时激活</strong>: 需要在180天后再次运行客户端激活<br/><strong>永久激活</strong>: 激活后永久保持激活状态" },
					{ "key": "user_name", "header": "分配用户" },
					{ "key": "note", "header": "备注", "class": "text" },
					{ "key": "outdate", "header": "分配时间", "class": "time" }
				]
			},
			category: "密钥",
			selects: []

		});
	});
</script>

@actions (array('title' => '密钥查看', 'action' => '密钥', 'buttons' => array()))

@search
array('label' => '名称', 'type' => 'textbox', 'name' => 'name', 'placeholder' => '用户真实姓名'),
array('label' => '账号', 'type' => 'textbox', 'name' => 'username', 'placeholder' => '用户登陆账号')
@endsearch
