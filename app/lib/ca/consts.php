<?php
namespace Ca;

class Consts {
	public static $app_name = '正版软件管理与服务平台';//BaseController中的sitename
	public static $ca_version = '1.5.1';
	public static $icp = '京ICP备12014130号-3';
	public static $cusotmer_alias = array('cernet', 'jzhj');//用户别名（未用）
	public static $product_status = array(1 => '可用', 2 => '禁用', 3 => '关闭');//产品状态
	public static $manager_status_texts = array(1 => '正常', 2 => '禁用');//管理员状态
	public static $manager_role_texts = array( //顶级管理员权限
		'base' => array(
			'name' => '基础',
			'list' => array(
				'[manager]' => '管理员管理',
				'[department]' => '部门管理',
				'[user]' => '用户管理',
				'[userinfo]' => '用户信息管理',
				'[user.new]' => '添加用户',
				'[app]' => '应用管理',
				'[ad]' => '广告管理',
				'[autoassign]' => '密钥自动分配管理',
			)
		),

		'key' => array(
			'name' => '密钥',
			'list' => array(
				'[product]' => '商品管理',
				'[key]' => '密钥管理',
				'[exchangecode]' => '激活码管理',
				'[departmentkeyassign]' => '部门激活分配',
				'[keyassign]' => '用户激活分配',
				'[keyusage]' => '用户激活情况',
				'[productpkg]' => '用户商品包管理',
			)
		),

		'chart' => array(
			'name' => '图表',
			'list' => array(
				'[chartkeyusage]' => '[图表]激活情况',
				'[chartkeycount]' => '[图表]密钥总量',
				'[chartkeyassign]' => '[图表]激活分配',
				'[chartproductactivate]' => '[图表]产品激活',
				'[charterror]' => '[图表]激活错误',
				'[chartuser]' => '[图表]用户数量',
			)
		),


		'soft' => array(
			'name' => '软件下载',
			'list' => array(
				'[articlecategory]' => '文章分类',
				'[article]' => '文章管理',
				'[soft]' => '软件管理',
				'[softlog]' => '软件记录',
			)
		),
		'share' => array(
			'name' => '资源共享',
			'list' => array(
				'[documentcategory]' => '文档分类',
				'[document]' => '文档管理',
				'[knowscategory]' => '问答分类',
				'[knows]' => '问答管理',
				'[meeting]' => '座谈信息管理',
				'[tag]' => '标签管理',
				'[sensitive]' => '敏感词管理',
				'[report],[reportdocument],[reportquestion],[reportanswer],[reportmeetingcomment]' => '举报管理'
			)
		),
		'help' => array(
			'name' => '帮助中心',
			'list' => array(
				'[helpcategory]' => '帮助中心分类',
				'[helpedit]' => '帮助中心内容',
			)
		),

	);
	public static $submanager_role_texts = array( //未用
		'base' => array(
			'name' => '基础管理',
			'list' => array(
				'[department]' => '部门管理',
				'[manager]' => '管理员管理',
				'[ad]' => '广告管理',
				'[user]' => '用户管理',
				'[user.new]' => '添加用户',
				'[app]' => '应用管理',
			)
		),
		'key' => array(
			'name' => '激活管理',
			'list' => array(
				'[product]' => '商品管理',
				'[key]' => '密钥查看',
				'[exchangecode]' => '激活码管理',
				'[keyusage]' => '用户激活情况',
				'[keyassign]' => '用户激活分配',
				'[departmentkeyassign]' => '部门激活分配',
			)
		),
		'chart' => array(
			'name' => '图表',
			'list' => array(
				'[chartkeyusage]' => '激活情况',
				'[chartkeycount]' => '密钥总量',
				'[chartkeyassign]' => '激活分配',
				'[chartproductactivate]' => '产品激活',
				'[charterror]' => '激活错误',
				'[chartuser]' => '用户数量',
			)
		),
		'help' => array(
			'name' => '帮助中心',
			'list' => array(
				'[helpcategory]' => '帮助中心分类',
				'[helpedit]' => '帮助中心内容',
			)
		),
	);

	public static $permission_group_texts = array( //用在PermissionService
		'base' => '基础管理',
		'key' => '激活管理',
		'share' => '资源共享',
		'soft' => '软件下载',
		'chart' => '图表',
		'help' =>'帮助中心',
	);

	public static $wsus_status_texts = array(1 => '待审批', 2 => '通过', 3 => '未通过');//审批状态 Wsus_Controller
	public static $managekey_status_texts = array(1 => '待审批', 2 => '不同意', 3 => '同意分配');// 请求状态
	public static $keyusage_status_texts = array(1 => '开始激活', 2 => '激活成功', 3 => '激活失败', 4 => '激活重置', 5 => '激活完成');//密钥请求状态
	public static $app_type_texts = array(1 => '执行文件', 2 => '嵌入网页');//应用管理类型
	public static $app_status_texts = array(1 => '可用', 2 => '禁用');//应用管理状态
	public static $soft_status_texts = array(1 => '可用', 2 => '禁用');//软件管理状态
	public static $article_status_texts = array(1 => '可用', 2 => '禁用');//文章管理状态
	public static $version_status_texts = array(1 => '待审核', 2 => '未通过审核', 3 => '通过审核');//软件审核状态
	public static $adminer_status_texts = array(1 => '正常', 2 => '锁定');//高级管理员状态(能否正常登录后台)
	public static $customer_status_texts = array(1 => '正常', 2 => '锁定');//客户管理状态
	public static $database_status_texts = array(1 => '正常', 2 => '未创建');//客户数据状态

	public static $have_my_document = array(13);		// 可以使用“个人中心 -> 我的文档” 功能的用户ID

	public static $adminer_role_texts = array( //高级管理员权限
		'base' => array(
			'name' => '基础管理',
			'list' => array(
				'[customer]' => array(
					'客户管理',
					array('[createdatabase]', '[customersetting]', '[keyassign]', '[customertopmanager]') // 包含权限
				),
				'[adminer]' => '管理员管理',
				'[sensitive]' => '敏感词管理',
			)
		),
		'soft' => array(
			'name' => '软件管理',
			'list' => array(
				'[softcategory]' => array(
					'软件分类',
					array('[softsubcategory]') // 包含权限
				),
				'[soft]' => '软件列表',
				'[softversion]' => '版本审核',
				'[softversionhistory]' => '审核历史',
			)
		),
		'statistics' => array(
			'name' => '统计',
			'list' => array(
				'[customerstatistics]' => '客户统计',
			)
		),
		'help' => array(
			'name' => '帮助中心',
			'list' => array(
				'[helpcategory]' => '帮助中心分类',
				'[helpedit]' => '帮助中心内容',
			)
		),
	);
	public static $user_status_texts = array(1 => '正常', 2 => '锁定', 3 => '待审核');//用户登录状态
	public static $user_from_texts = array(1 => '本地注册', 2 => '接口注册');//用户注册方式
	public static $soft_top_categories = array(//软件所属类型
		1 => '网络工具',
		2 => '系统工具',
		3 => '应用工具',
		4 => '联络聊天',
		5 => '图形图像',
		6 => '其他软件'
	);

	public static $soft_licensetype_texts = array( //软件授权类型
		1 => '免费',
		2 => '共享',
		3 => '商业'
	);

	public static $soft_language_texts = array( //软件语言
		1 => '简体中文',
		2 => '英文'
	);

	public static $ad_targets = array( //打开方式
		1 => '是',
		2 => '否'
	);

	public static $softtype_type_texts = array( //软件记录
		1 => '下载',
		2 => '更新',
		3 => '卸载'
	);

	public static $soft_type_texts = array( //软件所属板块
		1 => '推荐下载',
		2 => '装机必备',
		3 => '常用软件'
	);

	public static $article_type_texts = array( // 文章类型
		1 => '热点新闻',
		2 => '今日视点'
	);

	public static $soft_bits = array( //软件位数
		1 => '32位',
		2 => '64位'
	);

	public static $category_name = array( //文档分类
		'edu' => '课件专区',
		'pro' => '专业资料',
		'form' => '应用文书'
	);

	public static $document_publish_texts = array( //文档发布状态
		1 => '私有',
		2 => '等待审核',
		3 => '审核通过'
	);

	public static $document_status_texts = array( //文档状态
		1 => '成功',
		2 => '删除',
		3 => '处理中',
		4 => '处理失败'
	);

	public static $document_type_texts = array( //文档显示模块
		1 => '推荐',
	);

	public static $question_status_texts = array( //问答状态（是否显示在页面）
		1 => '正常',
		2 => '禁用'
	);

	public static $product_download = array( // 未用
		1 => 'windows7',
		2 => 'windows7',
		3 => 'office2010',
		4 => 'office2010',
		5 => 'windows8',
		6 => 'windows8',
		7 => 'office2013',
		8 => 'office2013',
	);

	public static $module_texts = array( //隶属网站 广告、文章管理
		'1' => '资源共享',
		'2' => '软件中心',
		'3' => '激活中心',
	);
	public static $module_alias = array( //隶属网站别名
		'1' => 'share',
		'2' => 'soft',
		'3' => 'activate',
	);

	public static $anchor_target = array( //打开方式 （广告）
		'_blank' => '新窗口',
		'_self' => '当前窗口',
		'_parent' => '父框架',
		'_top' => '整个窗口'
	);
	public static $ad_status_text = array( //广告状态 (是否显示)
		'1' => '可用',
		'2' => '禁用'
	);

	public static $meeting_status_texts = array( //讲座状态
		'1' => '可用',
		'2' => '禁用'
	);
	public static $meeting_active_texts = array( // 讲座进行状态
		'0' => '已结束',
		'1' => '报名中'
	);

	public static $switch_text = array( //未用
		'1' => '开启',
		'0' => '关闭'
	);

	public static $user_type_text = array( //用户类型
		'1' => '未知',
		'2' => '本科生',
		'3' => '教职工',
		'4' => '研究生',
	);

	public static $user_activated_text = array( //未用
		'0' => '禁止',
		'1' => '正常',
	);

	public static $user_isonline_text = array( //未用
		'0' => '离线',
		'1' => '在线',
	);

	public static $report_status_texts = array( //举报处理情况
		'1' => '未处理',
		'2' => '已禁用',
		'3' => '驳回',
	);

	public static $report_type_texts = array( //举报内容类型
		'1' => '文档',
		'2' => '提问',
		'3' => '回答',
		'4' => '评论',
	);

	public static $report_document_reason_texts = array( //举报文档原因
		'0' => '侵权',
		'1' => '广告或垃圾信息',
		'2' => '色情、淫秽、低俗信息',
		'3' => '反政府、反人类、反社会等反动信息',
		'4' => '散布赌博、暴力、凶杀、恐怖或者教唆犯罪等信息',
		'5' => '侮辱、诽谤等人身攻击信息',
		'6' => '散布谣言、扰乱社会秩序，破坏社会稳定等信息',
	);

	public static $report_reason_text = array( //举报知识问答原因
		'1' => '广告或垃圾信息',
		'2' => '色情、淫秽、低俗信息',
		'3' => '反政府、反人类、反社会等反动信息',
		'4' => '散布赌博、暴力、凶杀、恐怖或者教唆犯罪等信息',
		'5' => '侮辱、诽谤等人身攻击信息',
		'6' => '散布谣言、扰乱社会秩序，破坏社会稳定等信息',
	);

	public static $dns_view_texts = array( //未用
		'ANY' => '所有',
		'CER' => '教育网',
		'CNC' => '联通',
		'TEL' => '移动',
	);

	public static $exchangecode_status_text = array( //激活码管理状态
		'1' => '未领取',
		'2' => '已领取',
		'3' => '已使用'
	);
	public static $autoassign_status_text = array( //密钥自动分配状态
		'1' => '可用',
		'2' => '禁用',
	);

}

class Customer { //实例化用户数据库

	public $alias;
	public $name;
	public $env;
	public $securekey;

	public static $instance = null;

	public function init()
	{
		$this->env = $env = app()->env;
		if (!empty($GLOBALS['customer_alias']) && array_key_exists($env, $GLOBALS['customer_alias']))
		{
			$this->alias = $GLOBALS['customer_alias'][$env];
		}

		if (!empty($GLOBALS['customer_name']) && array_key_exists($env, $GLOBALS['customer_name']))
		{
			$this->name = $GLOBALS['customer_name'][$env];
		}

		if (!empty($GLOBALS['customer_securekey']) && array_key_exists($env, $GLOBALS['customer_securekey']))
		{
			$this->securekey = $GLOBALS['customer_securekey'][$env];
		}
	}

	public static function instance()
	{
		if (static::$instance == null)
		{
			static::$instance = new static;
			static::$instance->init();
		}
		return static::$instance;
	}

}

class ProductStatus { //产品状态
	const available = 1;
	const disabled = 2;
}

class UserStatus { //用户状态
	const normal = 1;
	const locked = 2;
	const pending = 3;
}

class UserKeyStatus { //密钥申请状态
	const pending = 1;
	const disagree = 2;
	const agree = 3;
}

class KeyUsageStatus { //密钥激活状态
	const begin_activate = 1;
	const activation_success = 2;
	const activation_failed = 3;
	const activation_reset = 4;
	const activation_complete = 5;
}

class SoftLogType { //软件记录类型
	const download = 1;
	const upgrade = 2;
	const uninstall = 3;
}

class SoftType {
	const recommend = 1; //推荐下载
	const indispensably = 2; //装机必备
	const popular = 3; //常用软件
}

class SoftVersionStatus { //审核状态
	const pending = 1;
	const disagree = 2;
	const agree = 3;
}

class ArticleType {
	const hot = 1; //热点新闻
	const viewpoint = 2; //今日视点
}

class SoftStatus { //软件状态
	const available = 1;
	const disabled = 2;
}

class DocumentSource { //文档来源
	const upload = 1;//上传
	const favorite = 2;//收藏
}

class DocumentType { //文档类型
	const root = null;
	const file = 1;
	const folder = 2;
	const attachment = 3; //附件
}

class DocumentPublish { //发布状态
	const private_d = 1;
	const public_d = 2;
	const submit_d = 3; //审核通过
}

class DocumentStatus {
	const normal = 1;
	const deleted = 2;
	const converting = 3;  //转换中
	const convertfailed = 4; //转换失败
}

class QuestionStatus { //提问状态
	const normal = 1;
	const deleted = 2;
	const closed = 3;
}

class AnswerStatus { //问答状态
	const normal = 1;
	const deleted = 2;
	const best = 3;
}

class DocumentShowType { //文档显示类型
	const Commended = 1;
}

class UserType { //用户类型
	const Unknow = 1;
	const Student = 2;
	const Teacher = 3;
}

class MeetingStatus { //讲座状态
	const normal = 1;
	const closed = 2;
}

class MessageStatus { //信息状态
	const unread = 1;
	const read = 2;
}

class MessageType {
	const getNewAnswer = 1;  //新的回答
	const updateQuestion = 2; //问题补充
	const moreAnswer = 3; //追加提问、回答
	const acceptAnswer = 4; //回答采纳
	const questionClosed = 5; //问题被禁用

}

class ReportType { //举报类型
	const document = 1;
	const question = 2;
	const answer = 3;
	const comment = 4;
}

class ReportStatus { //举报处理结果
	const pending = 1;
	const disabled = 2;
	const reject = 3;
}

class TopicStatus { //专题状态（是否显示）
	const normal = 1;
	const disabled = 2;
}

class AnswerType { //举报问答类型
	const normal = 1;
	const askMore = 2;
	const answerMore = 3;
}


class AdStatus { //广告状态
	const available = 1;
	const disabled = 2;
}

class CommentStatus { //评论状态
	const normal = 1;
	const disabled = 2;
}

class CommentType { //评论类型
	const meeting = 1;
}

class ExchangecodeStatus { //激活码状态
	const unassgined = 1;
	const assgined = 2;
	const used = 3;
}

class AutoAssignStatus { //自动分配状态
	const available = 1;
	const disabled = 2;
}
