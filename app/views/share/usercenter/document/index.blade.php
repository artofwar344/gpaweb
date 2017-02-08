<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/jquery.hashchange.js"></script>
<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/uploadify/jquery.uploadify.min.js"></script>
<script type="text/javascript">
$(function() {
	var parentId = window.location.hash.substring(1);
	var encryptParentId = "{{ Crypt::encrypt(Auth::user()->userid) }}";
	var folderLoading = false;
	var selectedDocumentIds = [];
	var table = $(".account_documents .table_1");
	var actions = $(".account_documents .main_actions");
	var selectFolder = $(".select_folder");

	var dialogNewFolder = $("div#dialogNewFolder");
	var dialogDelete = $("div#dialogDelete");
	var dialogMove = $("div#dialogMove");
	var dialogUpload = $("div#dialogUpload");

	var uptoButton = $(".btn_3_upto", actions);

	var dialogParams = { autoOpen: false, modal: true, resizable: false, height: "auto", minHeight: 0 };
	dialogParams.width = 290;
	dialogNewFolder.dialog(dialogParams);
	dialogDelete.dialog(dialogParams);
	dialogUpload.dialog(dialogParams);
	dialogParams.width = 300;
	dialogMove.dialog(dialogParams);

	var percentBar = $(".percent_bar");
	var freeSize = "{{ $freeSize }}";
	var checkSpaceStats = function() {
		$.post("/usercenter/document/checkfreespace", function(ret) {
			var percentBarWith = ret["raw"]["used"] / ret["raw"]["limit"] * 100;
			percentBarWith = percentBarWith > 100 ? 100 : percentBarWith;
			$(".bar_1", percentBar).width(percentBarWith + "%");
			if (percentBarWith >= 100) {
				$(".bar_1", percentBar).css({ "backgroundColor": "red" })
			}
			$(".used", percentBar).text(ret["format"]["used"]);
			$(".limit", percentBar).text(ret["format"]["limit"] || "∞");
			freeSize = ret["raw"]["free"];
			percentBar.show();
		});
	};

	var showList = function() {
		$.post("/usercenter/document/list?documentid=" + parentId, function(ret) {
			if (!ret) window.location = "/usercenter/document";
			$(table).html(ret);
			var parentId = parseInt($("tr:eq(0)", table).attr("pid"));

			if (parentId >= 0) uptoButton.removeClass("button_3_disabled");
			else uptoButton.addClass("button_3_disabled");
			if (parentId <= 0) parentId = "";
			uptoButton.attr("href", "#" + parentId);
		});
	};

	var disableActions = function() {
		$(".button_3", actions).addClass("button_3_disabled");
		$("#file_upload").uploadify('disable', true);
	};

	var refreshActions = function() {
		if ($(".check:checked", table).size() > 0) {
			$(".btn_3_move_file", actions).removeClass("button_3_disabled");
			$(".btn_3_del_file", actions).removeClass("button_3_disabled");
		}
		else {
			$(".btn_3_move_file", actions).addClass("button_3_disabled");
			$(".btn_3_del_file", actions).addClass("button_3_disabled");
		}
		$(".btn_3_upload", actions).removeClass("button_3_disabled");
		$(".btn_3_new_folder", actions).removeClass("button_3_disabled");
		if (parentId) uptoButton.removeClass("button_3_disabled");

		var checked = $(".account_documents .table_1 .check:checked");
		var documentIds = [];
		if (checked.size() > 0) {
			$.each(checked, function(index, checkbox) {
				documentIds.push($(checkbox).val());
			});
		}
		selectedDocumentIds = documentIds;
	};

	var folderList = function(parentId, li) {
		folderLoading = true;
		if (!parentId)
			$(".first", selectFolder)
				.html('<li><em class="expand"></em><span class="root">我的文档</span></li>');

		$.post("/usercenter/document/folderlist", { "parent_id": parentId }, function(ret) {
			var folders = ret.folders;
			var ul = $("<ul />");
			var checked = [];
			$(".check:checked").each(function(i, check) {
				checked[i] = $(check).val();
			});
			$.each(folders, function(index, folder) {
				var li = $("<li />").attr("did", folder.documentid);
				var em = $("<em />").click(function() {
					if (folderLoading) return;
					if (!$(this).hasClass("expand")) folderList(folder.documentid, li);
					else li.next().remove();
					$(this).toggleClass("expand");
				});
				var span = $("<span />").text(folder.name);
				if ($.inArray(folder.documentid, checked) != -1) {
					span.addClass("disabled");
				} else {
					if (folder.has_child) li.append(em);
				}
				ul.append(li.append(span));
			});
			if (!li) $(".first", selectFolder).append($("<li />").append(ul));
			else li.after($("<li />").append(ul));
			folderLoading = false;
		}, "json");
	};

	var removeSelected = function() {
		$.each(selectedDocumentIds, function(index, documentId) {
			$("tr[did=" + documentId + "]", table).remove();
			if ($("tr", table).size() == 1) {
				$("tbody", table).append(
					$("<tr />")
						.addClass("none")
						.append(
							$("<td colspan='5' />")
								.append(
									$("<span>该目录没有文档, 请上传!</span>")
								)
						)
				);
			}
		});
	};

	uptoButton.click(function(event) {
		event.stopImmediatePropagation();
		return !$(this).hasClass("button_3_disabled");
	});

	$(".button_3", actions).click(function(event) {
		if ($(this).hasClass("button_3_disabled")) {
			event.stopImmediatePropagation();
		}
		return false;
	});

	$(".dialog_1 .button_1").click(function(event) {
		if ($(this).hasClass("button_1_disabled")) {
			event.stopImmediatePropagation();
		}
		return false;
	});

	$(".btn_3_new_folder", actions).click(function() {
		dialogNewFolder.dialog("open");
		$(".messages", dialogNewFolder).hide();
		$("input", dialogNewFolder).val("");
		return false;
	});

	$(".btn_3_del_file", actions).click(function() {
		dialogDelete.dialog("open");
		return false;
	});

	$(".btn_3_move_file", actions).click(function() {
		$(".submit", dialogMove).addClass("button_1_disabled");
		dialogMove.dialog("open");
		folderList();
		return false;
	});

	$('#file_upload').uploadify({
		"formData": {
			"parent_id": parentId,
			"user_id": encryptParentId
		},
		"fileTypeExts": "@foreach (Config::get('share.allow_extension') as $ext) *.{{$ext}}; @endforeach",
		"hideButton": true,
		"buttonText": "上传文件",
		"width": "65",
		"height": "20",
		"swf": "{{ Config::get('app.asset_url') . 'scripts/uploadify/uploadify.swf' }}",
		"uploader": "{{ URL::to('/usercenter/document/uploads') }}",
		"onInit": function() {
			$(".btn_3_upload > span").remove();
			$("#SWFUpload_0").css({"left": "10px"});
		},
		'onSWFReady': function() {
			$("#file_upload").uploadify('disable', true);
		},
		"onUploadStart": function(file) {
			if (freeSize <= 0) {
				alert("你的资源空间已满！");
				$("#file_upload").uploadify("cancel");
			}
		},
		"onUploadError": function(file, errorCode, errorMsg, errorString) {
			console.log(errorMsg, errorString);
		},
		"onUploadSuccess": function(file, data, response) {
			var ret = jQuery.parseJSON(data);
			switch (ret.code) {
				case 1:
					showList();
					checkSpaceStats();
					break;
				case 2:
					$.each(ret.messages, function(id, message) {
						$(".messages", dialogUpload).html("").append($("<li />").html(message)).show();
					});
					break;
			}
		}
	});

	$(".submit", dialogNewFolder).click(function() {
		var nameInput = $("#folder_name", dialogNewFolder);
		var folderName = $.trim(nameInput.val());
		if (!folderName) {
			$(".messages", dialogNewFolder).html("").append($("<li />").html("文件夹名称不能为空")).show();
			nameInput.focus();
			return false;
		}
		var $this = $(this);
		$this.addClass("button_1_disabled");
		$.post("/usercenter/document/new", { "folder_name": folderName, "type": 2, "parent_id": parentId  }, function(ret) {

			switch (ret.code) {
				case 1:
					dialogNewFolder.dialog("close");
					var folder = ret.document;
					var row = $('<tr type="2" del="1" did="' + folder.documentid + '"> \
								<td class="check"><input class="check" type="checkbox" value="' + folder.documentid + '" /></td>\
								<td style="text-align:left"> \
									<span style="position:relative">\
										 <a class="title folder" href="#' + folder.documentid + '">' + folder.name + '</a> \
									</span>\
								</td> \
								<td></td> \
								<td></td> \
								<td style="text-align:right">' + folder.createdate + '</td> \
							</tr>');
					if ($("tr[type=2]", table).size() > 0) {
						$("tr[type=2]", table).last().after(row);
					}
					else {
						$("tr", table).remove(".none");
						$("tr", table).first().after(row);
					}
					showInformation("新建文件夹成功.");
					$this.removeClass("button_1_disabled");
					break;
				case 2:
					$.each(ret.messages, function(id, message) {
						$(".messages", dialogNewFolder).html("").append($("<li />").html(message)).show();
					});
					$(".content input:eq(0)", dialogNewFolder).select();
					$this.removeClass("button_1_disabled");
					break;
			}
		}, "json");
		return false;
	});

	table
		.on("click", ".actl_1_delete", function() {
			if ($(this).hasClass("action_link_1_disabled")) return false;
			var tr = $(this).closest("tr");
			var type = tr.attr("type");
			var documentId = tr.attr("did");
			selectedDocumentIds = [documentId];
			dialogDelete.dialog("open");
			return false;
		})
		.on("click", ".actl_1_modify",function() {
			var $this = $(this);
			if ($this.hasClass("action_link_1_disabled")) return false;
			$this.closest(".actions").hide();
			var tr = $this.closest("tr");
			var type = tr.attr("type");
			var documentId = tr.attr("did");
			var nameA = $this.parent().siblings("span").find(".title");
			var text = $.trim(nameA.text());
			nameA.html("&nbsp;");

			var width = $this.parent().siblings(".title").width();
			$this.parent().siblings("span")
				.append(
					$("<input />")
						.attr("type", "text")
						.addClass("textbox_1 change_name")
						.val(text)
						.css({"margin-left": -(width - 20) + "px"})
						.keydown(function(event) {
							if (event.keyCode == 13) {
								event.preventDefault();
								$(this).focusout();
							}
						})
						.focusout(function() {
							var name = $.trim($(this).val());
							nameA.text(text);
							if (name && name != text) {
								$.post("/usercenter/document/changename", { "documentid": documentId, "name": name  }, function(ret) {
									switch (ret.code) {
										case 1:
											nameA.text(name);
											showInformation("修改名称成功.");
											break;
									}
								}, "json");
							}
							$(this).remove();
						})
				).find(".change_name").select();
			return false;
		})
		.on("click", ".actl_1_share", function(event) {
			event.stopPropagation();
			if ($(this).hasClass("action_link_1_disabled")) return false;
			return true;
		})
		.on("click", "tr",function() {
			if ($(this).is("tr:has(.change_name)")) return false;
			var check = $(".check", this);
			check.prop("checked", !check.prop("checked"));
			check.trigger("change");
		})
		.on("click", ".check",function(event) {
			event.stopPropagation();
		})
		.on("click", ".title",function(event) {
			event.stopPropagation();
		})
		.on("change", ".check",function() {
			var checked = $(".account_documents .table_1 .check:checked");
			var documentIds = [];
			if (checked.size() > 0) {
				$.each(checked, function(index, checkbox) {
					documentIds.push($(checkbox).val());
				});
			}
			refreshActions();
			selectedDocumentIds = documentIds;
		})
		.on("mouseenter", "tr",function() {
			if ($(this).hasClass("none") || $(this).hasClass("loading") || $("input.change_name", $(this)).length > 0) return false;
			var canShare = $(this).attr("share");
			var canDelete = $(this).attr("del");
			var documentId = $(this).attr("did");
			var favorite = $(this).attr("favorite");
			var rowActions = '<span class="actions">';
			if (favorite != 1) {
				rowActions += '<a href="#" title="修改" class="action_link_1 actl_1_modify"></a> ';
			}
			var title;
			switch (canShare) {
				case "1":
					title = "已发布";
					break;
				case "2":
					title = "不能发布";
					break;
				case "3":
					title = "发布";
					break;
			}
			rowActions += '<a href="#" title="删除" class="action_link_1 actl_1_delete ' + (canDelete ? "" : "action_link_1_disabled") + '" ></a> \
					<a href="' + (canShare == 3 ? "/usercenter/document/publish?id=" + documentId : "#") + '" title="' + title + '" class="action_link_1 ' + (canShare == 3 ? "" : "action_link_1_disabled") + ' actl_1_share"></a> \
				</span>';
			$("td:eq(1)", this).append($(rowActions));
			return false;
		})
		.on("mouseleave", "tr", function() {
			$(".actions", this).remove();
			return false;
		});

	$(".submit", dialogDelete).click(function() {
		$.post("/usercenter/document/delete", { "documentids": selectedDocumentIds }, function(ret) {
			switch (ret.code) {
				case 1:
					removeSelected();
					checkSpaceStats();
					showInformation("删除文件成功.");
					break;
			}
		}, "json");

		dialogDelete.dialog("close");
		return false;
	});

	$(".submit", dialogMove).click(function() {
		var documentId = $(".selected", selectFolder).first().attr("did");

		dialogMove.dialog("close");
		$.post("/usercenter/document/move", { "documentid": documentId, "documentids": selectedDocumentIds }, function(ret) {
			$(".check", table).removeAttr("checked");
			switch (ret.code) {
				case 1:
					removeSelected();
					showInformation("文件移动成功.");
					break;
			}
		}, "json");
		return false;
	});

	$(selectFolder).on("click", "span", function() {
		$("li", selectFolder).removeClass("selected");
		$(this).closest("li").addClass("selected");
		$(".submit", dialogMove).removeClass("button_1_disabled");
	});

	percentBar.after($("<span />").addClass("loading_1"));
	showList();
	checkSpaceStats();
	refreshActions();

	$(document).ajaxStart(function() {
		percentBar.after($("<span />").addClass("loading_1"));
		$(".actions", dialogMove).prepend($("<span />").addClass("loading_1"));
		disableActions();
	}).ajaxStop(function() {
		$(".loading_1").remove();
		refreshActions();
		if (freeSize > 0) {
			$("#file_upload").uploadify('disable', false);
		} else {
			$("#file_upload").uploadify('disable', true);
		}
		$(".submit", dialogMove).removeClass("button_1_disabled");
	});

	$(window).hashchange(function() {
		parentId = window.location.hash.substring(1);
		$('#file_upload').uploadify('settings', 'formData', { "parent_id": parentId, "user_id": encryptParentId });
		showList();
	});
});
</script>
<div class="dialog_1" id="dialogMove">
	<div class="header"><span>移动文档</span><a class="close"></a></div>
	<div class="content">
		<div class="select_folder">
			<ul class="first"></ul>
		</div>
	</div>
	<div class="actions">
		<a href="#" class="button_1 submit"><span>确定</span></a>
		<a class="close" href="#">取消</a>
	</div>
</div>
<div class="dialog_1" id="dialogDelete">
	<div class="header"><span>删除文档</span><a class="close"></a></div>
	<div class="confirm">是否删除选择的文档?</div>
	<div class="actions">
		<a href="#" class="button_1 submit"><span>确定</span></a>
		<a class="close" href="#">取消</a>
	</div>
</div>
<div class="dialog_1" id="dialogNewFolder">
	<div class="header"><span>新建文件夹</span><a class="close"></a></div>
	<ul class="messages"></ul>
	<div class="content">
		<table class="form_1">
			<tr>
				<td class="label">名称:</td>
				<td><input name="folder_name" id="folder_name" class="textbox_1 textbox_1_a" style="width:205px" type="text" /> </td>
			</tr>
		</table>
		<input type="hidden" id="parent_id" name="parent_id" value="{{ $folder_id }}" />
	</div>
	<div class="actions">
		<a href="#" class="button_1 submit"><span>提交</span></a>
		<a class="close" href="#">取消</a>
	</div>
</div>

<div class="account_documents">
	<ul class="tabsheet_2">
		<li class="selected"><a>我的文档</a></li>
		<li class="info"><span>文档: 共 <strong>{{ $count_document }}</strong> 份</span> | <span>下载: <strong>{{ $count_download }}</strong> 次</span></li>
	</ul>
	<div class="spacer_1"></div>
	<div class="information_1" style="display:none"><div class="success"></div><span class="close"></span></div>
	<div class="main_actions">
		<a href="#" class="button_3 btn_3_upto {{ $folder_id ? '' : 'button_3_disabled' }}"
		@if ($parent_id || $folder_id)
		{{ 'href="/usercenter/document' . ($parent_id ? '?folderid=' . $parent_id : '') . '"' }}
		@endif
		><span>上一级</span></a>
		<a style="position: relative" class="button_3 button_3_disabled btn_3_upload"><span>上传文件</span>
			<input style="display:none" id="file_upload" name="file_upload" type="file" multiple="true">
		</a>
		<a class="button_3 button_3_disabled btn_3_new_folder" href="#"><span>新建文件夹</span></a>
		<a class="button_3 button_3_disabled btn_3_move_file" href="#"><span>移动</span></a>
		<a class="button_3 button_3_disabled btn_3_del_file" href="#"><span>删除</span></a>
		<ul class="percent_bar">
			<li>
				<div class="bar">
					<span class="bar_1"></span>
				</div>
			</li>
			<li><strong class="used">0</strong> / <span class="limit">0</span></li>
		</ul>
		<div class="clear"></div>
	</div>
	<table class="table_1">
		<tr class="menu">
			<th style="text-align:left">文档名称</th>
			<th style="width:40px">来源</th>
			<th style="width:50px">状态</th>
			<th style="width:80px; text-align:right">创建日期</th>
		</tr>
		<tr class="loading"><td colspan="4">加载中, 请稍后...</td></tr>
	</table>
</div>
