<script type="text/javascript">
	$(function() {
		$.backend({
			tableStructure: { eid: "customerid", struct: ["customerid", "name", "alias", "status_text", "database_status_text"] },
			category: "参数",
			operators: [ "modify" ],
			modifyStructure: {
				name: "name",
				companyname: "companyname",
				customername: "customername",
				autoassignopen: "autoassignopen",
				autoassignkeys: "autoassignkeys",
				clientversion: "clientversion",
				batchclientversion: "batchclientversion",
				proxyserver: "proxyserver",
				ldapdn: "ldapdn",
				ldaphost: "ldaphost",
				register: "register",
				changepassword: "changepassword",
				retrievepassword: "retrievepassword",
				servicephone: "servicephone",
				wsusserver: "wsusserver"
			},
			modifyDialogWidth: 600,
			validateRule: {
				companyname: {
					required: true,
					maxlength: 128
				},
				customername: {
					required: true,
					maxlength: 128
				},
				clientversion: {
					required: true
				},
				servicephone: {
					required: true
				},
				wsusserver: {
					required: true
				}
			},
			validateMessages: {
				companyname: {
					required: "公司名称不能为空",
					minlength: "名称长度不得超过128"
				},
				customername: {
					required: "客户名称不能为空",
					minlength: "客户名称长度不得超过128"
				},
				clientversion: {
					required: "客户端版本不能为空"
				},
				servicephone: {
					required: "服务电话不能为空"
				},
				wsusserver: {
					required: "WSUS服务器不能为空"
				}
			},
			modifyLoad: function(eid, func) {
				$.post("/customerparams/keys", {"eid": eid}, function(ret) {
					var empty = $("#autoassignkeys", "#dlg_new");
					empty.empty();
					if (ret.keys.length == 0) empty.append("<div class='nodata'>无可分配密钥</div>");
					var table = "<table>";
					$.each(ret.keys, function(id, key) {
						table += "<tr><td class='product_name'>" + key["name"] + ":</td><td class='product_amount'>"
							+ "<input type='text' id='key_" + key["keyid"] + "' name='autoassignkeys[" + key["keyid"] + "]' /></td></tr>";
					});
					table += "</table>";
					empty.append(table);
					var json_data = [];
					if (ret.autoassignkeys) json_data = jQuery.parseJSON(ret.autoassignkeys);
					$.each(json_data, function(id, key) {
						$("#key_" + key["keyid"]).val(key["amount"]);
					});
					func();
				}, "json");
			}

		});
	});

</script>
@actions ('参数管理', '', array())

@search
array('label' => '名称', 'type' => 'textbox', 'name' => 'name')
@endsearch

@table
array('name' => '编号', 'css' => 'number'),
array('name' => '客户名称'),
array('name' => '别名'),
array('name' => '客户状态', 'css' => 'state'),
array('name' => '数据库状态', 'css' => 'state')
@endtable

@dialog
array('label' => '客户名称', 'type' => 'textbox', 'name' => 'name'),
array('label' => 'companyname', 'type' => 'textbox', 'name' => 'companyname'),
array('label' => 'customername', 'type' => 'textbox', 'name' => 'customername'),
array('label' => 'clientversion', 'type' => 'textbox', 'name' => 'clientversion'),
array('label' => 'batchclientversion', 'type' => 'textbox', 'name' => 'batchclientversion'),
array('label' => 'ldapdn', 'type' => 'textbox', 'name' => 'ldapdn'),
array('label' => 'ldaphost', 'type' => 'textbox', 'name' => 'ldaphost'),
array('label' => 'servicephone', 'type' => 'textbox', 'name' => 'servicephone'),
array('label' => 'wsusserver', 'type' => 'textbox', 'name' => 'wsusserver'),
array('label' => 'proxyserver', 'type' => 'textbox', 'name' => 'proxyserver'),
array('label' => 'register', 'type' => 'select', 'name' => 'register', 'values' => Consts::$switch_text),
array('label' => 'changepassword', 'type' => 'select', 'name' => 'changepassword', 'values' => Consts::$switch_text),
array('label' => 'retrievepassword', 'type' => 'select', 'name' => 'retrievepassword','values' => Consts::$switch_text),
array('label' => 'autoassignopen', 'type' => 'select', 'name' => 'autoassignopen', 'values' => Consts::$switch_text),
array('label' => 'autoassignkeys', 'type' => 'empty', 'name' => 'autoassignkeys'),
@enddialog
