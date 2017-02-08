<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/categorySelector.js"></script>
<script>
$(function() {
	var type = "{{ $type }}";
	var typeName = "{{ $typeName }}";
	var dialogDelete = $("#dialogDelete");
	var dialogSetTag = $("#dialogSetTag");
	var dialogSetCategory = $("#dialogSetCategory");
	var dialogParams = { autoOpen: false, modal: true, resizable: false, width: 290, height: "auto", minHeight: 0 };
	dialogDelete.dialog(dialogParams);
	dialogSetTag.dialog(dialogParams);
	dialogParams.width = 400;
	dialogSetCategory.dialog(dialogParams);


	$("#btn_setcategory").click(function() {
		dialogSetCategory.dialog("open");
		return false;
	});
	$("#btn_settag").click(function() {
		dialogSetTag.dialog("open");
		return false;
	});

	var settings = { categories: jQuery.parseJSON('{{ json_encode($categories) }}') };
	var categorySelector = $(".category_selector").categorySelector(settings);

	//ajax添加关注
	var addAttention = function (ajaxUrl, params, dialog) {
		var self = $(".actions .submit", dialog);
		self.addClass("button_1_disabled");
		$("span", self).text("处理中");
		$.post(ajaxUrl, params, function(ret) {
			switch (ret.status >> 0) {
				case 1:
					dialog.dialog("close");
					showInformation("添加" + typeName + "成功");
					document.location.reload();
					break;
				default:
					$(".messages", dialog).html("").append($("<li />").html(ret.message)).show();
					break;
			}
			self.removeClass("button_1_disabled");
			$("span", self).text("确定");
		}, "json");
	};

	$(".actions .submit", dialogSetCategory).click(function() {
		if ($(this).hasClass("button_1_disabled")) return false;
		var selectedCategory = categorySelector.selectedCategory;
		if (selectedCategory == null) {
			$(".messages", dialogSetCategory).html("").append($("<li />").html("请选择分类")).show();
			return false;
		}
		addAttention("/usercenter/knows/addattentioncategory", { "categoryid": selectedCategory }, dialogSetCategory);
		return false;
	});

	$(".actions .submit", dialogSetTag).click(function() {
		var $this = $(this);
		if ($this.hasClass("button_1_disabled")) return false;
		var tagName = $.trim($("#tagname", dialogSetTag).val());
		if (tagName == "") {
			$(".messages", dialogSetTag).html("").append($("<li />").html("请输入关键词")).show();
			return false;
		}
		if (tagName.length > 20 ) {
			$(".messages", dialogSetTag).html("").append($("<li />").html("您输入的关键词太长拉，重新检查一下吧！")).show();
			return false;
		}
		addAttention("/usercenter/knows/addattentiontag", { "name": tagName }, dialogSetTag);
		return false;
	});



	//删除关注
	var displayid = null;
	$(".favs .delete").click(function() {
		displayid = $(this).attr("did");
		dialogDelete.dialog("open");
		return false;
	});

	$(".actions .submit", dialogDelete).click(function() {
		$.post("/usercenter/knows/deleteattention" + type, { "type": type, "id": displayid }, function(ret) {
			switch (ret.status >> 0) {
				case 1:
					dialogDelete.dialog("close");
					showInformation("删除" + typeName + "成功");
					document.location.reload();
					break;
			}
		}, "json");
		return false;
	});

	$(document).ajaxStart(function() {
		$(".main_actions .btn_3_category").after($("<span />").addClass("loading_1"));
	}).ajaxStop(function() {
		$(".loading_1").remove();
	});
});
</script>

<div class="dialog_1" id="dialogDelete">
	<div class="header"><span>删除{{ $typeName }}</span><a class="close"></a></div>
	<div class="confirm">是否删除选择的{{ $typeName }}?</div>
	<div class="actions">
		<a href="#" class="button_1 submit"><span>确定</span></a>
		<a class="close" href="#">取消</a>
	</div>
</div>

<div class="dialog_1" id="dialogSetCategory">
	<div class="header"><span>添加分类</span><a class="close"></a></div>
	<ul class="messages"></ul>
	<div class="content">
		<div class="category_selector"></div>
	</div>
	<div class="actions">
		<a href="#" class="button_1 button_1_b submit"><span>确定</span></a>
		<a class="close" href="#">取消</a>
	</div>
</div>

<div class="dialog_1" id="dialogSetTag">
	<div class="header"><span>添加关键词</span><a class="close"></a></div>
	<ul class="messages"></ul>
	<div class="content">
		<div><input class="textbox_1 textbox_1_a" id="tagname" type="text" name="tag" placeholder="请输入关键词" /></div>
	</div>
	<div class="actions">
		<a href="#" class="button_1 button_1_b submit"><span>确定</span></a>
		<a class="close" href="#">取消</a>
	</div>
</div>

<div class="account_questions_fav account_questions">
	<ul class="tabsheet_2">
		<li @if ($type == 'category') class="selected" @endif><a href="/usercenter/knows/attention?type=category">我关注的分类</a></li>
		<li><span>|</span></li>
		<li @if ($type == 'tag') class="selected" @endif><a href="/usercenter/knows/attention?type=tag">我关注的关键词</a></li>
		<li class="info"><span>共 <strong>{{ $questions->getTotal() }}</strong> 条</span></li>
	</ul>
	<div class="information_1" id="information_1" style="display:none"><div class="success"></div><span class="close"></span></div>
	<div class="spacer_1"></div>
	<div class="main_actions">
		<a id="btn_set{{ $type }}" class="button_3 btn_3_category" href="#"><span>添加{{ $typeName }}</span></a>
		<div class="clear"></div>
	</div>
	<div class="favs">
		@if (count($attentionObjects) > 0)
		<a class="keyword @if (!$displayid) hot @endif" href="/usercenter/knows/attention?type={{ $type }}">全部</a>
		@foreach ($attentionObjects as $object)
		| <a class="keyword @if ($displayid == $object['id']) hot @endif" href="/usercenter/knows/attention?type={{ $type }}&displayid={{ $object['id'] }}">{{ $object['name'] }}</a><span did="{{ $object['id'] }}" class="delete">×</span>
		@endforeach
		@else
		你还没有关注{{ $typeName }}, 点击“添加{{ $typeName }}”开始添加
		@endif
	</div>
	@include('share.usercenter.knows.list', $questions)
</div>

