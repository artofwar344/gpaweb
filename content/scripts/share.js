$(function() {
	$(".table_1").on("mouseenter", "tr", function() {
		$(this).addClass("hover");
	}).on("mouseleave", "tr", function(){
			$(this).removeClass("hover");
		});

	$(".dialog_1").keydown(function(event){
		if(event.keyCode == 13) {
			event.preventDefault();
			$(".submit", this).click();
			return false;
		}
		return true;
	});

	$(".dialog_1 .close").click(function() {
		$(this).closest(".dialog_1").dialog("close");
		return false;
	});

	$(".pagination").find(".disabled, .active").click(function() {
		return false;
	});
});

function addToFav(url, title) {
	if (!url) url = window.location;
	if (!title) title = document.title;
	var browser = navigator.userAgent.toLowerCase();
	if (window.sidebar) window.sidebar.addPanel(title, url, ""); // Mozilla, Firefox, Netscape
	else if (window.external) // IE or chrome
		if (browser.indexOf('chrome') == -1) window.external.AddFavorite(url, title); // IE
		else alert("您的浏览器不支持点击收藏，\n请按快捷键 Ctrl+D 收藏GP资源共享!"); // chrome
	else if (window.opera && window.print) return true; // Opera - automatically adds to sidebar if rel=sidebar in the tag
	else if (browser.indexOf('konqueror') != -1) alert("您的浏览器不支持点击收藏，\n请按快捷键 Ctrl+B 收藏GP资源共享!"); // Konqueror
	else if (browser.indexOf('webkit') != -1) alert("您的浏览器不支持点击收藏，\n请按快捷键 Ctrl+B (Command+B) 收藏GP资源共享!"); // safari
	return true;
}