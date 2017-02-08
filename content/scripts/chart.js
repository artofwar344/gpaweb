var _action = window.location.pathname.split("/")[1];
var _searching = false;
var _listdata = null;

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

$(document).ajaxStart(function() {
	$("div.loading").show();
}).ajaxStop(function() {
		$("div.loading").hide();
	});

var initSelection = function() {
	if (_selects.length <= 0) return false;
	$.post("/" + _action + "/selects", function(entities) {
		var i = 0;
		$.each(entities, function(index, entity) {
			var select = _selects[i];
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
			i++;
		});

		$(".main_search").slideDown("fast");

	}, "json");
	return true;
};

var list = function(params) {
	$.post("/" + _action + "/list", params, function(ret) {
		var option = $.extend(true, {}, _chartOption);
		switch (ret.type){
			case 'pie':
				break;
			default:
				var series = ret.series;
				$.each(series, function(index, serie) {
					series[index]["data"] = $.map(serie["data"], parseFloat);
				});
				option.xAxis.categories = ret.categories;
				break;
		}
		_listdata = ret.data;
		option.series = ret.series;
		option.title.text = ret.title;
		option.subtitle.text = ret.subtitle;
		new Highcharts.Chart(option);
	}, "json");
};

$(function() {
	var actions = $(".main_actions");
	actions.slideDown("fast");

	initSelection();
	list();

	$(".main_search").on("click", ".button_clear", function() {
		$(".main_search input").eq(0).select();
		$(".main_search form")[0].reset();
		if (_searching) {
			list();
			_searching = false;
		}

		return false;
	});

	$(".main_search .button_search").click(function() {
		var hasValue = false;
		$(".main_search").find(":text, :file, :checkbox, select, textarea").each(function() {
			if ($(this).val() != "" && $(this).val() != 0) {
				hasValue = true;
				return false;
			}
		});

		if (!hasValue) {
			$(".main_search input:first").select();
			return false;
		}

		_searching = true;
		var params = $(this).closest("form").serializeObject();
		list(params);
		return false;
	});

	$(".main_actions .button_export").click(function() {
		window.location.href = "/" + _action + "/export";
		return false;
	});
});
