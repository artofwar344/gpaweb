$(function() {
	var old = captchaimg = null;
	if ($(".captchaimg").length == 0 && $(".row_captcha").length > 0) {
		$("#captcha").focus(function() {

			$("<img />").hide().addClass("captchaimg")
				.attr("alt", "请输入验证码")
				.attr("src", "/captcha?" + String(Math.random()).substr(2, 5))
				.css("position", "absolute")
				.appendTo($("input[name=captcha]").closest("td"));
			$(".captchaimg").mousedown(function() {
				old = $(".captchaimg").attr("src");
				$(".captchaimg").attr("src", old.substr(0, old.indexOf("?") + 1) + String(Math.random()).substr(2, 5)).show();
			});
			$(".row_captcha").find("input").unbind("focus");
		});
	}

	$(".row_captcha").find("input").attr("autocomplete", "off");
	$(".row_captcha").find("input").focus(function() {
		$(".captchaimg").show();
	});
});