<script type="text/javascript">
$(function() {
	var updateStatus = function(eid) {
		$.post("/knows/status", { "eid": eid }, function() {
			backend.list();
		});
	};

	var getText = function(row) {
		switch (row["status"] >> 0) {
			case 1:
				return [ "禁用", true ];
			case 2:
				return [ "启用", false ];
		}
		return "";
	};

	var answerEnable = function(row) {
		return row["answer_count"] > 0;
	};

	var answerTip = function(row) {
		return answerEnable(row) ? "查看该提问下的所有回答" : "该提问还没有人回答";
	};

	var updateTip = function(row) {
		switch (row["status"] >> 0) {
			case 1:
				return "修改提问状态为\"禁用\"<br/><span class='subtip_1'>禁用后, 网站上将隐藏该问题和相关回答</span>";
			case 2:
				return "修改提问状态为\"正常\"<br/><span class='subtip_1'>启用后, 网站上可以看到该问题和相关回答</span>";
		}
		return "";
	};

	var statusValueClass = function(row) {
		var ret = "";
		switch (parseInt(row["status"])) {
			case 1:
				ret = "green";
				break;
			case 2:
				ret = "red";
				break;
		}

		return ret;
	};

	var backend = $.backend({
		tableStructure: {
			eid: "questionid",
			columns: [
				{ "key": "questionid", "header": "编号", "class": "number" },
				{ "key": "title", "header": "提问标题", "class": "text text500" },
				{ "key": "category_name", "header": "所属分类" },
				{ "key": "user_name", "header": "提问用户", "headertip": "该问题提问用户" },
				{ "key": "views", "header": "浏览", "class": "count", "headertip": "该问题浏览次数" },
				{ "key": "answer_count", "header": "回答", "class": "count", "headertip": "该问题回答次数" },
				{ "key": "accepted", "header": "采纳", "headertip": "该问题是否已经采纳正确答案" },
				{ "key": "status_text", "header": "状态", "class": "state", "headertip": "<strong>正常</strong>: 该问题正常显示在网站内<br/><strong>禁用</strong>: 该问题将不会现在页面内", "valueclass": statusValueClass },
				{ "key": "createdate", "header": "提问时间", "class": "time" }
			]
		},
		category: "提问",
		selects: [ "categoryid" ],
		modifyStructure: { title: "title" },
		operators: [
			{ type:'iframe', url: "/answer?id={eid}", text: "查看回答", css: "btn_view", width: "90%", height: "570px", enable: answerEnable, tip: answerTip  },
			{ type: "callback", callback: updateStatus, text: getText, css: "btn_switch", tip: updateTip },
			{ type: "delete", tip: "删除提问<span class='subtip_1'><strong>注意</strong>: 删除问题将删除该问题相关的所有答案!</span>", text: "删除", css: "btn_delete" }
		],
		validateRule: {
			title: {
				required: true,
				maxlength: 64
			}
		},

		validateMessages: {
			title: {
				required: "名称不能为空",
				minlength: "名称长度不得超过64"
			}
		}
	});
});
</script>

@actions (array('title' => '问答列表', 'buttons' => array()))

@search
	array('label' => '标题', 'type' => 'textbox', 'name' => 'title', 'placeholder' => '提问标题'),
	array('label' => '分类', 'type' => 'select', 'name' => 'categoryid'),
@endsearch
