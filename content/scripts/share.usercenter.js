/**
 * share usercenter 删除功能的js
 * 使用 $.shareUserCenter(settings)
 * settings:
 *   settings.table 目标表格元素
 *   settings.actions 操作按钮的父级元素
 *   settings.deleteUrl (string) 进行删除操作的post Url
 *   settings.extraData (object) post的额外数据
 *   settings.mouseenterAction (function) 鼠标移至表格行上的操作，默认为显示删除按钮
 *   settings.deleteCheck (function) 进行删除操作前进行验证的函数，默认无验证
 *   settings.emptyRow 全部删除后显示的内容
 *
 * selectedEntityIds (array) 选中元素的id
 */

(function($) {
	var SUC = function(settings) {
		this.settings = {
			table: $(".table_1"),
			actions: $(".main_actions")
		};
		$.extend(true, this.settings, settings);
		this.start();
	};

	SUC.prototype = {
		selectedEntityIds: [],
		dialogDelete: null,
		start: function () {
			$(".dialog_1 .button_1").click(function(event) {
				if ($(this).hasClass("button_1_disabled")) {
					event.stopImmediatePropagation();
					return false;
				}
			});
			$(".button_3", this.settings.actions).click(function(event) {
				if ($(this).hasClass("button_3_disabled")) {
					event.stopImmediatePropagation();
					return false;
				}
			});

			var SUC = this;
			$(document)
				.ajaxStart(function() {
					$(".btn_3_del_file", SUC.settings.actions).after($("<span />").addClass("loading_1"));
					SUC.disableActions();
				})
				.ajaxStop(function() {
					$(".loading_1").remove();
					SUC.refreshActions(SUC);
				});

			SUC.initDialogDelete();
			SUC.initTableAction();
			SUC.initDeleteAction();
			SUC.refreshActions(SUC);
		},
		initDialogDelete: function() {
			this.dialogDelete = $("div#dialogDelete");
			var dialogParams = { autoOpen: false, modal: true, resizable: false, width: 285, height: "auto", minHeight: 0 };
			this.dialogDelete.dialog(dialogParams);
		},
		initTableAction: function() {
			var SUC = this;
			var table = SUC.settings.table;
			var dialogDelete = SUC.dialogDelete;
			var actions = SUC.settings.actions;

			if (typeof(this.settings.mouseenterAction) === "function") {
				var mouseenterAction = this.settings.mouseenterAction;
			} else {
				var mouseenterAction = function() {
					if ($(this).hasClass("none") || $(this).hasClass("loading")) return false;
					var rowActions = '<span class="actions"><a href="#" title="删除" class="action_link_1 actl_1_delete"></a></span>';
					$("td:eq(1)", this).append($(rowActions));
					return false;
				};
			}

			table
				.on("mouseenter", "tr", mouseenterAction)
				.on("mouseleave", "tr", function() {
					$(".actions", this).remove();
					return false;
				})
				.on("click", "tr", function() {
					var check = $(".check", this);
					check.prop("checked", !check.prop("checked"));
					check.trigger("change");
				})
				.on("click", ".check", function(event) {
					event.stopPropagation();
				})
				.on("click", ".title", function(event) {
					event.stopPropagation();
				})
				.on("click", ".modify", function(event) {
					event.stopPropagation();
				})
				.on("change", ".check", function() {
					SUC.refreshActions(SUC);
				});

		},
		initDeleteAction: function() {
			var SUC = this;
			var actions = SUC.settings.actions;
			var dialogs = SUC.settings.dialogs;
			var table = SUC.settings.table;
			var dialogDelete = SUC.dialogDelete;
			$(".btn_3_del_file", actions).click(function() {
				if ($(this).hasClass('button_3_disabled')) return false;
				if (typeof(SUC.settings.deleteCheck) == "function") {
					var deleteCheck = SUC.settings.deleteCheck;
					if (deleteCheck(SUC.selectedEntityIds)) {
						dialogDelete.dialog("open");
					}
				} else {
					dialogDelete.dialog("open");
				}
				return false;
			});

			table.on("click", ".actl_1_delete", function() {
				if ($(this).hasClass("action_link_1_disabled")) return false;
				var tr = $(this).closest("tr");
				var type = tr.attr("type");
				var eid = tr.attr("eid");
				SUC.selectedEntityIds = [eid];
				if (typeof(SUC.settings.deleteCheck) == "function") {
					var deleteCheck = SUC.settings.deleteCheck;
					if (deleteCheck(SUC.selectedEntityIds)) {
						dialogDelete.dialog("open");
					}
				} else {
					dialogDelete.dialog("open");
				}
				return false;
			});

			$(".submit", dialogDelete).click(function() {
				var data = { "eids": SUC.selectedEntityIds };
				if (typeof(SUC.settings.extraData) == "object") {
					$.extend(true, data, SUC.settings.extraData);
				}
				$.post(SUC.settings.deleteUrl, data, function(ret) {
					switch (ret.code) {
						case 1:
							var count = $(".info span strong", ".tabsheet_2").text();
							$(".info span strong", ".tabsheet_2").text(parseInt(count) - SUC.selectedEntityIds.length);
							SUC.removeSelected();
							showInformation("删除成功.");
							break;
					}
				}, "json");
				dialogDelete.dialog("close");
				return false;
			});
		},
		refreshActions: function(SUC) {
			var table = SUC.settings.table;
			var actions = SUC.settings.actions;
			if ($(".check:checked", table).size() > 0) {
				$(".btn_3_del_file", actions).removeClass("button_3_disabled");
			} else {
				$(".btn_3_del_file", actions).addClass("button_3_disabled");
			}
			var checked = $(".check:checked", table);
			var entityIds = [];
			if (checked.size() > 0) {
				$.each(checked, function(index, checkbox) {
					entityIds.push($(checkbox).val());
				});
			}
			SUC.selectedEntityIds = entityIds;
		},
		removeSelected: function() {
			var table = this.settings.table;
			var emptyRow = this.settings.emptyRow;
			$.each(this.selectedEntityIds, function(index, eid) {
				$("tr[eid=" + eid + "]", table).remove();
				if ($("tr", table).size() == 1) {
					$("tbody", table).append(emptyRow);
				}
			});
		},
		disableActions: function() {
			$(".button_3", this.settings.actions).addClass("button_3_disabled");
		}

	};

	$.shareUserCenter = function(settings) {
		return new SUC(settings);
	};

}) (jQuery);


