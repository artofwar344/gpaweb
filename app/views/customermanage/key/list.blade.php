<script type="text/javascript">
$(function() {
	var viewEnable = function(row) {
		return (row["type"] == "手动激活" || row["type"] == "零售激活");
	};
	var viewTip = function(row) {
		return (viewEnable(row) ? "查看密钥详情" : "手动激活与零售激活方式的密钥才有详细记录");
	};

	var logViewUrl = function(row) {
		return "/subkeyassign?keyid=" + row["keyid"];
	};

	$.backend({
		modifyDialogWidth: 350,
		tableStructure: {
			eid: "keyid",
			columns: [
				{ "key": "keyid", "header": "编号", "class": "number" },
				{ "key": "name", "header": "名称", "headertip": "该密钥的别名, 用于辨别密钥" },
				{ "key": "department_name", "header": "所属部门", "headertip": "该密钥所属部门" },
				{ "key": "section", "header": "密钥片段", "class": "state", "headertip": "该密钥的一段, 用于辨别密钥<br/><span class='subtip_1'>永久激活需要使用该信息</span>" },
				{ "key": "product_name", "header": "所属商品", "headertip": "该密钥可以激活上的商品" },
				{ "key": "type", "header": "激活模式", "headertip": "<strong>定时激活</strong>: 需要在180天后再次运行客户端激活<br/><strong>永久激活</strong>: 激活后永久保持激活状态" },
				{ "key": "server_text", "header": "激活服务器", "headertip": "激活应用的激活服务器<br/><span class='subtip_1'>定时激活需要使用该信息</span>" },
				{ "key": "count", "header": "激活总量", "class": "count", "headertip": "该密钥可以激活商品的总量<br/><span class='subtip_1'>包含已分配和未分配数量</span>" },
				{ "key": "departmentassigncount", "header": "部门分配", "class": "count", "headertip": "已分配给下级部门的激活总量" },
				{ "key": "assigncount", "header": "用户分配", "class": "count", "headertip": "已分配当前部门用户的总量" },
				{ "key": "note", "header": "备注", "class": "text" },
				{ "key": "createdate", "header": "创建时间", "class": "time" }
			]
		},
		operators: [
			{ type: "iframe", enable: viewEnable, url: logViewUrl, text: "使用详情", css: "btn_view", width: "80%", height: "570px", tip: viewTip }
		],
		category: "密钥",
		selects: [ "productid", "departmentid" ],
		modifyStructure: { name: "name", productid: "productid", server: "server", count: "count", note: "note" },
		validateRule: {
			productid: {
				required: true
			},
			departmentid: {
				required: true
			},
			name: {
				required: true,
				maxlength: 64
			},
			key: {
				required: true,
				maxlength: 29
			},
			count: {
				required: true,
				digits: true
			},
			note: {
				maxlength: 256
			}
		},
		modifyUnvalidateRules: [ "key" ],

		validateMessages: {
			name: {
				required: "名称不能为空",
				minlength: "名称长度不得超过64"
			},
			key: {
				minlength: "key长度不得超过29",
				required: "密钥不能为空"
			},
			count: {
				required: "数量不能为空",
				digits: "请输入正整数"
			},
			note: {
				minlength: "备注长度不得超过256"
			},
			productid: {
				required: "请选择所属产品"
			},
			departmentid: {
				required: "请选择所属部门"
			}
		}
	});
});
</script>

@actions (array('title' => '密钥查看', 'action' => '密钥', 'buttons' => array()))

@search
	array('label' => '名称', 'type' => 'textbox', 'name' => 'name', 'placeholder' => '密钥名称'),
	array('label' => '所属产品', 'type' => 'select', 'name' => 'productid')
@endsearch
