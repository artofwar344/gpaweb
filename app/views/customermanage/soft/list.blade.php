<script type="text/javascript">
var softlogRole @if (Ca\Service\ManagerService::check_role('softlog')) = true @endif;
$(function() {
	$.backend({
		tableStructure: {
			eid: "softid",
			columns: [
				{ "key": "softid", "header": "编号", "class": "number" },
				{ "key": "softid_text", "header": "图标" },
				{ "key": "name", "header": "名称" },
				{ "key": "category_name", "header": "分类" },
				{ "key": "language_text", "header": "软件语言" },
				{ "key": "licensetype_text", "header": "授权类型" },
				{ "key": "platform", "header": "软件平台", "headertip": "软件支持的操作系统" },
				{ "key": "version", "header": "当前版本" },
				{ "key": "filesize_text", "header": "文件大小" },
				{ "key": "bit_text", "header": "位数", "headertip": "软件的编译位数: <span class='subtip_1'>32位或者64位</span>" },
				{ "key": "type_text", "header": "所属板块", "headertip": "软件在哪些板块中显示" },
				{ "key": "description", "header": "描述", "class": "text text300" },
				{ "key": "status_text", "header": "状态", "headertip": "<strong>可用</strong>: 该软件在资源下载网站上显示<br/><strong>禁用</strong>: 在资源共享网站上不显示该软件" },
				{ "key": "updatedate", "header": "更新时间", "class": "time", "headertip": "该软件最新版本上传时间" }
			]
		},
		category: "软件",
		selects: [ "categoryid", "type" ],
		operators: [
			softlogRole ? { type: "iframe", url: "/softlog?id={eid}", text: "软件记录", css: "btn_view", width: "600px", height: "680px" , tip: "查看该软件使用记录" } : "",
			{ type: "modify", tip: "编辑软件", text: "编辑", css: "btn_modify" }
		],
		modifyStructure: { type: "[type]", status: "status" },
		validateRule: {
			status: {
				required: true
			}
		},

		validateMessages: {
			status: { required: "状态不能为空" }
		}
	});
});
</script>

@actions (array('title' => '软件管理', 'action' => '软件', 'buttons' => array()))

@search
	array('label' => '名称', 'type' => 'textbox', 'name' => 'name'),
	array('label' => '所属分类', 'type' => 'select', 'name' => 'categoryid'),
	array('label' => '所属板块', 'type' => 'select', 'name' => 'type')
@endsearch

@dialog
	array('label' => '所属板块', 'type' => 'checklist', 'name' => 'type', 'values' => Ca\Consts::$soft_type_texts),
	array('label' => '状态', 'type' => 'select', 'name' => 'status', 'values' => Ca\Consts::$soft_status_texts)
@enddialog