<script type="text/javascript">
	$(document).ready(function() {
		//var current = $("." + "<?php $paths = explode('/', Request::path()); echo $paths[0]; ?>");
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
		array('department manager user app', '基础管理',
			array(
				'部门管理' => '/department',
				'管理员管理' => '/manager',
				'用户管理' => '/user',
				'用户信息管理' => '/userinfo',
				'应用管理' => '/app',
				'密钥自动分配管理' => '/autoassign'
			),
		),
		array('tag', '标签管理',  array('标签管理' => '/tag')),
		array('sensitive', '敏感词管理',  array('敏感词管理' => '/sensitive')),
		array('ad', '广告管理', array('广告管理' => '/ad')),
		array('article articlecategory', '文章管理',
			array(
				'文章分类' => '/articlecategory',
				'文章管理' => '/article'
			)
		),
		array('soft', '软件管理',
			array(
				'软件管理' => '/soft',
				'软件记录' => '/softlog'
			)
		),

		array('knows', '资源共享',
			array(
				array('knows knowscategory answer', '问答管理', array('问答分类' => '/knowscategory', '问答列表' => '/knows')),
				array('faq faqcategory', 'FAQ管理', array('FAQ分类' => '/faqcategory', 'FAQ列表' => '/faq')),
				array('document documentcategory', '文档管理', array('文档分类' => '/documentcategory', '文档列表' => '/document')),
				'讲座管理' => '/meeting',
			)
		),
		array('report', '举报管理',
			array(
				'举报文档' => '/reportdocument',
				'举报提问' => '/reportquestion',
				'举报回答' => '/reportanswer',
				'举报讲座评论' => '/reportmeetingcomment',
			)
		),
		array('key', '激活管理',
			array(
				'商品管理' => '/product',
				'商品权限管理' => '/productpermission',
//				'用户商品包管理' => '/productpkg',
				'密钥管理' => '/key',
				'激活码管理' => '/exchangecode',
				'部门激活分配' => '/departmentkeyassign',
				'用户激活分配' => '/keyassign',
				'用户激活情况' => '/keyusage',
			)
		),
		array('chartkeyusage chartsoft', '图表查看',
			array(
				'密钥总量' => '/chartkeycount',
				'激活分配' => '/chartkeyassign',
				'用户激活情况' => '/chartkeyusage',
				'注册用户' => '/chartuser',
				'商品激活' => '/chartproductactivate',
				'激活错误' =>'/charterror',
				'软件情况' => '/chartsoft'
			)
		),
		array('helpcategory helpedit' , '帮助中心',
			array(
				'帮助中心分类' => '/helpcategory',
				'帮助中心内容' => '/helpedit',
			)
		),
	)); ?>
	<div class="clear"></div>
</div>