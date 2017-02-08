(function($){
	$.categorySelector = function(container, settings) {
		this.container = container;
		this.$container = $(container);
		this.settings = {
			styleType: 1,
			categories: [],
			currentCategory: 0,
			styleClass: "category_selector",
			baseCss: { width: "100%", height: "120px"}
		};
		$.extend(true, this.settings, settings);
		this.build();

	};

	$.categorySelector.prototype = {
		selectedCategory: null,
		selectedParent: null,
		subCategoryBlock: null,
		build: function() {
			var that = this;
			var currentCategory = that.settings.currentCategory;
			that.selectedCategory = currentCategory;
			if (currentCategory != '0' && currentCategory != null) {
				$.each(that.settings.categories, function(i, category) {
					if (category["categoryid"] == currentCategory) {
						that.selectedParent = category['parentid'];
					}
				});
			}

			var ul_lvl0 = $("<ul/>").attr({ "id": "lvl0" });
			$.each(that.settings.categories, function(i, category) {
				if (category["parentid"] == null) {
					var li = $("<li/>")
						.attr({ "cid": category["categoryid"] })
						.append($("<a/>").text(category["name"]));
					if (category["categoryid"] == that.selectedParent) {
						li.addClass("selected");
					}
					ul_lvl0.append(li);
				}
			});
			var ul_lvl1 = $("<ul/>").attr({ "id": "lvl1" });
			var clear = $("<div/>").addClass("clear");
			that.subCategoryBlock = ul_lvl1;
			switch (that.settings.styleType) {
				case 1:
				default:
					if (!that.$container.hasClass(that.settings.styleClass)) {
						that.$container.addClass(that.settings.styleClass)
					}
					that.$container.css(that.settings.baseCss);
					that.$container.append(ul_lvl0, ul_lvl1, clear);
					that.clickCategoryEvent();
					break;
				case 2:
					var nameContainer = $("<ul/>").addClass("value");
					var name_lvl0 = $("<li/>").attr({ "id": "name_lvl0"});
					var name_lvl1 = $("<li/>").attr({ "id": "name_lvl1"});

					if (that.settings.currentCategory == null || that.settings.currentCategory == '0') {
						name_lvl0.html("点击选择分类");
					} else {
						$.each(that.settings.categories, function(i, category) {
							if (category["categoryid"] == currentCategory) {
								name_lvl1.html("&nbsp;&gt;&nbsp;" +  category["name"]);
							}
						});
						$.each(that.settings.categories, function(i, category) {
							if (category["categoryid"] == that.selectedParent) {
								name_lvl0.html(category["name"]);
							}
						});
						that.updateSubCategory(that.selectedParent);
					}
					nameContainer.append(name_lvl0, name_lvl1);

					var selector = $("<div/>").addClass("selector").hide();
					selector.append(ul_lvl0, ul_lvl1, clear);
					this.$container.append(nameContainer, selector, clear);
					this.clickNameContainerEvent();
					this.clickCategoryEvent();
					var $container = this.$container;
					$("body").click(function(event) {
						if ($.contains($container[0], event.target) == false) {
							$container.find(".selector").hide();
							$container.removeClass("select_category_expaned");
						}
					});
					break;
			}
		},
		updateSubCategory: function(parentid) {
			var that = this;
			that.subCategoryBlock.empty();
			$.each(this.settings.categories, function(i, category) {
				if (category["parentid"] == parentid) {
					var li = $("<li/>")
						.append(
							$("<a/>")
								.css({ "background-image": "none"})
								.text(category["name"])
						)
						.click(function() {
							if ($(this).hasClass("selected")) return false;
							$(this).addClass("selected");
							$(this).siblings().removeClass("selected");
							that.selectedCategory = category["categoryid"];
							$("#category_id").val(category["categoryid"]);
							if (that.settings.styleType == 2) {
								$("#name_lvl1").html("&nbsp;&gt;&nbsp;" + category["name"]);
							}
							return false;
						});
					if (that.selectedCategory == category["categoryid"]) {
						li.addClass("selected");
					}
					that.subCategoryBlock.append(li).show();
				}
			});
		},
		clickCategoryEvent: function() {
			var that = this;
			$("#lvl0 li", this.container).on("click",function() {
				if ($(this).hasClass("selected")) return false;
				var parentid = $(this).attr("cid");
				$(this).addClass("selected");
				$(this).siblings().removeClass("selected");
				that.updateSubCategory(parentid);
				that.selectedParent = parentid;
				that.selectedCategory = null;
				$("#category_id").val(0);
				if (that.settings.styleType == 2) {
					$("#name_lvl0").text($(this).text());
					$("#name_lvl1").text("");
				}
				return false;
			});
		},
		clickNameContainerEvent: function() {
			var that = this;
			$(".value", this.$container).click(function() {
				that.$container.addClass("select_category_expaned");
				var selector = $(this).next(".selector");
				if (selector.is(":visible")) {
					selector.hide();
				} else {
					selector.children("ul").hide();
					var ul = $("ul#lvl0", selector);
					selector.width(ul.width() * 2 + 5);
					ul.show();
					if (that.selectedCategory != 0 && that.selectedCategory != null) {
						$("ul#lvl1", selector).show();
					}
					selector.show();
				}
			});
		}

	};

	$.fn.categorySelector = function(settings) {
		return new $.categorySelector(this, settings);
	}
})(jQuery);
