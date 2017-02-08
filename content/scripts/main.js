(function ($) {
	$.backend = function (settings) {
		settings = jQuery.extend({
			listParams: {},
			getParams: {},
			selectsParams: {},
			deleteParams: {},
			newParams: {},
			modifyLoad: {},
			tableStructure: {},
			modifyStructure: {},
			category: "",
			selects: [],
			pageIndex: 1,
			operators: [],
			dialogWidth: 300,
			modifyDialogWidth: 300,
			validateRule: {},
			modifyUnvalidateRules: [],
			validateMessages: {},
			newDisabledFields: [],
			modifyDisabledFields: [],
			actionDisabledFields: [],
			newDefaultValues: {},
			searchDefaultValues: {},
			showSearchBar: true
		}, settings);
		var searching = false; // is searching
		var listParams = {}; // list page parameters
		var getParams = {}; // get row parameters
		var selectsParams = {}; // selects parameters
		var deleteParams = {}; // delete parameters
		var newParams = {}; // delete parameters
		var checkedRow = []; // checked rows
		var controller = window.location.pathname.split("/")[1]; // php controller
		var table; // main table
		var dlgNew = $("#dlg_new"); // new or modify dialog
		var paging; // paging
		var mainActions = $(".main_actions"); // main actions
		var multiActions = $(".multi_actions"); // multi actions
		var mainSearch = $(".main_search"); // main search bar
		var btnSearch = $(".button_search", mainSearch); // main search button
		var btnSearchClear = $(".button_clear", mainSearch); // main search clear
		var btnAdd = $(".button_add", mainActions); // add new record button
		var body = $("body");
		var mainContent = $(".main_content");
		var dlgValidate;

		var clearCheckedRow = function() {
			checkedRow = [];
		};

		table = $("<table/>");
		paging = $("<div/>").addClass("paging_1");
		$("<div />").addClass("table_1").append(table).append(paging).appendTo(mainContent);

		var showMessage = function(title, content) {
			$("h1", dlgMessage).html(title);
			$(".info", dlgMessage).html(content);
			dlgMessage.dialog("open");
		};

		// list records
		var list = function() {
			var hasCheckbox = settings.tableStructure.checkbox;
			var topCheckbox;
			listParams = $.extend({}, settings.listParams, listParams);

			listParams["page"] = settings.pageIndex;
			$.post("/" + controller + "/list", listParams, function(ret) {
				var rows = ret.list;
				var pageCount = ret.count;
				var entityCount = ret.entityCount;

				$("tr", table).remove();
				var tr = $("<tr/>").appendTo(table);
				topCheckbox = $("<input/>").attr("type", "checkbox").click(function() {
					var checkboxs = $("td.checkbox input", table).not(":disabled");
					checkboxs.prop("checked", this.checked);
					$.each(checkboxs, function(index, checkbox) {
						var eid = $(checkbox).attr("eid");
						if ($(checkbox).is(":checked")) {
							$(checkbox).closest("tr").addClass("checked");
							if (checkedRow.indexOf(eid) == -1) checkedRow.push(eid);
						}
						else {
							$(checkbox).closest("tr").removeClass("checked");
							if (checkedRow.indexOf(eid) != -1) checkedRow.splice(checkedRow.indexOf(eid), 1);
						}
					});
					updateMultiAction();
				});
				if (hasCheckbox) $("<th/>").addClass("checkbox").append(topCheckbox).prependTo(tr);
				$.each(settings.tableStructure.columns, function(index, column) {
					var th = $("<th/>");
					th.attr("key", column.key);
					if (column["class"] != undefined) th.addClass(column["class"]);
					th.append($("<span/>").html(column.header));
					if (column.visible == false) th.hide();
					if (column.headertip != undefined) th.append(
						$("<span/>").addClass("tip_1").attr("title", column.headertip).tooltip({
							content: function() {
								return $(this).attr("title");
							}
						}));
					th.appendTo(tr);
				});
				tr.append($("<th/>").addClass("action").text("动作"));

				if (rows.length > 0) {
					$.each(rows, function(index, row) {
						var eid = row[settings.tableStructure.eid].toString();
						var tr = $("<tr />").attr("eid", eid);

						if (hasCheckbox) {
							var checkbox = $("<input />").attr("type", "checkbox").attr("eid", eid).click(function() {
								if (checkbox.is(":checked")) {
									tr.addClass("checked");
									if (checkedRow.indexOf(eid) == -1) checkedRow.push(eid);
									if ($("td.checkbox input:not(:checked):not(:disabled)").size() == 0) topCheckbox.prop("checked", true);
								}
								else {
									tr.removeClass("checked");
									if (checkedRow.indexOf(eid) != -1) checkedRow.splice(checkedRow.indexOf(eid), 1);
									topCheckbox.prop("checked", false);
								}

								updateMultiAction();
							});
							var checkboxEnable = true;
							if (settings.tableStructure.checkboxEnable != undefined) {
								checkboxEnable = settings.tableStructure.checkboxEnable;
								if (typeof settings.tableStructure.checkboxEnable == "function") checkboxEnable = checkboxEnable(row);
							}
							if (!checkboxEnable) checkbox.prop("disabled", true);
							if (checkedRow.indexOf(eid) != -1) {
								checkbox.attr("checked", true);
								tr.addClass("checked");
							}
							$("<td />").addClass("checkbox").append(checkbox).appendTo(tr);
						}

						$.each(settings.tableStructure.columns, function(index, column) {
							var key = column.key;
							var th = $("th", table).not(".checkbox").eq(index);
							var structs = key.split(".");
							var value = row;
							$.each(structs, function(id, s) {
								value = value[s];
							});
							var td = $("<td />");

							// hide column
							if (!th.is(":visible")) td.hide();

							// empty field
							if (!value) td.addClass("empty");

							// overflow hidden text
							if (th.hasClass("text")) {
								var width = th.css("width");
								td.append(
									$("<div />")
										.html(value)
										.attr("title", value)
										.addClass("text_wrap")
										.append($("<span />"))
								);
							}
							else td.html(value);

							var valueClass = column.valueclass;
							if (valueClass != undefined) {
								if (typeof valueClass == "function") valueClass = valueClass(row);
								td.addClass(valueClass);
							}

							if (th.hasClass("paragraph")) td.addClass("paragraph");

							if (index == 0) td.addClass("index");
							tr.append(td);
						});

						if (typeof(settings.operators) != "undefined" && settings.operators.length > 0) {
							var otd = $("<td />").addClass("action");
							$.each(settings.operators, function(index, operator) {
								if (typeof(operator) == "string") {
									switch (operator) {
										case "modify":
											otd.append($("<a />").text("编辑").attr("href", "#").addClass("button_2 btn_modify"));
											break;
										case "delete":
											otd.append($("<a />").text("删除").attr("href", "#").addClass("button_2 btn_delete"));
											break;
									}
								}
								else {
									var type = operator["type"];
									var callback = operator["callback"];
									var info = operator["info"];
									var text = operator["text"];
									var css = operator["css"];
									if (typeof text == "function") {
										var textWithCss = text(row);
										text = textWithCss[0];
										css += "_" + (textWithCss[1] ? "on" : "off");
									}

									var enable = operator["enable"];
									if (enable == undefined) enable = true;
									else if (typeof enable == "function") enable = enable(row);
									var confirmext = operator["confirm"];
									if (confirmext == undefined) confirmext = false;
									var confirmText = operator["confirmText"];
									var url = operator["url"];
									if (typeof url == "function") url = url(row);
									else if (url != undefined) url = url.replace("{eid}", eid);
									var width = operator["width"];
									var height = operator["height"];
									var tip = operator["tip"];
									if (typeof tip == "function") tip = tip(row);

									var link = $("<a />").text(text).attr("href", "#").addClass("button_2");
									if (css != undefined) link.addClass(css);
									if (!enable) link.addClass("button_2_disabled");
									if (tip) link.attr("title", tip);

									switch (type) {
										case "confirm":
											link.click(function() {
												if ($(this).hasClass("button_2_disabled")) return false;
												if (confirm(info)) {
													$.post(callback, { "eid": eid }, function() {
														list();
													});
												}
												return false;
											});
											break;
										case "redirect":
											link.click(function() {
												if ($(this).hasClass("button_2_disabled")) return false;
												document.location.href = callback + "?id=" + eid;
												return false;
											});
											break;
										case "callback":
											link.click(function() {
												if ($(this).hasClass("button_2_disabled")) return false;
												if (confirmext) {
													var dlgConfirm = $("#dlg_confirm");
													dlgConfirm.dialog("open").find(".info").text(confirmText);
													$(".submit", dlgConfirm).one("click", function() {
														callback(eid);
														dlgConfirm.dialog("close");
													});
												}
												else callback(eid);
												return false;
											});
											break;
										case "iframe":
											link.click(function() {
												if ($(this).hasClass("button_2_disabled")) return false;
												$("h1", dlgIframe).text(text);
												$(".loading_1", dlgIframe).height(height).show();
												$("iframe", dlgIframe).attr("src", url + "&inner=1").css({
													"width": "100%",
													"height": height
												});
												dlgIframe
													.dialog("option", "width", width)
													.dialog("open");
												return false;
											});
											break;
										case "delete":
											break;
										case "modify":
											break;
									}
									otd.append(link);
								}
							});
							otd.wrapInner($("<span />").addClass("wrap"));
							tr.append(otd);
						}
						table.append(tr);
					});

					// check top checkbox when selected rows more than one
					if ($("td.checkbox input:not(:disabled)").size() == 0) topCheckbox.prop("disabled", true);
					else if (hasCheckbox) topCheckbox.prop("checked", $("td.checkbox input:not(:checked):not(:disabled)", table).size() == 0);
				}
				else table.append($("<tr class='none' />").append($("<td colspan='100' />").html("无记录")));

				$(".button_2, .text_wrap", table).tooltip({
					track: true,
					content: function() {
						return $(this).attr("title");
					}
				});

				$("tr:even", table).addClass("even");
				paging.html(createpaging(settings.pageIndex, pageCount, entityCount)).show();
				table.show();

				// hide actions columns when whithout actions
				if ($("td.action a", table).size() == 0) $("th.action, td.action", table).hide();
				else  $("th.action", table).show();

				// show search and multi action bar
				$.each(settings.actionDisabledFields, function(index, field) {
					$("#search_" + field, mainSearch).prop("disabled", true);
				});

				$.each(settings.searchDefaultValues, function(key, value) {
					$("#search_" + key, mainSearch).val(value);
				});
				if (settings.showSearchBar) $(mainSearch).slideDown("fast");

				$(multiActions).slideDown("fast");
				window.setTimeout(function() {
					$(document).trigger("list", ret);
				}, 500);

			}, "json");

			updateMultiAction();
		};

		var updateMultiAction = function() {
			$(".selected strong", multiActions).text(checkedRow.length);

			if (checkedRow.length > 0) $(".button_1", multiActions).removeClass("button_1_disabled");
			else $(".button_1", multiActions).addClass("button_1_disabled");
		};

		var initSelection = function() {
			if (typeof settings.selects == "undefined") return false;
			if (settings.selects.length <= 0) return false;

			selectsParams = $.extend({}, settings.selectsParams);
			$.post("/" + controller + "/selects", selectsParams, function(entities) {
				var i = 0;
				$.each(entities, function(index, entity) {
					var select = settings.selects[i];
					$.each(entity, function(id, value) {
						$(".main_search #search_" + select).append($("<option />", {
							value: value[select],
							text: value.name
						}));
						
						$("#dlg_new #" + select).append($("<option />", {
							value: value[select],
							text: value.name
						}));
					});
					var selected = $("#dlg_new #" + select).attr("default");
					$("#dlg_new #" + select + " option[value=" + selected + "]").attr("selected", true);
					$(".main_search #search_" + select + " option[value=" + selected + "]").attr("selected", true);
					i++;
				});

				$.each(settings.searchDefaultValues, function(key, value) {
					$("#search_" + key, mainSearch).val(value);
				});
			}, "json");

			return true;
		};

		var resetForm = function(title) {
			$("[disabled]", dlgNew).removeAttr("disabled");
			$("label.error_1", dlgNew).remove();
			dlgValidate.resetForm();
			$(".error_1", dlgNew).removeClass("error_1");
			dlgNew.removeAttr("eid");
			$("form", dlgNew)[0].reset();
			$("input", dlgNew).removeAttr("checked");
			$("ul.error", dlgNew).html("");
			$(".button_1", dlgNew).removeClass("button_1_disabled");
			$("h1", dlgNew).html(title);
		};

		$("<span/>").attr("title", "刷新页面").addClass("refresh").appendTo(mainActions).click(function() {
			if ($("form", mainSearch).size() > 0) $("form", mainSearch)[0].reset();
			searching = false;
			settings.pageIndex = 1;
			listParams = {};
			list();
			btnSearchClear.addClass("button_1_disabled");
		});

		btnSearchClear.addClass("button_1_disabled");
		var dlgConfirm = $(
			'<div id="dlg_confirm" class="dialog_1">\
				<h1>确认</h1>\
				<p class="info"></p>\
				<div class="actions">\
					<a href="#" id="submit" class="button_1 button_submit submit">确定</a>\
					<a href="#" class="button_1 button_1_a close">取消</a>\
				</div>\
					<a href="#" class="close header_close"></a>\
			</div>');
		body.prepend(dlgConfirm);
		dlgConfirm.dialog({ autoOpen: false });
		$(".close", dlgConfirm).click(function() {
			$(".submit", dlgConfirm).unbind("click");
		});

		var dlgMessage = $(
			'<div id="dlg_message" class="dialog_1">\
				<h1></h1>\
				<p class="info"></p>\
				<div class="actions">\
					<a href="#" id="submit" class="button_1 button_1_a close">确定</a>\
				</div>\
				<a href="#" class="close header_close"></a>\
			</div>'
		);
		body.prepend(dlgMessage);

		var dlgDel = $(
			'<div id="dlg_del" class="dialog_1">\
				<h1>删除</h1>\
				<p class="info">确定要删除吗？</p>\
				<div class="actions">\
					<a href="#" id="submit" class="button_1 button_1_a submit">确定</a>\
					<a href="#" class="button_1 button_1_a close">取消</a>\
				</div>\
				<a href="#" class="close header_close"></a>\
			</div>'
		);
		body.prepend(dlgDel);

		var dlgIframe = $(
			'<div id="dlg_iframe" class="dialog_1">\
				<h1></h1>\
				<div class="content">\
					<div class="loading_1"></div>\
					<iframe frameborder="0"></iframe>\
				</div>\
				<div class="actions">\
					<a href="#" class="button_1 button_1_a close">关闭</a>\
				</div>\
					<a href="#" class="close header_close"></a>\
			</div>');
		body.prepend(dlgIframe);
		$("iframe", dlgIframe).on("load", function() {
			$(this).prev().hide();
		});

		// processing, diabled all buttons
		$(".button_1, .button_2, .paging_1 a").on("click", function(e) {
			if ($("body").hasClass("disabled")) e.stopImmediatePropagation();
			return false;
		});

		// add valid for new and modify dialog
		if (!$.isEmptyObject(settings.validateMessages) && !$.isEmptyObject(settings.validateRule)) {
			dlgValidate = $("form", dlgNew).validate({
				errorClass: "error_1",
				errorPlacement: function(error, element) {
					error.appendTo(element.parent("td").next("td"));
				}, rules: settings.validateRule, messages: settings.validateMessages
			});
		}

		$(".dialog_1").dialog({
			autoOpen: false,
			modal: true,
			width: settings.dialogWidth,
			show: {
				effect: 'fade',
				duration: 200
			},
			resizable: false,
			hide: {
				effect: 'fade',
				duration: 100
			}
		}).find(".close").click(function() {
				$(this).closest(".dialog_1").dialog("close");
				return false;
			});
		dlgNew.dialog("option", "width", settings.modifyDialogWidth);

		// add enter key to inputs of search bar
		$("input", mainSearch).keypress(function(e) {
			if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
				btnSearch.click();
				return false;
			}
			return true;
		});

		// add enter key to inputs of add or modify windows
		$(".dialog_1 *").keypress(function(e) {
			if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
				$(this).closest(".dialog_1").find(".submit").click();
				return false;
			}
			return true;
		});

		btnSearch.click(function() {
			var hasValue = false;
			$(":text, :file, :checkbox, select, textarea", mainSearch).each(function() {
				if($(this).val() != "" && $(this).val() != 0) {
					hasValue = true;
					return false;
				}
				return true;
			});

			if (!hasValue) {
				$(":text, :file, :checkbox, select, textarea", mainSearch).not(".disabled").eq(0).focus();
				return false;
			}

			searching = true;
			listParams = $(this).closest("form").serializeObject();
			settings.pageIndex = 1;
			list();
			btnSearchClear.removeClass("button_1_disabled");
			return false;
		});

		btnSearchClear.click(function() {
			if ($(this).hasClass("button_1_disabled")) return false;

			$(":text, :file, :checkbox, select, textarea", mainSearch).not(".disabled").eq(0).focus();
			$("form", mainSearch)[0].reset();
			if (searching) {
				searching = false;
				settings.pageIndex = 1;
				listParams = {};
				list();
				$(document).trigger("clearSearch");
			}
			$(this).addClass("button_1_disabled");

			return false;
		});

		// add new record button
		btnAdd.click(function(e) {
			var addAction = function () {
				$(".checklist span").removeClass("checked");
				$(".datepicker", dlgNew).hide();
				$(".ke-container", dlgNew).closest("td").css("padding", "5px 0");
				$.extend(true, dlgValidate.settings, { rules: settings.validateRule });
				resetForm("新建" + settings.category);
				dlgNew.dialog("open");
				$.each(settings.newDisabledFields, function(index, field) {
					$("#" + field, dlgNew).prop("disabled", true);
				});
				$.each(settings.newDefaultValues, function(key, value) {
					$("#" + key, dlgNew).val(value).trigger("change");
				});
				$("input, select, textarea", dlgNew).eq(0).select();
			};
			if (typeof settings.addLoad == "function") {
				settings.addLoad(addAction);
			} else {
				addAction();
			}
			return false;
		});

		// delete record button
		table.on("click", ".btn_delete", function() {
			if ($(this).hasClass("button_2_disabled")) return false;

			var eid = $(this).closest("tr").attr("eid");
			$("h1:eq(0)", dlgDel).html("删除" + settings.category);
			dlgDel.attr("eid", eid).dialog("open");

			return false;
		});

		// delete record submit button
		$(".submit", dlgDel).click(function() {
			var self = $(this);
			if (self.hasClass("gray")) return false;

			self.addClass("gray");

			var eid = dlgDel.attr("eid");
			deleteParams = $.extend({ eid: eid }, settings.deleteParams);
			$.post("/" + controller + "/delete", deleteParams, function() {
				dlgDel.dialog("close");
				list();
				self.removeClass("gray");
			});
			return false;
		});

		// modify record button
		table.on("click", ".btn_modify", function() {
			if ($(this).hasClass("button_2_disabled")) return false;

			$(".checklist span").removeClass("checked");
			$(".datepicker", dlgNew).hide();
			$(".ke-container", dlgNew).css("margin-left", "5px").closest("td").css("padding", "5px 0");

			$.each(settings.modifyUnvalidateRules, function(index, rule) {
				$("form #" + rule, dlgNew).rules("remove");
			});

			resetForm("修改" + settings.category);
			var eid = $(this).closest("tr").attr("eid");
			dlgNew.attr("eid", eid);
			$.each(settings.modifyDisabledFields, function(index, field) {
				$("#" + field, dlgNew).prop("disabled", true);
			});
			$(".button_1", dlgNew).removeClass("gray");

			var ajaxGet = function() {
				getParams = $.extend({ eid: eid }, settings.getParams);
				$.post("/" + controller + "/get", getParams, function(data) {
						$.each(settings.modifyStructure, function(index, struct) {
						if (struct.indexOf("[") == 0 && data[index]) {
							var values = data[index].split(",");
							$.each(values, function(id, value) {
								$("input[value='" + value + "']", dlgNew).prop("checked", true).next().addClass("checked");
							});
						}
						else if (struct.indexOf("#") == 0) {
							$("input[name='" + index + "'][value='" + data[index] +"']", dlgNew).prop("checked", true);
						}
						else $("#" + index, dlgNew).val(data[index]);
					});

					if (typeof(data["_disable_fields"]) != "undefined") {
						$.each(data["_disable_fields"], function(index, field) {
							$("#" + field, dlgNew).prop("disabled", true);
						});
					}

					dlgNew.dialog("open");
					$("input, select, textarea", dlgNew).not(":hidden").not(":disabled").eq(0).select();
				}, "json");
			};
			if (typeof settings.modifyLoad == "function") settings.modifyLoad(eid, ajaxGet);
			else ajaxGet();

			return false;
		});

		$(".checklist input").not("legend input").click(function() {
			if ($(this).is(":checked")) $(this).next().addClass("checked");
			else $(this).next().removeClass("checked");
		});

		$(".checklist legend input").click(function() {
			var options = $(this).closest("fieldset").find("li");
			if ($(this).is(":checked")) {
				options.find("input").prop("checked", true);
				options.find("span").addClass("checked");
			}
			else {
				options.find("input").prop("checked", false);
				options.find("span").removeClass("checked");
			}
		});

		// new or modify record submit button
		$(".submit", dlgNew).click(function() {
			var self = $(this);
			if (self.hasClass("button_1_disabled")) return false;

			var eid = dlgNew.attr("eid");
			var form = $("form", dlgNew);
			if (form.valid()) {
				self.addClass("button_1_disabled");

				var values = $("form", dlgNew).serialize();
				$(":disabled", dlgNew).each(function() {
					values += "&" + $(this).attr("name") + "=" + $(this).val();
				});
				values += eid ? "&eid=" + eid : "";

				$("input, select, textarea, .checklist", dlgNew).prop("disabled", true);
				$.each(settings.newParams, function(key, value) {
					values += "&" + key + "=" + value;
				});
				$.post("/" + controller + "/entity", values, function(ret) {
					if (ret.code != undefined && ret.code != 1) {
						var error = {};
						error[ret.id] = ret.message;
						dlgValidate.showErrors(error);
						$("#" + ret.id, dlgNew).select();
						$("input, select, textarea, .checklist", dlgNew).prop("disabled", false);
						self.removeClass("button_1_disabled");
						return;
					}
					dlgNew.dialog("close");
					list();
				});
			}
			else $("input:eq(0)", dlgNew).select();
			return false;
		});

		if ($(".datetime_picker", dlgNew).length > 0) {
			$(".datetime_picker", dlgNew).appendDtpicker({
				"animation": false,
				"locale": "cn"
			});
		}

		// paging
		paging.on("click", "a", function() {
			if ($(this).hasClass("disabled") || $(this).hasClass("current")) return false;

			var searchParams = $(".main_search form").serializeObject();
			var params = {};

			$.each(searchParams, function(key, value) {
				params[key.replace("search_")] = value;
			});

			if ($(this).hasClass("prev")) settings.pageIndex--;
			else if ($(this).hasClass("next")) settings.pageIndex++;
			else settings.pageIndex = parseInt($(this).text());

			list();

			return false;
		});

		mainActions.slideDown("fast");
		initSelection();
		list();

		return {
			list: list,
			checkedRow: function() { return checkedRow; },
			clearCheckedRow: clearCheckedRow,
			showMessage: showMessage
		};
	};
})(jQuery);

var createpaging = function(page, pagecount, entityCount) {
	if (page > pagecount && pagecount != 0) page = pagecount;
	var paging = "";
	var last = page - 1;
	if (page == 1) {
		last = 1;
	}
	var next = page + 1;
	if (page == pagecount) {
		next = pagecount;
	}
	if (pagecount > 1) {
		if (page == 1) {
			last = "<a href='#' class='disabled prev'>上一页</a>";
		} else {
			last = "<a href='#' class='prev'>上一页</a>";
		}
		if (page >= pagecount) {
			next = "<a href='#' class='disabled next'>下一页</a>";
		} else {
			next = "<a href='#' class='next'>下一页</a>";
		}
		if (pagecount <= 10 && pagecount > 1) {
			for (var i = 1; i <= pagecount; i++) {
				if (i == page) {
					paging = paging + "<a class='current'  href='#' >" + i + "</a>";
				} else {
					paging = paging + "<a  href='#'>" + i + "</a>";
				}
			}
		} else if (pagecount > 10) {
			var p = parseInt(page / 10);
			var p2 = parseInt(pagecount / 10);

			var total = 10 * p + 10;
			if (p == p2) total = pagecount;
			for (i = 10 * p - 1; i <= total; i++) {
				if (i > 0) {
					if (i == page) paging = paging + "<a class='current'  href='#'>" + i + "</a>";
					else paging = paging + "<a  href='#'>" + i + "</a>";
				}
			}
			if (total < pagecount) paging = paging + "<span>···</span>";
		}
		paging = last + paging + next;
	}
	if (paging != "") paging = "共 " + entityCount + " 条&nbsp;&nbsp;" + paging;
	else paging = "共 " + entityCount + " 条";
	return paging;
};

if (!Array.prototype.indexOf) {
	Array.prototype.indexOf = function(elt)
	{
		var len = this.length >>> 0;

		var from = Number(arguments[1]) || 0;
		from = (from < 0)
			? Math.ceil(from)
			: Math.floor(from);
		if (from < 0)
			from += len;

		for (; from < len; from++)
		{
			if (from in this &&
				this[from] === elt)
				return from;
		}
		return -1;
	};
}

$.fn.serializeObject = function() {
	var o = {};
	var a = this.serializeArray();
	$.each(a, function() {
		if (o[this.name] !== undefined) {
			if (!o[this.name].push) {
				o[this.name] = [o[this.name]];
			}
			o[this.name].push(this.value || '');
		} else {
			o[this.name] = this.value || '';
		}
	});
	return o;
};


$(function() {
	$(document).ajaxStart(function() {
		$("div.loading").fadeIn("fast");
		$("body").addClass("disabled");
	}).ajaxStop(function() {
			$("div.loading").fadeOut("fast");
			$("body").removeClass("disabled");
		}).ajaxComplete(function(e, response) {
			if (response.getResponseHeader('session_timeout') == 1) {
				document.location.href = "/";
			}
		});

	$(window).resize(function() {
		var inner = $("body").hasClass("inner_body");
		$(".main_content").height($(window).height() - (inner ? 5: 117));
	}).resize();

	var ie7 = (navigator.userAgent.match(/msie [7]/i));
	if (ie7) {
		$(".dialog_1 tr td:last-child").width(1).css("padding", 0);

		$(".dialog_1 .textbox_1").width(function() {
			return $(this).width() + 10;
		});
	}

	$(".tip_1").tooltip({
		content: function() {
			return $(this).attr("title");
		}
	});

	$(".main_search select").on("change", function() {
		$(this).attr("title", $("option[value='" + $(this).val() + "']", this).text()).tooltip({
			track: true,
			content: function() {
				return $(this).attr("title");
			}
		});
	});
});