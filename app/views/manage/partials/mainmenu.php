<script type="text/javascript">
	$(document).ready(function() {
		var current = $("." + "<?php $paths = explode('/', Request::path());echo $paths[0]; ?>");
		var nav = $(".main_nav");
		$("li", nav).hover(function() {
			$(this).children("ul").slideDown("fast");
		}, function() {
			$(this).children("ul").hide();
		});
	});
</script>
<div class="main_nav">
	<?php echo HtmlExt::htmlNavigate(array(
		array('base', '基础管理',array('客户管理' => '/customer', '高级管理员管理' => '/adminer', '敏感词管理' => '/sensitive', '激活错误代码' => '/activationerror')),
		array('softcategory soft softversion', '软件管理', array('软件分类' => '/softcategory', '软件列表' => '/soft', '版本审核' => '/softversion', '审核历史' => '/softversionhistory')),
		array('customerstatistics', '统计', array('客户统计' => '/customerstatistics')),
	)); ?>
	<div class="clear"></div>
</div>