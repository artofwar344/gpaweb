<?php
class ChinaBankConfig // 网银在线配置信息
{
	public static $Mid = "22392574";
	public static $NotifyUrl = "order/paynotify";
	public static $ReturnUrl = "order/payresult";
	public static $Key = "fladjkflksadjf3940394f8f8e3";
}
class AlipayConfig // 支付宝配置信息
{
	public static $Partner = "2088701785958145";
	public static $SecurityCode = "cs5md0krvlj843st6v0svgz14juewx9n";
	public static $SellerEmail = "jzhj_cw@cernet.com";

	public static $InputCharset = "utf-8";
	public static $Transport = "http";

	public static $NotifyUrl = "order/paynotify";
	public static $ReturnUrl = "order/payresult";
	public static $ShowUrl = "list";

	public static $SignType = "MD5";
	public static $Antiphishing = 0;
}
class MsEA
{
	public static $APIUrl = "https://e5webservices.onthehub.com/OrderWebService.asmx?wsdl";
	public static $AccountNumber = 100023522;
	public static $Key = "1e52314f";

	public static $Offering = array(
		27 => "98eec9b3-038e-df11-ad8b-0030487d8897",
		28 => "bc6e4ab6-1c0d-e011-bed1-0030487d8897",
		29 => "c06e4ab6-1c0d-e011-bed1-0030487d8897",
		30 => "e8c28900-bb8b-df11-ad8b-0030487d8897",
		218 => "c677e5d8-171f-e211-a76f-f04da23e67f6" //Win8Pro
	);
}
class MsKIS
{
	public static $UserName = 'FAREAST\cernet01';
	public static $Password = "xK_maaliskuu$317";
	public static $APIUrl = "https://pakeyinfoext.one.microsoft.com/KeyInfoServiceEX/KeyInfoWebService.asmx?wsdl";
}
class MailSenderA
{
	public $Host = "mail.shop.edu.cn";
	public $UserName = "studenthero.ch@shop.edu.cn";
	public $Password = "cdream@micro";
	public $From = "studenthero.ch@shop.edu.cn";
	public $FromName = "校园先锋";
}
class MailSenderB
{
	public $Host = "mail.shop.edu.cn";
	public $UserName = "studenthero@shop.edu.cn";
	public $Password = "cernet@#!KJAG62";
	public $From = "studenthero@shop.edu.cn";
	public $FromName = "校园先锋新春有礼！";
}
class Constants
{
	public static $SiteMainTitle = "赛尔校园先锋官方网站_千万学子成长服务平台（shop.edu.cn）独家教育特惠"; // 网站主标题
	public static $SiteKeyword = "cs6,windows8,windows8下载,苹果电脑,iphone5,acrobat下载,macbook pro,macbook air,windows8中文版,Office2013,正版软件"; // 网站通用关键词
	public static $SiteDescription = "校园先锋是赛尔网络面向高校师生推出的正版软件特惠购买平台，中国高校师生均可以优惠价格购买windows8，苹果产品，Office 2013，photoshop cs6，macbook air，macbook pro,adobe acrobat等正版软件及Apple学生优惠产品，并享受高质量的下载及售后服务";

	public static $AdminPageSize = 10; // 后台每页显示数量
	public static $AppId = 1; // 站点Id (ste主站[ste.shop.edu.cn]的Id为1)
	public static $Debug = false; // 切换调试模式
	public static $UserFakeIdNumber = true; // 使用通用身份证
	public static $FakeIdNumbers = array("510104199706320521"); // 通用身份证, 填入此身份证均直接通过
	public static $StockAlarmAmount = 30; // 库存报警量, 低于该数量即报警
	public static $CookiesEncryptKey = "shop_cookies"; // cookies值加密key
	public static $UploadFileSizeLimit = 1; // 上传限制为1mb
	public static $UploadFileCountLimit = 7; // 上传限制7张照片
	public static $EsaleFirstInOrderTotal = 2000; // 第一次进货总量
	public static $EsaleOpenRegister = false; // 是否开放注册esale
	public static $ESDIntro = "• <strong>即买即用</strong><br />• 包含电子密钥一个（多平台商品为两个），通过注册邮箱发送给用户<br />• 客户端软件需要下载";
	public static $ShrinkIntro = "• <strong>需要预订</strong><br />• 包含电子密钥一个（多平台商品为两个），通过注册邮箱发送给用户<br />• 彩盒包装内含客户端软件光盘（不包含密钥）<br />• 彩盒包装从Adobe发货到用户指定地址有可能超过30天";

	public static $VoucherRate = 10; // 1 = 10%, 10 = 100% //抽奖活动中奖几率

	public static $AdobeLicenseKey = "adobe"; // adobe 商品密钥加密key

	public static $MsLicenseKey = "GgIfyBtKRkg="; // 微软密钥加密Key
	public static $MsLicenseIV = "98765432109="; // 微软商品密钥加密IV

	public static $MonitorParams = array("a", "b", "c", "d", "e", "f", "g"); // 需要监控的url参数

	// 第三方接口用常量
	public static $Ms360LicenseKey = 'micr0$oft3@O'; // 微软密钥加密key (给360提供)

	// 每个用户最多通过国政通验证次数
	public static $MaxGztAuthTimes = 3;

	public static $AuthorizeLevel = 0; // 0： 需要验证 1：开放验证

	public static $MonitorEmarParams = array("src"=>"emar");

	public static $WeiboAKEY = 4038252314;
	public static $WeiboSKEY = "12a9fbda120d88d7466c07faca1f5239";
	public static $WeiboUids = array(2521990780, 1925534154, 2509471610); //

	public static $ICP = "京ICP备12014130号-1";		// ICP备案号

	public static $InvoiceTime = 90;		// 支付成功后，可以获取发票的最长天数

	public static $UserType = array( // 注册用户类型
		0 => "学生",
		1 => "新生",
		2 => "教师"
	);

	public static $SiteManagerRoles = array( // siteadmin后台管理员权限
		"高级管理" => array(
			"manager" => "管理员管理"
		),
		"用户管理" => array(
			"user" => "用户查询",
			"userauth" => "人工认证",
			"userauth2" => "校园协议人工认证",
//			"comment" => "评论管理"
		),
		//"商品管理" => array(
		//	"soft" => "软件商品",
		//	"book" => "图书商品",
		//	"peripheral" => "周边商品",
		//),
		"订单管理" => array(
			"order" => "订单查询",
			"orderupdate" => "订单修改",
			"resendlicense" => "补发密钥",
			"orderstatistics" => "订单统计",
			"purchaseuser" => "购买用户统计",
			"orderdeliver" => "彩盒装订单投递",
			"simplepackingdeliver" => "简包装订单投递",
			"bookorderdeliver" => "图书订单投递",
			"discorderdeliver" => "光盘订单投递",
			"peripheralorderdeliver" => "周边商品订单投递",
			"batchdelivery" => "批量投递"
		),
		"销售管理" => array(
			"salestatistics" => "销售统计",
			"salesdetail" => "销售明细",
			"salesdetailwithlicense" => "密钥查看",
			"abstock" => "AB库查询",
			"forecast" => "销售预测",
			"shwin8" => "SH Win8查询"
		),
		"发票管理" => array(
			"invoice" => "发票查询",
			"invoicedetail" => "发票明细"
		),
		"支付管理" => array(
			"reconciliation" => "订单对账",
			"reconciliationstatistics" => "订单对账统计"
		),
		"Esale管理" => array(
			"esaleprice" => "代理价修改",
			"esaleauth" => "代理认证",
			"esalecart" => "代理商购物车管理",
			"esaleorder" => "代理进货查询",
			"esaleucorder" => "代理统收销售",
			"esalestock" => "代理商库存管理",
		),
		"退货管理" => array(
			"esalereturned" => "退货管理",
		//	"tradereturned" => "线上销售退货管理"
		),
		"线下销售管理" => array(
			"akorderlist" => "线下AK销售查询",
			"linesalesbyas" => "线下支付AS订单管理"
		),
		"文章管理" => array(
			"helpcategory" => "帮助中心分类",
			"helparticle" => "帮助中心内容",
			"aboutuscategory" => "关于我们分类",
			"aboutusarticle" => "关于我们内容",
		),
		"产品管理" => array(
			"productseries" => "产品系列管理",
			"product" => "产品管理",
		),
		"广告数据" => array(
			"adstatistics" => "广告数据监控",
			"banner" => "首页Banner管理",
			"apple" => "苹果产品展示管理",
			"userpublicity" => "会员中心宣传文字",
			"activity" => "活动回顾列表",
			
		),
		"用户数据统计" => array(
			"activeuser" => "活跃用户数据统计",
			"register" => "注册数据统计",
			"register_sale" => "注册销量转化率统计",
		),
		"销售数据" => array(
			"statisticsbybrand" => "品牌销量统计",
			"statisticsbytype" => "产品类型销量统计",
			"statisticsbyordertype" => "销售途径销量统计",
			"ST_bytotal_discount" => "销售额与抵扣统计",
			"statisticsbybrand_money" => "品牌销售额统计",
			"statisticsbytype_money" => "产品类型销售额统计",
			"statisticsbyordertype_money" => "销售途径分类金额统计",
		)
	);

	public static $StockManagerRoles = array(
		"高级管理" => array(
			"manager" => "管理员管理"
		),
		"进货管理" => array(
			"instock" => "新进密钥",
			"inhistory" => "进货历史"
		),
		"密钥管理" => array(
			"stockdetail" => "查看库存",
			"license" => "密钥查询",
			"encryptlicensefile" => "加密密钥文件"
		),
		"代金券管理" => array(
			"coupongenerate" => "生成代金券",
			"couponbatch" => "代金券批次",
			"coupon" => "代金券查询"
		),
		"出货" => array(
			//"sqlexcute" => "sql执行管理",
			"shiponline" => "Esale进货支付信息提交",
			'deparment1' => '销售一部订单审核',
			'deparment2' => '销售二部订单审核',
//			"shipunderline" => "线下出货",
			"akorderlog" => "查看线下销售日志",
//			"" => ''
		),
		"微软库存管理" => array(
			"microsoftinstock" => "微软密钥入库",
			"stockcontrol" => "库存调整"
		),
		'无密钥商品库存管理' => array(
			'nokeyproductinstock' => '无密钥商品入库',
			'nokeyproductstock' => '无密钥商品库存管理'
		),
		"兑换码管理" => array(
			"addexchangecode" => "新增兑换码",
			"exchangeretrieve" => "注销兑换码",
		//	"searchexchangecode" => "查询兑换码"
		),
		"取货管理" => array(
			"customers" => "客户信息管理",
			//"customerslist" => "客户信息列表",
			"pickup" => "取货管理",
			"examine" => "取货审核"
		)
	);
	public static $Customer = array( // 客户可取产品类别
		0 => "win",
		1 => "office"
	);
	public static $PickupStatus = array( // 取货批量日志状态
		0 => "取货中",
		1 => "暂停",
		2 => "已完成",
		3 => "未审核",
		4 => "未通过",
		5 => "已通过",
		6 => "可补取"
	);
	public static $ProductPickId = array( // 客户可取产品类别
		30 => "win7",
		218 => "win8",
		528 => "win8.1",
		411 => "office 2013 plus",
		246 => "office 2010 plus",
		557 => 'Office:mac 2011'
	);
	public static $ProductOffer = array( // 客户可取产品类别    /*** 下列选项中，有新的Offerid，请注册旧的Offerid，并在新的OfferID后面写上更新日期。  ***/
		30 => "3c58c73d-b3d5-e111-9c76-f04da23fc522",    // win7
		218 => "b81870df-f01e-e211-a76f-f04da23e67f6",    // win8
		528 => "4764d00a-fa37-e311-93f6-b8ca3a5db7a1",    // win8.1
		411 => "6234303b-545e-e211-a88c-f04da23e67f4",    // Office 2013 PLUS
		246 => "5058c73d-b3d5-e111-9c76-f04da23fc522",    // Office 2010 PLUS
		557 => "d457c73d-b3d5-e111-9c76-f04da23fc522"     // Office:mac 2011
	);
	public static $AuthRejectReason = array( // 验证信息拒绝理由
		0 => "照片上传失败",
		1 => "照片不够清晰",
		2 => "无效师生证件",
		3 => "证件信息与填写信息不符"
	);

	public static $DegreeRejectReason = array( // 校园协议验证信息拒绝理由
		0 => "照片上传失败",
		1 => "照片不够清晰",
		2 => "无效证件，需上传学生证内页",
		3 => "证件信息与填写信息不符",
		4 => "不符合校园协议资格"
	);

	public static $userpublicityStatus = array(
		"0" => "未上线",
		"1" => "上线"
	);
	
	public static $UserRole = array( // 用户权限
		0 => "普通会员",
		1 => "待认证会员",
		2 => "待人工认证",
		3 => "认证会员",
		4 => "认证未通过"
	);

	public static $PayMethod = array( // 支付方式
		1 => "支付宝",
		2 => "网银在线",
		3 => "现金支付",
		4 => "赠送"
	);

	public static $DeliverCompany = array( // 递送公司
		0 => "申通",
		1 => "韵达",
		2 => "EMS",
		3 => "圆通",
		4 => "中通",
		5 => "顺丰"
	);


	public static $expressesIc=array (
		0 =>'shentong',//=> '申通快递（可能存在延迟）',
		1=>'yunda', //=> '韵达快递',
		2=>'ems' , //=> 'EMS快递',
		3=>'yuantong' , //=> '圆通快递',
		4=>'zhongtong' , //=> '中通快递',
		5=>'shunfeng' , //=> '顺丰快递'
	);

	public static $expressesAli=array (
		0 =>'STO',//=> '申通快递（可能存在延迟）',
		1=>'YUNDA', //=> '韵达快递',
		2=>'EMS' , //=> 'EMS快递',
		3=>'YTO' , //=> '圆通快递',
		4=>'ZTO' , //=> '中通快递',
		5=>'SF' , //=> '顺丰快递'
	);

	public static $expressWeb=array (
	0=>'http://www.sto.cn',//=> '申通快递（可能存在延迟）',
	1=>'http://www.yundaex.com', //=> '韵达快递',
	2=>'http://www.ems.com.cn' , //=> 'EMS快递',
	3=>'http://www.yto.net.cn' , //=> '圆通快递',
	4=>'http://www.zto.cn' , //=> '中通快递',
	5=>'http://www.sf-express.com' , //=> '顺丰快递'
);
	// 免运费商品
	public static $expressForFreeProductids = array(
		555,556
	);
	public static $DeliverMethod = array( // 投递方式
		0 => "自提",
		1 => "挂号",
		2 => "快递",
		3 => "平邮",
		4 => "EMS"
	);

	public static $DeliverCosts = array( // 费用
		0 => 0,
		1 => 5,
		2 => 10,
		3 => 5, //平邮
		4 => 25
	);

	public static $InvoiceTotal = array( // 发票订单价格
		0 => 0,
		1 => 5,
		2 => 10
	);

	public static $InvoiceDespatchModes = array( // 递送方式
		0 => "自提",
		1 => "挂号",
		2 => "快递"
	);

	public static $OrderTitle = array( // 订单打头字母
		0 => "AC", // 商品订单
		1 => "AZ", // 直销订单
		2 => "AB", // 发票订单
		3 => "AS", // esale 进货订单
		4 => "AE", // esale 销售订单
		5 => "AR",  // esale 退货订单
		6 => "AU",  // 统收销售订单
		7 => "AG",  // Gift 赠送订单
		8 => "AT",  // Gift 团购订单
		9 => "AC"  // 活动订单
	);

	public static $ProductType = array( // 产品类型
		1 => "ESD for Windows",
		2 => "ESD for Mac",
		3 => "ESD for Windows & Mac",
		4 => "Shrink for Windows",
		5 => "Shrink for Mac",
		6 => "Shrink for Windows & Mac"
	);

	public static $ProductTypes = array(
		1 => "软件商品",
		2 => "书籍商品",
		3 => "光盘商品",
		4 => "配件周边商品",
		5 => "活动商品A",//"免运费活动商品",
		6 => "活动商品B",//"不免运费活动商品",
	);

	public static $OrderStatus = array( // 订单状态
		0 => "未支付",
		1 => "已支付",
		3 => "已取消",
		4 => "已锁定", //锁定后不能支付
		5 => "待审核", 		// 商务提交订单支付信息，等待上级审核
		6 => "审核未通过",		// 上级审核未通过，退回给上午重新提交支付信息
		7 => "审核通过"		// 上级审核未通过，退回给上午重新提交支付信息
	);

	public static $Deparment = array(
		0 => '未分配',	//
		1 => '销售一部',	//
		2 => '销售二部',	//
	);

	public static $DeliveryStatus = array( // 商品投递状态
		0 => "未发货",
		1 => "已发货",
		2 => "已收货",
		3 => "已通知发货"
	);

	public static $InvoiceStatus = array( // 发票投递状态
		0 => "未开具",
		1 => "已发货",
		2 => "已收货",
		3 => "已开具",
	);
	public static $UserStatus = array( // 用户状态
		0 => "锁定",
		1 => "正常",
		2 => "未激活"
	);

	public static $AppIds = array( // 获取密钥的网站编号
		1 => "STE主站" // ste.shop.edu.cn
	);

	public static $Gender = array(
		0 => "男",
		1 => "女"
	);

	public static $CouponType = array(
		0 => "所有商品",
		1 => "指定商品"
	);

	public static $PaymentStatus = array(
		0 => "未对账",
		1 => "未收款",
		2 => "已收款",
		3 => "金额不匹配"
	);

	public static $ReturnStatus = array(
		0 => "等待退货",
		1 => "确认退货",
		2 => "拒绝退货",
	);

	public static $TradeReturnStatus = array(
		0 => "正在处理",
		1 => "退货完成",
		2 => "审批通过",
		3 => "审批未通过",
		4 => "退款成功",
	);

	public static $AllowPurchase = array(
		0 => "任何人",
		1 => "学生",
		2 => "教师和学生",
	);
	public static $ProductSeriesStatus = array(
		0 => "未发布",
		1 => "发布",
		2 => "发布但未上线",
		3 => "缺货"
	);
	public static $activityStatus = array(
		0 => '<span>（已结束）</span>',
		1 => '<span  style="color:red">（进行中）</span>',
		);

	public static $activityStatusadmin = array(
		0 => '已结束',
		1 => '进行中',
		);
	public static $OrderType = array(
		0 => "主站订单(AC)",
		1 => "兑换订单(AZ)",
//		2 => "发票订单(AB)",
//		3 => "代理商进货(AS)",
		4 => "代理商销售(AE)",
//		6 => "代理商统收(AU)",
		7 => "赠送订单(AG)",
		8 => "团购订单(AT)",
		9 => "活动订单(AC)",
		10 =>"线下直出(AK)"
);
	/*活动商品库存量设置(按照商品系列设置)*/
	public static $ActivitySeriesIdAndStock = array(
		113 => 8, //(22-12-2)
		114 => 10, // 5 + 8
		115 => 34,
		116 => 4,
		117 => 9,
		118 => 1,
		119 => 0, //44 - 44,
		120 => 6,
		121 => 8,
		122 => 1,
		123 => 2,
		124 => 12,
		125 => 2,
		126 => 4,
		144 => 10,
		145 => 10,
		146 => 10
	);
	public static $ActitityBeginTime = "2014-11-30";
	/*活动商品Id*/
	public static $ActivityProductId = array(500, 501, 502, 504, 505, 506, 507, 508, 509, 510, 511, 512, 513, 514, 515, 516, 517, 518, 519, 520, 521, 522, 523, 524, 525, 526, 527);

	public static $StockWarning = array(
		473 => 4,
		421 => 20,
		239 => 4,
		238 => 4,
		50 => 2,
		47 => 2,
		43 => 2,
		42 => 3,
		45 => 2,
		243 => 4,
		242 => 3,
		245 => 2,
		244 => 2,
		37 => 6,
		38 => 2,
		39 => 2,
		40 => 10,
		41 => 5,
		36 => 40,
		241 => 4,
		240 => 5,
		246 => 0,
		411 => 0,
		218 => 0,
		557 => 0,
		30 => 0,
		528 => 0,
		412 => 0,
		413 => 0,
		414 => 0,
		415 => 0,
		407 => 0,
		408 => 0,
		409 => 0,
		410 => 0,
		558 => 39

	);

	public static $sqlExcuteStatus = array(
		1 =>'未执行',
		2 =>'执行失败',
		3 =>'执行成功',
	);

	public static $commentStatus = array(
		0 => "审核中",
		1 => "审核通过",
		2 => "审核未通过",
		3 => "已删除",
	);

	public static $SurveySubjectType = array(
		0 => "单选",
		1 => "多选",
		2 => "填空",
	);

	public static $SurveySubjectTypeEN = array(
		0 => "radio",
		1 => "checkbox",
		2 => "text",
	);
	public static $SurveyStatus = array(
	0 => "未启用",
	1 => "多选",
	2 => "填空",
	);

	public static $feedbackType = array(
		0 => "优化与建议",
		1 => "活动建议",
		2 => "合作建议",
		3 => "订单问题",
		4 => "物流配送",
		5 => "退换货办理",
		6 => "其他",
	);

	public static  $EsaleRole = array(
		0 => "未验证",
		1 => "等待验证",
	//	2 => "冻结, 不能进货, 可以销售库存",
	//	3 => "不能使用esale功能",
		4 => "验证通过",
		5 => "特殊渠道",
		6 => "拒绝通过",
	);

	public static $UcRole = array(
		0 => "未开通",
		1 => "已开通",
	);

	public static $bannerStatus = array(
		"0" => "未上线",
		"1" => "上线"
	);
	public static $appleStatus = array(
		"0" => "未上线",
		"1" => "上线"
	);
	//apple展示商品类型

	public static $appleType = array(
		"0" => "Mac",
		"1" => "iPad",
		"2" => "iPhone",
		"3" => "iPod",
		"4" => "Watch"
	);
	public static $bannerType = array(
		"0" => "首页",
		"1" => "apple",
		"2" => "adobe",
		"3" => "microsoft",
		"4" => "配件周边商品",
		"5" => "学习图书",
		"6" => "首页底部apple",
		"7" => "首页底部microsoft",
		"8" => "首页底部adobe"
	);
	public static $aTarget = array(
		"_self" => "默认",
		"_blank" => "新窗口打开",
	);

	public static $esalecartNoProductId = array(
		0 => 31,
		1 => 32,
		2 => 33,
		3 => 34,
		4 => 35,
		5 => 101
	);
	/**
	 * 无密钥库存管理相关商品ID列表
	 * @var array
	 */
	public static $noKeyProductid = array(558);

	public static $ActiveMailHeader = "账号激活邮件 - 校园先锋";
	public static $ActiveMailBody = '<html>
										<body>
											<table style="font-size:14px; font-family:微软雅黑, Arial">
												<tr><td style="font-size:26px; font-weight:bold"><img src="{0}/images/logo.gif" alt="校园先锋" /></td></tr>
												<tr><td style="border:solid 1px #e6e6e5; padding:10px">
													<p>您好！</p>
													<p>　　感谢您加入校园先锋！您在{1}创建账号，请点击下面的链接完成账号激活。为保障您帐号的安全，该链接有效期为24小时，仅第一次点击有效。（如您点击后未实现账号激活可要求系统再次发送账号激活邮件）</p>
													<p style="text-align:center; font-size:20px; font-weight:bold"><a href="{2}">点此激活您的账户</a></p>
													<p>如果上述文字点击无效，请把下面网页地址复制到浏览器地址栏中打开： </p>
													<p>{2}</p>
													{4}
												</td></tr>
												<tr><td style="padding:10px 0">感谢您对校园先锋计划的支持！</td></tr>
												<tr><td style="padding-top:10px; color:#999b9e; border-top:solid 2px #e6e6e5">此为系统邮件，请勿回复<br />您在购买过程中有任何疑问，请查阅 <a href="{3}">帮助中心</a> 或联系客服电话：400-676-7170。</td></tr>
											</table>
										</body>
									</html>';
	public static $ActiveNewEmailHeader = "更换登陆邮箱 - 校园先锋";
	public static $ActiveNewEmailBody = '<html>
										<body>
											<table style="font-size:14px; font-family:微软雅黑, Arial">
												<tr><td style="font-size:26px; font-weight:bold"><img src="{0}/images/logo.gif" alt="校园先锋" /></td></tr>
												<tr><td style="border:solid 1px #e6e6e5; padding:10px">
													<p>您好！</p>
													<p>感谢您对校园先锋工作的支持！您在{1}申请更换登陆邮箱，请点击下面的链接激活新登陆邮箱的操作。为保障您帐号的安全，该链接有效期为24小时，仅第一次点击有效。（如您点击后未成功更换登陆邮箱可用原账号，登陆邮箱，再次重复之前的的更换邮箱操作。）</p>
													<p style="text-align:center; font-size:20px; font-weight:bold"><a href="{2}">点此激活您的新登陆邮箱</a></p>
													<p>如果上述文字点击无效，请把下面网页地址复制到浏览器地址栏中打开： </p>
													<p>{2}</p>
													{4}
												</td></tr>
												<tr><td style="padding:10px 0">感谢您对校园先锋计划的支持！</td></tr>
												<tr><td style="padding-top:10px; color:#999b9e; border-top:solid 2px #e6e6e5">此为系统邮件，请勿回复<br />您在购买过程中有任何疑问，请查阅 <a href="{3}">帮助中心</a> 或联系客服电话：400-676-7170。</td></tr>
											</table>
										</body>
									</html>';

	public static $ForgetPwdMailHeader = "找回密码邮件 - 校园先锋";
	public static $ForgetPwdMailBody = '<html>
										<body>
											<table style="font-size:14px; font-family:微软雅黑, Arial">
												<tr><td style="font-size:26px; font-weight:bold"><img src="{0}/images/logo.gif" alt="校园先锋 STUDENT HERO" /></td></tr>
												<tr><td style="border:solid 1px #e6e6e5; padding:10px">
													<p>亲爱的{1}同学，您好！</p>
													<p>感谢您加入校园先锋！您在{2}申请找回密码，请点击下面的链接重新设定密码。为了保障您帐号的安全，该链接有效期为24小时，仅第一次点击有效。</p>
													<p style="text-align:center; font-size:20px; font-weight:bold"><a href="{3}">点此进入密码修改页面</a></p>
													<p>如果上述文字点击无效，请把下面网页地址复制到浏览器地址栏中打开： </p>
													<p><a href="{3}">{3}</a></p>
												</td></tr>
												<tr><td style="padding:10px 0">感谢您对校园先锋的支持！</td></tr>
												<tr><td style="padding-top:10px; color:#999b9e; border-top:solid 2px #e6e6e5">此为系统邮件，请勿回复<br />您在购买过程中有任何疑问，请查阅 <a href="{4}">帮助中心</a> 或联系客服电话：400-676-7170。</td></tr>
											</table>
										</body>
									</html>';

	public static $LicenceMailHeader = "产品授权邮件 - 校园先锋";
	public static $LicenceMailBody = '<html>
										<body>
											<table style="font-size:14px; font-family:微软雅黑, Arial">
												<tr><td style="font-size:26px; font-weight:bold"><img src="{0}/images/logo.gif" alt="校园先锋" /></td></tr>
												<tr><td style="font-size:16px; padding:5px 0"><strong>提示：</strong>这是您加入赛尔校园先锋并购买软件密钥的依据，请您妥善保存此邮件，在您安装软件时将会使用邮件中的密钥。</td></tr>
												<tr><td style="border:solid 1px #e6e6e5; padding:10px">
													<p>亲爱的{2}用户,，您好！<br />感谢您加入赛尔校园先锋并购买产品，以下是您购买产品的详细信息： </p>
													<p><strong>产品名称:</strong> {3}</p>
													<p><strong>产品密钥:</strong> {4} <br />(请您妥善保管产品密钥，如果不慎丢失，您可以登录<a href="{1}">{1}</a>，进入会员中心→已购软件→点击“重发密钥”，密钥将重新发到您的注册邮箱中。)
													</br><strong>产品下载地址:</strong>{13}如有任何问题请致电: 400-676-7170 （周一至周五 8:30-17:15, 节假日除外），或发邮件至service@shop.edu.cn。</p>
													{12}
													<p style="border-bottom:solid 1px #e6e6e5"></p>
													<p>订单编号：{5}</p>
													<p>付款日期：{6}</p>
													<p style="border-bottom:solid 1px #e6e6e5"></p>
													<p>购买者姓名：{2}</p>
													<p>购买者帐号：{7}</p>
													<p>身份证号：{8}</p>
													<p>电话号码：{9}</p>
												</td></tr>
												<tr><td style="padding:10px 0">校园先锋的产品仅限于中国内地高校学生或教师群体购买、使用，不得进行出售、转让、拍卖等交易行为，如经查证有倒卖的现象，您购买产品的密钥将有可能被回收，我们保留追究其法律责任的权利。感谢您使用正版软件！</td></tr>
												<tr><td style="padding-top:10px; color:#999b9e; border-top:solid 2px #e6e6e5">此为系统邮件，请勿回复<br />您在购买过程中有任何疑问，请查阅 <a href="{10}">帮助中心</a> 或联系客服电话：400-676-7170 （周一至周五 8:30-17:15, 节假日除外）。</td></tr>
											</table>
										</body>
									</html>';
	public static $PresellMailHeader = "商品备货通知邮件 - 校园先锋";
	public static $PresellMailBody = '<html>
										<body>
											<table style="font-size:14px; font-family:微软雅黑, Arial">
												<tr><td style="font-size:26px; font-weight:bold"><img src="{10}/images/logo.gif" alt="校园先锋" /></td></tr>
												<tr><td style="border:solid 1px #e6e6e5; padding:10px">
													<p>亲爱的{1}用户，您好！</p>
													<p>您的订单已被记录，此商品正在备货中！</p>
													{11}
													<p style="border-bottom:solid 1px #e6e6e5"></p>
													<p>产品名称：{3}</p>
													<p>订单编号：{2}</p>
													<p>付款日期：{4}</p>
													<p style="border-bottom:solid 1px #e6e6e5"></p>
													<p>购买者姓名：{5}</p>
													<p>购买者帐号：{6}</p>
													<p>身份证号：{7}</p>
													<p>电话号码：{8}</p>
													<p style="border-bottom:solid 1px #e6e6e5"></p>
												</td></tr>
												<tr><td style="padding:10px 0">非常感谢您购买校园先锋的产品，同时感谢您对校园先锋的支持！</td></tr>
												<tr><td style="padding-top:10px; color:#999b9e; border-top:solid 2px #e6e6e5">此为系统邮件，请勿回复<br />您在购买过程中有任何疑问，请查阅 <a href="{9}">帮助中心</a> 或联系客服电话：400-676-7170 （周一至周五 8:30-17:15, 节假日除外），或发邮件至service@shop.edu.cn。</td></tr>
											</table>
										</body>
									</html>';
	public static $PD10PresellMailHeader = "Parallels Desktop 10 for Mac 教育版 商品备货通知邮件";
	public static $PD10PresellMailBody = '<html>
											<body>
												<table style="font-size:14px; font-family:微软雅黑, Arial">
												<tr><td style="font-size:26px; font-weight:bold"><img src="{10}/images/logo.gif" alt="校园先锋" /></td></tr>
												<tr>
													<td style="border:solid 1px #e6e6e5; padding:10px">
														<p>亲爱的{1}用户，您好！</p>
														<p>您的订单已被记录，此商品正在备货中！</p>
														<p>您所购买的“Parallels Desktop 10 for Mac 教育版”软件，目前正在备货中。该产品将在备货完毕后寄达，由此给您带来不便请谅解！</p>
													</td>
												</tr>
												<tr><td style="padding:10px 0">非常感谢您购买校园先锋的产品，同时感谢您对校园先锋的支持！</td></tr>
												<tr><td style="padding-top:10px; color:#999b9e; border-top:solid 2px #e6e6e5">此为系统邮件，请勿回复<br />您在购买过程中有任何疑问，请查阅 <a href="{9}">帮助中心</a> 或联系客服电话：400-676-7170 （周一至周五 8:30-17:15, 节假日除外），或发邮件至service@shop.edu.cn。</td></tr>
											</table>
											</body>
										</html>';
	public static $Win8PresellMailHeader = "商品备货通知邮件 - 校园先锋";
	public static $Win8PresellMailBody = '<html>
										<body>
											<table style="font-size:14px; font-family:微软雅黑, Arial">
												<tr><td style="font-size:26px; font-weight:bold"><img src="{10}/images/logo.gif" alt="校园先锋" /></td></tr>
												<tr><td style="border:solid 1px #e6e6e5; padding:10px">
													<p>亲爱的{1}用户，您好！</p>
													<p>您的订单已被记录，此商品正在备货中！</p>
													{11}
													<p style="border-bottom:solid 1px #e6e6e5"></p>
													<p>产品名称：{3}</p>
													<p>订单编号：{2}</p>
													<p>付款日期：{4}</p>
													<p style="border-bottom:solid 1px #e6e6e5"></p>
													<p>购买者姓名：{5}</p>
													<p>购买者帐号：{6}</p>
													<p>身份证号：{7}</p>
													<p>电话号码：{8}</p>
													<p style="border-bottom:solid 1px #e6e6e5"></p>
													<p>您的密钥预计将在7个工作日之内到货，届时请您通过注册邮箱收取密钥邮件。</p>
												</td></tr>
												<tr><td style="padding:10px 0">非常感谢您购买校园先锋的产品，同时感谢您对校园先锋的支持！</td></tr>
												<tr><td style="padding-top:10px; color:#999b9e; border-top:solid 2px #e6e6e5">此为系统邮件，请勿回复<br />您在购买过程中有任何疑问，请查阅 <a href="{9}">帮助中心</a> 或联系客服电话：400-676-7170 （周一至周五 8:30-17:15, 节假日除外），或发邮件至service@shop.edu.cn。</td></tr>
											</table>
										</body>
									</html>';
	public static $InsuranceMailHeader = "用户您好，恭喜您获得了电脑无忧意外损坏保护服务！";
	public static $InsuranceMailBody = '<html>
										<body>
											<table style="font-size:14px; font-family:微软雅黑, Arial">
												<tr><td style="font-size:26px; font-weight:bold"><img src="{0}/images/logo.gif" alt="校园先锋 STUDENT HERO" /></td></tr>
												<tr><td style="border:solid 1px #e6e6e5; padding:10px">
													<p>亲爱的用户：</p>
													<p>感谢您参与校园先锋计划并购买产品，以下是您购买产品的详细信息：</p>
													<p><strong>产品名称：</strong>电脑无忧意外损坏保护服务</p>
													<p><strong>产品激活码：</strong>{1} (请您妥善保管产品激活码，如果不慎丢失，您可以联系客服）</p>
													<p><strong>产品说明：</strong>本产品将在服务开启后6个月内，为您的电脑提供意外跌落、碰撞、进液以及突然断电、电器短路、其他电气原因导致电脑硬件损失的风险保障。对列明地址的电脑因外来并有明显痕迹的盗窃、抢夺或抢劫行为导致的损失，负责保障。</p>
													<p><strong>开启方法：</strong>请您直接访问产品活动页面: <a href="http://duba.ebao51.cn/xyxf.jsp" target="_blank">http://duba.ebao51.cn/xyxf.jsp</a>，输入产品激活码，完善个人资料便可安心使用电脑。</p>
													<p><strong>订单编号：</strong>{2}</p>
													<p><strong>订单日期：</strong>{3}</p>
													<p><strong>购买者姓名：</strong>{4}</p>
													<p><strong>购买者帐号：</strong>{5}</p>
													<p><strong>身份证号：</strong>{6}</p>
													<p><strong>电话号码：</strong>{7}</p>
												</td></tr>
												<tr><td style="padding:10px 0">我们拥有从产品销售、运营服务、售后理赔等在内的专业服务队伍和完善支撑体系，是国内为数不多的、具备整体解决方案的电脑保险服务提供商，丰富、专业的服务经验让用户在享受服务的过程中得到最优的用户体验。感谢您使用电脑无忧意外损坏保护服务。</td></tr>
												<tr><td style="padding-top:10px; color:#999b9e; border-top:solid 2px #e6e6e5">此为系统邮件，请勿回复<br />您在购买过程中有任何疑问，请查阅 <a href="{8}">帮助中心</a> 或联系客服电话：400-676-7170。</td></tr>
											</table>
										</body>
									</html>';
	public static $AppleAuthMailHeader = "Apple 教育优惠资格确认 - 校园先锋";
	public static $AppleAuthMailBody = '<html>
											<style type="text/css">
												body,ul,ol,li,p,h1,h2,h3,h4,h5,h6,form,fieldset,table,td,img,div{margin:0; padding:0; border:0; }
												body{ background:url({0}/images/appleauth/bg.jpg) no-repeat top center #e7e7e7; color:#333; font-size:12px; font-family:"黑体","SimSun","Arial Narrow"; }
												ul,ol{list-style-type:none; }
												select,input,img,select{vertical-align:middle; }
												a{color:#666; text-decoration:none; }
												a:hover,a:active,a:focus{color:#184280; text-decoration:underline; }
												.clear{clear:both}
												.fl{float:left}
												.fr{float:right}
												.index{background:url({0}/images/appleauth/index-bg.jpg) no-repeat top center; }
												.head{width:885px; margin:auto; height:76px; }
												.logo{background-image:url({0}/images/appleauth/logo.png); width:250px; height:55px; float:left; margin-top:10px; }
												.logo a{display:block; width:182px; height:30px; }
												.head-nav{float:right; background:url({0}/images/appleauth/ico.png) no-repeat left center; padding-left:20px; margin-top:35px; display:none}
												.bottom{width:782px; margin:auto; padding:15px 0px; overflow:hidden}
												.dyzc{background-image:url({0}/images/appleauth/sbg.gif); padding:30px 10px 35px; }
												.dy-btn{text-align:center; padding-top:65px; }
												.dy-btn input{background-image:url({0}/images/appleauth/btn.png); width:60px;height:23px; line-height:23px; border:0px; font-weight:bold; cursor:pointer; color:#fff}
												.cont{width:801px; overflow:hidden; margin:auto}
												.cont-top{background-image:url({0}/images/appleauth/cont-top.gif); width:801px; height:7px; overflow:hidden}
												.cont-mid{width:799px; overflow:hidden; border-left:1px solid #cc0000; border-right:1px solid #cc0000; background-color:#fff; }
												.cont-foot{background-image:url({0}/images/appleauth/cont-foot.gif); width:801px; height:6px; overflow:hidden}
											</style>
											<body>
												<div class="head"><div class="logo"></div><div class="head-nav"><b></b> </div></div>
												<div class="cont">
													<div class="dyzc" style=" height:315px;font-size: 14px;line-height: 40px; padding:35px 40px 35px;">
														<p>亲爱的用户:</p>
														<p>感谢您访问我们的网站 ! </p>
														您已成功完成学生身份验证，可立即享受 Apple 教育优惠政策，并可立即以教育优惠价格购买 Apple 商品。
														<p>如果您再次登录我们的网站，只需输入您已通过验证的邮箱 <span style="color:blue;">{1}</span> 。</p>
														<p>您也可以拨打 Apple 客服电话：400-666-8800 咨询或验证购买 Apple 教育优惠产品。</p>
														<p>欢迎您再次登录！</p>
													</div>
												</div>
												<div class="bottom">
													<span class="fl">您可随时选择订阅或退订，如需了解更多的用户隐私政策，请参见<a href="{2}">链接</a>。</span><br />
													<p style="font-size:10px; color:#999999" > Apple Inc.为Apple商标的所有人</p>
												</div>
											</body>
											</html>';
	public static $VoucherMailHeader = "
	";
	public static $VoucherMailBody = '<html>
										<body>
											<table style="font-size:14px; font-family:微软雅黑, Arial">
												<tr><td style="font-size:26px; font-weight:bold"><img src="{0}/images/logo.gif" alt="校园先锋 STUDENT HERO" /></td></tr>
												<tr><td style="font-size:16px; padding:5px 0"><strong>提示：</strong>这封邮件是您参与校园先锋计划相关活动的依据，请妥善保存此邮件，您购买Windows7专业完整版软件时将会使用邮件中的代金券串号。</td></tr>
												<tr><td style="border:solid 1px #e6e6e5; padding:10px">
													<p>亲爱的用户：</p>
													<p>感谢您一直以来对校园先锋的支持。祝贺您幸运抽中价值为<strong style="color:#f00">900</strong>元的代金券。</p>
													<p style="font-size:16px"><strong>代金券串号为: </strong>{1}</p>
													<p><strong>优惠详情：</strong>使用此优惠券链接购买Windows 7专业完整版仅需<strong style="color:#f00">399</strong>元。</p>
													<p><strong>使用方法：</strong>请您登录校园先锋网站，进入购买页面{2}，选择购买Windows 7专业完整版，在订单确认页的下方会出现“<strong>使用代金券抵消部分金额</strong>”。点开后填入相应信息，提交代金券即可抵消部分产品购买金额。</p>
													<p>您可以自己使用该代金券，也可把该代金券送给亲友使用。</p>
													<p style="border-bottom:solid 1px #e6e6e5"></p>
													<p><strong>提醒：</strong>该代金券仅能使用一次，有效期自本邮件发出起<strong style="color:#f00">30</strong>日内有效，请您尽快使用。本代金券仅能用来购买Windows 7专业完整版。</p>
													<p>如需了解更多关于产品信息，您可以<a href="{3}" target="_blank">点此查看帮助网页</a>或与我们的客服人员取得联系，客服电话：400-676-7170</p>
													<p>再次感谢您对校园先锋的支持。让我们一起早日免受盗版软件所带来的风险和危害。</p>
												</td></tr>
												<tr><td style="padding-top:10px; color:#999b9e; border-top:solid 2px #e6e6e5">此为系统邮件，请勿回复<br />您在购买过程中有任何疑问，请查阅 <a href="{3}">帮助中心</a> 或联系客服电话：400-676-7170。</td></tr>
											</table>
										</body>
									</html>';
	public static $MsvolagreementMailHeader = "请您核实并提交微软公司正版软件许可确认书";
	public static $MsvolagreementMailBody = '<html>
										<body>
											<table style="font-size:14px; font-family:微软雅黑, Arial">
												<tr><td style="font-size:26px; font-weight:bold"><img src="{0}/images/logo.gif" alt="校园先锋 STUDENT HERO" /></td></tr>
												<tr><td style="border:solid 1px #e6e6e5; padding:10px">
													<p>亲爱的{1}同学，您好！ ：</p>
													<p>感谢您参与赛尔校园先锋并购买微软产品，以下是您购买产品的详细信息：</p>
													<p style="font-size:16px">产品名称: {4}</p>
													<p style="color:#f00">请核实并提交许可确认书，以便工作人员尽快向您递送所购软件。</p>
													<p>请登录 <a href="{2}">{2}</a> 核实许可确认书。</p>
													{5}
													<p>感谢您对赛尔校园先锋的支持！</p>
													<p style="border-bottom:solid 1px #e6e6e5"></p>
													<p>如需了解更多关于产品信息，您可以<a href="{3}" target="_blank">点此查看帮助网页</a>或与我们的客服人员取得联系，客服电话：400-676-7170</p>
													<p>再次感谢您对校园先锋的支持。让我们一起早日免受盗版软件所带来的风险和危害。</p>
												</td></tr>
												<tr><td style="padding-top:10px; color:#999b9e; border-top:solid 2px #e6e6e5">此为系统邮件，请勿回复<br />您在购买过程中有任何疑问，请查阅 <a href="{3}">帮助中心</a> 或联系客服电话：400-676-7170。</td></tr>
											</table>
										</body>
									</html>';
	public static $Win8volagreementMailHeader = "赛尔校园先锋-微软公司正版软件许可确认书";
	public static $Win8volagreementMailBody = '<html>
										<body>
											<table style="font-size:14px; font-family:微软雅黑, Arial">
												<tr><td style="font-size:26px; font-weight:bold"><img src="{0}/images/logo.gif" alt="校园先锋 STUDENT HERO" /></td></tr>
												<tr><td style="border:solid 1px #e6e6e5; padding:10px">
													<p>亲爱的{1}同学，您好！ ：</p>
													<p>感谢您参与赛尔校园先锋并购买微软产品，以下是您购买产品的详细信息：</p>
													<p style="font-size:16px">产品名称: {4}</p>
													<p>请登录 <a href="{2}">{2}</a> 在线查看许可确认书。</p>
													{5}
													<p>感谢您对赛尔校园先锋的支持！</p>
													<p style="border-bottom:solid 1px #e6e6e5"></p>
													<p>如需了解更多关于产品信息，您可以<a href="{3}" target="_blank">点此查看帮助网页</a>或与我们的客服人员取得联系，客服电话：400-676-7170</p>
													<p>再次感谢您对校园先锋的支持。让我们一起早日免受盗版软件所带来的风险和危害。</p>
												</td></tr>
												<tr><td style="padding-top:10px; color:#999b9e; border-top:solid 2px #e6e6e5">此为系统邮件，请勿回复<br />您在购买过程中有任何疑问，请查阅 <a href="{3}">帮助中心</a> 或联系客服电话：400-676-7170。</td></tr>
											</table>
										</body>
									</html>';
	public static $GiftCouponMailHeader = "恭喜您，获得校园先锋在线商店30元代金劵。";
	public static $GiftCouponMailBody = '<html>
										<body>
											<table style="font-size:14px; font-family:微软雅黑, Arial">
												<tr><td style="font-size:26px; font-weight:bold"><img src="{0}/images/logo.gif" alt="校园先锋 STUDENT HERO" /></td></tr>
												<tr><td style="font-size:16px; padding:5px 0"><strong>提示：</strong>这封邮件是您参与校园先锋计划相关活动的依据，请妥善保存此邮件，您购买软件时将会使用邮件中的代金券串号。</td></tr>
												<tr><td style="border:solid 1px #e6e6e5; padding:10px">
													<p>亲爱的用户：</p>
													<p>欢迎加入校园先锋俱乐部。您在校园先锋新生节活动中获得了价值为<strong style="color:#f00">30</strong>元的代金券。</p>
													<p style="font-size:16px"><strong>代金券串号为: </strong>{1}</p>
													<p><strong>优惠详情：</strong>使用此优惠券购买校园先锋在线商店的全部软件产品时可以抵值30元现金使用。</p>
													<p><strong>使用方法：</strong>请您登录校园先锋在线商店(<a href="{2}" target="_blank">{2}</a>)，选择购买您需要的微软或Adobe公司的正版软件，在订单确认页的下方会出现“<strong>使用代金券抵消部分金额</strong>”。点开后填入相应信息，提交代金券即可抵消部分产品购买金额。</p>
													<p>您可以自己使用该代金券，也可把该代金券送给亲友使用。</p>
													<p style="border-bottom:solid 1px #e6e6e5"></p>
													<p><strong>提醒：</strong>该代金券仅能使用一次，2012年10月31日前有效，逾期将不能使用。本代金券可使用范围为校园先锋在线商店所有软件类产品，不包括Apple公司的硬件及图书频道、配件频道等产品。</p>
													<p>如需了解更多关于产品信息，您可以<a href="{3}" target="_blank">点此查看帮助网页</a>或与我们的客服人员取得联系，客服电话：400-676-7170</p>
													<p>再次感谢您对校园先锋的支持。让我们一起早日免受盗版软件所带来的风险和危害。</p>
												</td></tr>
												<tr><td style="padding-top:10px; color:#999b9e; border-top:solid 2px #e6e6e5">此为系统邮件，请勿回复<br />您在购买过程中有任何疑问，请查阅 <a href="{3}">帮助中心</a> 或联系客服电话：400-676-7170 (周一至周五8:30-17:15 节假日除外)。<br />
												用态度面对，用品质生活！关注校园先锋微博<a href="http://e.weibo.com/studenthero" target="_blank">http://e.weibo.com/studenthero</a> 更多交流，更多期待！校园先锋真心和所有先锋用户一起成长！
												</td></tr>
											</table>
										</body>
									</html>';
	// 购买Mac Office赠送Win Office
	public static $GiftOfficeMailHeader = "Micorsoft Office 2010 专业版授权邮件 - 校园先锋";
	public static $GiftOfficeMailBody = '<html>
										<body>
											<table style="font-size:14px; font-family:微软雅黑, Arial">
												<tr><td style="font-size:26px; font-weight:bold"><img src="{0}/images/logo.gif" alt="校园先锋 STUDENT HERO" /></td></tr>
												<tr><td style="font-size:16px; padding:5px 0"><strong>提示：</strong>这封邮件是您参与校园先锋计划相关活动的依据，请妥善保存此邮件。</td></tr>
												<tr><td style="border:solid 1px #e6e6e5; padding:10px">
													<p>亲爱的用户：</p>
													<p>感谢您加入赛尔校园先锋并购买Office:mac 2011 产品。为感谢您的支持，我们将赠送您 Micorsoft Office 2010 Professional 密钥一个，让您没有兼容问题的烦恼。</p>
													<p style="font-size:16px"><strong>Micorsoft Office 2010 专业版 密钥: </strong>{4}</p>
													<p>请您妥善保管产品密钥，如有任何问题请致电: 400-676-7170 （周一至周五 8:30-17:15, 节假日除外），或发邮件至service@shop.edu.cn。</p>
													<p><strong>安装方法:</strong>请您访问并登录<a target="_blank" href="http://shop.edu.cn/">赛尔校园先锋官方网站</a>，到<a target="_blank" href="http://item.shop.edu.cn/series?id=11">产品页面</a>点击“下载与安装”，然后根据提示进行下载和安装。</p>
													<p>常见问题及相关帮助:<br />
													购买帮助：<a target="_blank" href="http://help.shop.edu.cn/?cid=6">http://help.shop.edu.cn/?cid=6</a><br />
													常见问题：<a target="_blank" href="http://help.shop.edu.cn">http://help.shop.edu.cn</a><br />
													关于我们：<a target="_blank" href="http://www.shop.edu.cn/page?aboutus">http://www.shop.edu.cn/page?aboutus</a>
													</p>
													<p>如果您想了解校园先锋在线商店最新活动，请关注我们的网站及新浪微博@<a target="_blank" href="http://weibo.com/studenthero">赛尔校园先锋</a>。<br />欢迎您再次到校园先锋在线商店进行购物，祝您购物愉快！</p>
												</td></tr>
												<tr><td style="padding-top:10px; color:#999b9e; border-top:solid 2px #e6e6e5">此为系统邮件，请勿回复<br />您在购买过程中有任何疑问，请查阅 <a href="{10}">帮助中心</a> 或联系客服电话：400-676-7170 (周一至周五8:30-17:15 节假日除外)。
												</td></tr>
											</table>
										</body>
									</html>';
	// 赠送 15元代金券
	public static $Voucher2MailHeader = "15元全场软件代金劵请领取 - 校园先锋";
	public static $Voucher2MailBody = '<html>
										<body>
											<table style="font-size:14px; font-family:微软雅黑, Arial">
												<tr><td style="font-size:26px; font-weight:bold"><img src="{0}/images/logo.gif" alt="校园先锋 STUDENT HERO" /></td></tr>
												<tr><td style="font-size:16px; padding:5px 0"><strong>提示：</strong>这封邮件是您参与校园先锋计划相关活动的依据，请妥善保存此邮件。</td></tr>
												<tr><td style="border:solid 1px #e6e6e5; padding:10px">
													<p>亲爱的用户：</p>
													<p>欢迎加入校园先锋俱乐部。</p>
													<p>您获得了15元的全场软件通用代金券。</p>
													<p style="font-size:16px"><strong>代金券串号为: </strong>{1}</p>
													<p><strong>优惠详情：</strong>使用此优惠券购买校园先锋在线商店的全部软件产品时可以抵值15元现金使用。</p>
													<p><strong>使用方法：</strong>请您登录校园先锋在线商店(<a href="{2}" target="_blank">{2}</a>)，选择购买您需要的微软或Adobe公司的正版软件，在订单确认页的下方会出现“<strong>使用代金券抵消部分金额</strong>”。点开后填入相应信息，提交代金券即可抵消部分产品购买金额。</p>
													<p>您可以自己使用该代金券，也可把该代金券送给亲友使用。</p>
													<p style="border-bottom:solid 1px #e6e6e5"></p>
													<p><strong>提醒：</strong>该代金券仅能使用一次，代金劵使用时间为2012年10月26日-2013年1月31日，请在有效期内使用。</p>
													<p>如需了解更多关于产品信息，您可以<a href="{3}" target="_blank">点此查看帮助网页</a>或与我们的客服人员取得联系，客服电话：400-676-7170</p>
													<p>再次感谢您对校园先锋的支持。让我们一起早日免受盗版软件所带来的风险和危害。</p>
												</td></tr>
												<tr><td style="padding-top:10px; color:#999b9e; border-top:solid 2px #e6e6e5">此为系统邮件，请勿回复<br />您在购买过程中有任何疑问，请查阅 <a href="{3}">帮助中心</a> 或联系客服电话：400-676-7170 (周一至周五8:30-17:15 节假日除外)。<br />
												用态度面对，用品质生活！关注校园先锋微博<a href="http://e.weibo.com/studenthero" target="_blank">http://e.weibo.com/studenthero</a> 更多交流，更多期待！校园先锋真心和所有先锋用户一起成长！
												</td></tr>
											</table>
										</body>
									</html>';
	// 购买Win8赠送30元代金券
	public static $GiftCoupon2MailHeader = "购Windows 8送大礼，30元全场软件代金劵请领取 - 校园先锋";
	public static $GiftCoupon2MailBody = '<html>
										<body>
											<table style="font-size:14px; font-family:微软雅黑, Arial">
												<tr><td style="font-size:26px; font-weight:bold"><img src="{0}/images/logo.gif" alt="校园先锋 STUDENT HERO" /></td></tr>
												<tr><td style="font-size:16px; padding:5px 0"><strong>提示：</strong>这封邮件是您参与校园先锋计划相关活动的依据，请妥善保存此邮件，您购买软件时将会使用邮件中的代金券串号。</td></tr>
												<tr><td style="border:solid 1px #e6e6e5; padding:10px">
													<p>亲爱的用户：</p>
													<p>欢迎加入校园先锋俱乐部。</p>
													<p>由于您在校园先锋购买了专业版的Windows 8，我们赠送您30元的全场通用软件代金劵。</p>
													<p style="font-size:16px"><strong>代金券串号为: </strong>{1}</p>
													<p><strong>优惠详情：</strong>使用此优惠券购买校园先锋在线商店的全部软件产品时可以抵值30元现金使用。</p>
													<p><strong>使用方法：</strong>请您登录校园先锋在线商店(<a href="{2}" target="_blank">{2}</a>)，选择购买您需要的微软或Adobe公司的正版软件，在订单确认页的下方会出现“<strong>使用代金券抵消部分金额</strong>”。点开后填入相应信息，提交代金券即可抵消部分产品购买金额。</p>
													<p>您可以自己使用该代金券，也可把该代金券送给亲友使用。</p>
													<p style="border-bottom:solid 1px #e6e6e5"></p>
													<p><strong>提醒：</strong>该代金券仅能使用一次，代金劵使用时间为2012年10月26日-2012年11月26日，请在有效期内使用。</p>
													<p>如需了解更多关于产品信息，您可以<a href="{3}" target="_blank">点此查看帮助网页</a>或与我们的客服人员取得联系，客服电话：400-676-7170</p>
													<p>再次感谢您对校园先锋的支持。让我们一起早日免受盗版软件所带来的风险和危害。</p>
												</td></tr>
												<tr><td style="padding-top:10px; color:#999b9e; border-top:solid 2px #e6e6e5">此为系统邮件，请勿回复<br />您在购买过程中有任何疑问，请查阅 <a href="{3}">帮助中心</a> 或联系客服电话：400-676-7170 (周一至周五8:30-17:15 节假日除外)。<br />
												用态度面对，用品质生活！关注校园先锋微博<a href="http://e.weibo.com/studenthero" target="_blank">http://e.weibo.com/studenthero</a> 更多交流，更多期待！校园先锋真心和所有先锋用户一起成长！
												</td></tr>
											</table>
										</body>
									</html>';
	public static $MsvolagreementContent = '
				<p>本许可确认书为微软公司向正版软件购买人授予的授权许可。</p>
					<table>
						<tr>
							<td><strong>大专院校协议编号:</strong></td><td>48M50061</td>
							<td><strong>学生姓名:</strong></td><td>{0}</td>
						</tr>
						<tr>
							<td><strong>订购许可登记表的结束日期:</strong></td><td>{1}</td>
							<td><strong>身份证号:</strong></td><td>{2}</td>
						</tr>
						<tr>
							<td><strong>教育机构名称:</strong></td><td>中国教育和科研计算机网网络中心</td>
							<td><strong>今日日期:</strong></td><td>{3}</td>
						</tr>
					</table>
					<h3>1. 许可的授予</h3>
					<p>微软公司或其关联公司（以下简称“微软”）在此授权中国教育和科研计算机网网络中心（以下简称“CERNET”）运行软件的一份副本，其版本号及语言如下所示。CERNET根据“大专院校协议”向其成员赛尔校园先锋正版俱乐部会员授予在其电脑上安装软件的权利。 赛尔校园先锋正版俱乐部会员使用软件的权利具有永久性，并应受最新产品使用权利相关部分的管辖、约束，请参见 <a target="_blank" href="http://www.microsoft.com/licensing/">http://www.microsoft.com/licensing/</a>。 如果机构无法访问上方所示网址，请告知微软。微软将向机构提供一份产品使用权利的打印副本。</p>
					<table>
						<tr><th style="text-align: left;">产品说明</th></tr>
						<tr><td>{4}</td></tr>
					</table>
					<h3>2. 权利及限制说明</h3>
					<ul>
						<li>a. <strong>对反向工程、反向编译和反汇编的限制。 </strong>赛尔校园先锋正版俱乐部会员不得对软件进行反向工程、反向编译或反汇编；尽管有此项限制，但如果适用法律明示允许上述活动，则仅在适用法律明示允许的范围内从事上述活动不受此限。</li>
						<li>b. <strong>组件的分离。</strong> 本软件是作为单个产品授予使用许可的。 其组成部分（如有）不得拆分后用于一台以上的计算机。</li>
						<li>c. <strong>租借。</strong> 赛尔校园先锋正版俱乐部会员不得出租、租赁或出借本软件。</li>
						<li>d. <strong>支持服务。</strong> 根据“大专院校协议”计划获得软件的赛尔校园先锋正版俱乐部会员，不享受免费电话支持。支持服务由赛尔校园先锋代替微软受理。</li>
						<li>e. <strong>软件转让。</strong> 赛尔校园先锋正版俱乐部会员不得出售、转让或以其他方式转让自己在本赛尔校园先锋正版俱乐部会员许可确认书中享有的权利。</li>
					</ul>
					<h3>3. 版权</h3>
					<p>本软件（包括但不限于软件中包含的任何图像、照片、动画、视频、音频、音乐、文本及“小程序”）和软件任何副本的一切所有权和版权以及其中包含的一切所有权和版权，均归微软或其供应商所有。 本软件受版权法和国际条约规定的保护。 因此，赛尔校园先锋正版俱乐部会员需要像对待其他受版权保护的材料一样来对待本软件，但如果赛尔校园先锋正版俱乐部会员是仅为备份或存档目的而保留原件，则可以在单台计算机上安装软件。</p>
					<h3>4. 有限保证及免责</h3>
					<p>机构享有九十 (90) 天产品保证，保证条款在产品使用权利中说明。</p>
					<p>在前一句规定的有限保证之外，在适用法律所允许的最大范围内，微软代表自身及其供应商拒绝就产品及相关材料提供一切保证，包括但不仅限于：所有权保证、不侵权保证、适销性保证及适用于特定用途的保证。 某些情况下，机构可能有权向微软索赔。 此时，无论机构索赔的依据为何（违约或侵权），微软的责任仅限于直接损害，以机构根据本协议为引发此种索赔的产品支付的金额为限。 在适用法律所允许的最大范围内，微软及其供应商在任何情况下均无须对与本协议有关的间接损害（包括但不仅限于后果性损害，因利润或收入损失、业务中断或业务信息丢失而造成的损害，或其他损失）负责，即使曾获知此种损害的可能性亦是如此。</p>
			';
/*
	// win8 预售邮件
	public static $Win8PresellMailHeader = "Windows 8 预售通知邮件 - 校园先锋";
	public static $Win8PresellMailBody = '<html>
										<body>
											<table style="font-size:14px; font-family:微软雅黑, Arial">
												<tr><td style="font-size:26px; font-weight:bold"><img src="{10}/images/logo.gif" alt="校园先锋" /></td></tr>
												<tr><td style="font-size:16px; padding:5px 0"><strong>提示：</strong>这是您加入赛尔校园先锋并购买软件密钥的依据，请您妥善保存此邮件，在您安装软件时将会使用邮件中的密钥。</td></tr>
												<tr><td style="border:solid 1px #e6e6e5; padding:10px">
													<p>亲爱的{1}用户，您好！</p>
													<p>感谢您加入赛尔校园先锋并购买产品，以下是您购买产品的详细信息：</p>
													<p style="border-bottom:solid 1px #e6e6e5"></p>
													<p>产品名称：{3}</p>
													<p>订单编号：{2}</p>
													<p>付款日期：{4}</p>
													<p style="border-bottom:solid 1px #e6e6e5"></p>
													<p>购买者姓名：{5}</p>
													<p>购买者帐号：{6}</p>
													<p>身份证号：{7}</p>
													<p>电话号码：{8}</p>
													<p style="border-bottom:solid 1px #e6e6e5"></p>
													<p>赛尔校园先锋将在win8正式发售日（10月31号）前将密钥、下载方式优先发给预定用户。届时请您通过注册邮箱收取密钥邮件。</p>
												</td></tr>
												<tr><td style="padding:10px 0">非常感谢您购买校园先锋的产品，同时感谢您对校园先锋的支持！</td></tr>
												<tr><td style="padding-top:10px; color:#999b9e; border-top:solid 2px #e6e6e5">此为系统邮件，请勿回复<br />您在购买过程中有任何疑问，请查阅 <a href="{9}">帮助中心</a> 或联系客服电话：400-676-7170 （周一至周五 8:30-17:15, 节假日除外），或发邮件至service@shop.edu.cn。</td></tr>
											</table>
										</body>
									</html>';*/

	// win8 授权邮件
	public static $Win8LicenseMailHeader = "Windows 8 授权邮件 - 校园先锋";
	public static $Win8LicenseMailBody = '<html>
										<body>
											<table style="font-size:14px; font-family:微软雅黑, Arial">
												<tr><td style="font-size:26px; font-weight:bold"><img src="{0}/images/logo.gif" alt="校园先锋" /></td></tr>
												<tr><td style="border:solid 1px #e6e6e5; padding:10px">
													<p>亲爱的{2}用户,您好！<br />感谢您加入赛尔校园正版俱乐部并购买产品，授权期内您可正常使用本产品，可享受版本免费升级等服务，毕业后自动转终身授权。以下是您购买产品的详细信息： </p>
													<p><strong>产品名称:</strong> {3}</p>
													<p><strong>产品密钥:</strong> {4} <br />(请您妥善保管产品密钥，如果不慎丢失，您可以登录<a href="{1}">{1}</a>，进入会员中心→已购软件→点击“重发密钥”，密钥将重新发到您的注册邮箱中。)</p>
													<p><strong>安装方法:</strong>请您按以下步骤完成升级安装Windows 8 专业版<br />
													1. 确保电脑配置符合安装要求，<a href="{11}#requirement" target="_blank">查看配置要求</a>；<br />
													2. 下载Windows8-Websetup，<a href="http://download.shop.edu.cn/Microsoft/W8DL/websetup.html" target="_blank">点击此处下载</a>；<br />
														(如果上述文字点击无效，请把下面网页地址复制到浏览器地址栏中打开：<br /><a href="http://download.shop.edu.cn/Microsoft/W8DL/websetup.html" target="_blank">http://download.shop.edu.cn/Microsoft/W8DL/websetup.html</a>)<br />
														如果您还无法下载Windows8-Websetup，请<a href="http://item.shop.edu.cn/disc" target="_blank">点击这里</a>索取客户端介质。<br />
													3. 运行Windows8-Websetup.exe，按照程序提示下载安装Windows 8。
													</p>
													{12}
													<p style="border-bottom:solid 1px #e6e6e5"></p>
													<p>订单编号：{5}</p>
													<p>付款日期：{6}</p>
													<p style="border-bottom:solid 1px #e6e6e5"></p>
													<p>购买者姓名：{2}</p>
													<p>购买者帐号：{7}</p>
													<p>身份证号：{8}</p>
													<p>电话号码：{9}</p>
												</td></tr>
												<tr><td style="padding:10px 0">校园先锋的产品仅限于赛尔校园正版俱乐部成员购买、使用，不得进行出售、转让、拍卖等交易行为，如经查证有倒卖的现象，您购买产品的密钥将有可能被回收，我们保留追究其法律责任的权利。感谢您使用正版软件！</td></tr>
												<tr><td style="padding-top:10px; color:#999b9e; border-top:solid 2px #e6e6e5">此为系统邮件，请勿回复<br />您在购买过程中有任何疑问，请查阅 <a href="{10}">帮助中心</a> 或联系客服电话：400-676-7170 （周一至周五 8:30-17:15, 节假日除外，或发邮件至service@shop.edu.cn）。</td></tr>
											</table>
										</body>
									</html>';
	// win8 京东秘钥
	public static $Win8NonProtocolLicenseMailHeader = "Windows 8 授权邮件 - 校园先锋";
	public static $Win8NonProtocolLicenseMailBody = '<html>
										<body>
											<table style="font-size:14px; font-family:微软雅黑, Arial">
												<tr><td style="font-size:26px; font-weight:bold"><img src="{0}/images/logo.gif" alt="校园先锋" /></td></tr>
												<tr><td style="border:solid 1px #e6e6e5; padding:10px">
													<p>亲爱的{2}用户,您好！<br />感谢您加入赛尔校园正版俱乐部并购买产品，以下是您购买产品的详细信息： </p>
													<p><strong>产品名称:</strong> {3}</p>
													<p><strong>产品密钥:</strong> {4} <br />(请您妥善保管产品密钥，如果不慎丢失，您可以登录<a href="{1}">{1}</a>，进入会员中心→已购软件→点击“重发密钥”，密钥将重新发到您的注册邮箱中。)</p>
													<p><strong>安装方法:</strong>请您按以下步骤完成升级安装Windows 8 专业版<br />
													1. 确保电脑配置符合安装要求，<a href="{11}#requirement" target="_blank">查看配置要求</a>；<br />
													2. 下载Windows8-Websetup，<a href="http://download.shop.edu.cn/Microsoft/W8DL/websetup.html" target="_blank">点击此处下载</a>；<br />
														(如果上述文字点击无效，请把下面网页地址复制到浏览器地址栏中打开：<br /><a href="http://download.shop.edu.cn/Microsoft/W8DL/websetup.html" target="_blank">http://download.shop.edu.cn/Microsoft/W8DL/websetup.html</a>)<br />
														如果您还无法下载Windows8-Websetup，请<a href="http://item.shop.edu.cn/disc" target="_blank">点击这里</a>索取客户端介质。<br />
													3. 运行Windows8-Websetup.exe，按照程序提示下载安装Windows 8。
													</p>
													{12}
													<p style="border-bottom:solid 1px #e6e6e5"></p>
													<p>订单编号：{5}</p>
													<p>付款日期：{6}</p>
													<p style="border-bottom:solid 1px #e6e6e5"></p>
													<p>购买者姓名：{2}</p>
													<p>购买者帐号：{7}</p>
													<p>身份证号：{8}</p>
													<p>电话号码：{9}</p>
												</td></tr>
												<tr><td style="padding:10px 0">校园先锋的产品仅限于赛尔校园正版俱乐部成员购买、使用，不得进行出售、转让、拍卖等交易行为，如经查证有倒卖的现象，您购买产品的密钥将有可能被回收，我们保留追究其法律责任的权利。感谢您使用正版软件！</td></tr>
												<tr><td style="padding-top:10px; color:#999b9e; border-top:solid 2px #e6e6e5">此为系统邮件，请勿回复<br />您在购买过程中有任何疑问，请查阅 <a href="{10}">帮助中心</a> 或联系客服电话：400-676-7170 （周一至周五 8:30-17:15, 节假日除外，或发邮件至service@shop.edu.cn）。</td></tr>
											</table>
										</body>
									</html>';
	// 试试手气邮件 // 元旦20元代金券邮件
	public static $Voucher3MailHeader = "恭喜您，获得校园先锋在线商店{discount}元代金劵。";
	public static $Voucher3MailBody = '<html>
										<body>
											<table style="font-size:14px; font-family:微软雅黑, Arial">
												<tr><td style="font-size:26px; font-weight:bold"><img src="{0}/images/logo.gif" alt="校园先锋 STUDENT HERO" /></td></tr>
												<tr><td style="font-size:16px; padding:5px 0"><strong>提示：</strong>这封邮件是您参与校园先锋计划相关活动的依据，请妥善保存此邮件，您购买软件时将会使用邮件中的代金券串号。</td></tr>
												<tr><td style="border:solid 1px #e6e6e5; padding:10px">
													<p>亲爱的用户, 您好！</p>
													<p>恭喜您在先锋欢乐购抽奖活动中获得{1}元的全场软件通用代金劵。</p>
													<p style="font-size:16px"><strong>代金券串号为: </strong>{2}</p>
													<p><strong>使用方法：</strong>请您登录校园先锋在线商店(<a href="{3}" target="_blank">{3}</a>)，选择购买您需要的微软或Adobe公司的正版软件，在订单确认页的下方会出现“<strong>使用代金券抵消部分金额</strong>”。点开后填入相应信息，提交代金券即可抵消部分产品购买金额。</p>
													<p style="border-bottom:solid 1px #e6e6e5"></p>
													<p><strong>提醒：</strong>该代金券仅能使用一次。代金劵使用截止时间为2013年01月31日。</p>
													<p>如需了解更多关于产品信息，您可以<a href="{4}" target="_blank">点此查看帮助网页</a>或与我们的客服人员取得联系，客服电话：400-676-7170</p>
												</td></tr>
												<tr><td style="padding-top:10px; color:#999b9e; border-top:solid 2px #e6e6e5">此为系统邮件，请勿回复<br />您在购买过程中有任何疑问，请查阅 <a href="{4}">帮助中心</a> 或联系客服电话：400-676-7170 (周一至周五8:30-17:15 节假日除外)。<br />
												用态度面对，用品质生活！关注校园先锋微博<a href="http://e.weibo.com/studenthero" target="_blank">http://e.weibo.com/studenthero</a> 更多交流，更多期待！校园先锋真心和所有先锋用户一起成长！
												</td></tr>
											</table>
										</body>
									</html>';

//Activity11 邀请好友送代金卷活动，发送代金卷
	public static $Activity11MailHeader = "恭喜您，获得校园先锋在线商店{discount}元代金劵。";
	public static $Activity11MailBody = '<html>
										<body>
											<table style="font-size:14px; font-family:微软雅黑, Arial">
												<tr><td style="font-size:26px; font-weight:bold"><img src="{0}/images/logo.gif" alt="校园先锋 STUDENT HERO" /></td></tr>
												<tr><td style="font-size:16px; padding:5px 0"><strong>提示：</strong>这封邮件是您参与校园先锋《成长集结号,邀请好友送好礼!》活动的依据，请妥善保存此邮件，您购买软件时将会使用邮件中的代金券串号。</td></tr>
												<tr><td style="border:solid 1px #e6e6e5; padding:10px">
													<p>亲爱的用户, 您好！</p>
													<p>恭喜您在校园先锋《成长集结号,邀请好友送好礼!》活动中获得{1}元的全场软件通用代金劵。</p>
													<p style="font-size:16px"><strong>代金券串号为: </strong>{2}</p>
													<p><strong>使用方法：</strong>请您登录校园先锋在线商店(<a href="{3}" target="_blank">{3}</a>)，选择购买您需要的微软或Adobe公司的正版软件，在订单确认页的下方会出现“<strong>使用代金券抵消部分金额</strong>”。点开后填入相应信息，提交代金券即可抵消部分产品购买金额。</p>
													<p style="border-bottom:solid 1px #e6e6e5"></p>
													<p><strong>提醒：</strong>该代金券仅能使用一次。代金劵使用截止时间为2013年7月31日24点。</p>
													<p>如需了解更多关于产品信息，您可以<a href="{4}" target="_blank">点此查看帮助网页</a>或与我们的客服人员取得联系，客服电话：400-676-7170</p>
												</td></tr>
												<tr><td style="padding-top:10px; color:#999b9e; border-top:solid 2px #e6e6e5">此为系统邮件，请勿回复<br />您在购买过程中有任何疑问，请查阅 <a href="{4}">帮助中心</a> 或联系客服电话：400-676-7170 (周一至周五8:30-17:15 节假日除外)。<br />
												用态度面对，用品质生活！关注校园先锋微博<a href="http://e.weibo.com/studenthero" target="_blank">http://e.weibo.com/studenthero</a> 更多交流，更多期待！校园先锋真心和所有先锋用户一起成长！
												</td></tr>
											</table>
										</body>
									</html>';

/* * Activity14 校园先锋教育特惠迎新活动——三星专场 添加，活动代金卷邮件发送通用邮模板
 *   {1}     活动名称
 *   {2}     代金卷串号
 *   {3}     代金卷抵扣金额
 *   {4}     代金卷使用范围
 *   {5}     截止日期（代金卷可以使用的最后一天）格式为：2013年10月30日
 * */
public static $ActivityCouponMailHeader = "恭喜您，获得校园先锋在线商店{discount}元代金劵。";
public static $ActivityCouponMailBody = '<html>
<body>
<table style="font-size:14px; font-family:微软雅黑, Arial">
	<tr><td style="font-size:26px; font-weight:bold"><img src="http://content.shop.edu.cn/images/logo.gif" alt="校园先锋 STUDENT HERO" /></td></tr>
	<tr><td style="font-size:16px; padding:5px 0"><strong>提示：</strong>这封邮件是您参与校园先锋《{1}》活动的依据，请妥善保存此邮件，您购买软件时将会使用邮件中的代金券串号。</td></tr>
	<tr><td style="border:solid 1px #e6e6e5; padding:10px">
		<p>亲爱的用户, <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;您好！</p>
		<p>感谢您分享活动《{1}》给您的好友。校园先锋在线商店赠送您价值为20元的代金券，以下是代金劵的详细信息：</p>
		<p style="font-size:14px"><strong>代金券串号为: </strong>{2}</p>
		<p><strong>代金卷抵扣金额：</strong>{3}元</p>
		<p><strong>代金卷使用范围：</strong>校园先锋在线商店线上销售的{4}商品。</p>
		<p><strong>代金卷使用方法：</strong>请您登录校<a href="http://shop.edu.cn" target="_blank">园先锋在线商店（shop.edu.cn）</a>，选择购买您需要的商品，在订单确认页的下方会出现“<strong>使用代金券抵消部分金额</strong>”。点开后填入相应信息，提交代金券信息即可抵消部分金额。</p>
		<p style="border-bottom:solid 1px #e6e6e5"></p>
		<p><strong>提醒：</strong>该代金券仅能使用一次，不可重复或累加。代金劵使用截止时间为{5}24点。</p>
		<p>了解更多请关注 <span style="font-size: 14px; color: #ff0000; font-weight: bold;">新浪微博<a href="http://weibo.com/studenthero" target="_blank" style="color: #ff0000;">@赛尔校园先锋</a>&nbsp;&nbsp;&nbsp;<a href="http://wp.qq.com/wpa/qunwpa?idkey=36ce730a8de0297264dfff03ad6108219bd34de27a637add58528b9b4bee60fe" target="_blank" style="color: #ff0000;">QQ群：186155896</a></span></p>
	</td></tr>
	<tr><td style="padding-top:10px; color:#999b9e; border-top:solid 2px #e6e6e5">此为系统邮件，请勿回复<br />您在购买过程中有任何疑问，请查阅 <a href="{4}">帮助中心</a> 或联系客服电话：400-676-7170 (周一至周五8:30-17:15 节假日除外)。<br />
		用态度面对，用品质生活！关注校园先锋微博<a href="http://weibo.com/studenthero" target="_blank">@赛尔校园先锋</a> 更多交流，更多期待！校园先锋真心和所有先锋用户一起成长！
	</td></tr>
</table>
</body>
</html>';

	public static $Activity15CouponMailHeader = "恭喜您获得校园先锋20元代金劵，三星活动第二季进行中1元买正版！";
	public static $Activity15CouponMailBody = '<html>
<body>
<table style="font-size:14px; font-family:微软雅黑, Arial">
	<tr><td style="font-size:26px; font-weight:bold"><img src="http://content.shop.edu.cn/images/logo.gif" alt="校园先锋 STUDENT HERO" /></td></tr>
	<tr><td style="font-size:16px; padding:5px 0"><strong>提示：</strong>这封邮件是您参与校园先锋《1+1，十一成双——三星校园活动第二季》活动的依据，请妥善保存此邮件，您购买软件时将会使用邮件中的代金券串号。</td></tr>
	<tr><td style="border:solid 1px #e6e6e5; padding:10px">
		<p>亲爱的用户, <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;您好！</p>
		<p>好奇睿智的射手账号带给你好运，恭喜您参与1+1，十一成双——三星校园活动第二季抢到20元代金劵！11月23日-12月21日期间注册的新用户老用户都可以领取。快叫上你的好朋友一起来吧！</p>
		<p>以下是代金劵的详细信息：</p>
		<p style="font-size:14px"><strong>代金券串号为: </strong>{0}</p>
		<p><strong>代金卷抵扣金额：</strong>20元</p>
		<p><strong>代金卷使用范围：</strong>使用此优惠券购买校园先锋在线商店线上销售的三星电脑和软件商品时可以抵值20元现金使用。</p>
		<p><strong>代金卷使用方法：</strong>请您登录校<a href="http://shop.edu.cn" target="_blank">园先锋在线商店（shop.edu.cn）</a>，选择购买您需要的商品，在订单确认页的下方会出现“<strong>使用代金券抵消部分金额</strong>”。点开后填入相应信息，提交代金券信息即可抵消部分金额。</p>
		<p style="border-bottom:solid 1px #e6e6e5"></p>
		<p>1元买正版软件活动进行中，数量有限，速来<a href="http://shop.edu.cn/" target="_blank">园先锋在线商店</a>！</p>
		<p><strong>提醒：</strong>该代金券仅能使用一次，不可重复或累加。代金劵使用截止时间为2013年12月30日24点。</p>
		<p>了解更多请关注 <span style="font-size: 14px; color: #ff0000; font-weight: bold;">新浪微博<a href="http://weibo.com/studenthero" target="_blank" style="color: #ff0000;">@赛尔校园先锋</a>&nbsp;&nbsp;&nbsp;<a href="http://wp.qq.com/wpa/qunwpa?idkey=36ce730a8de0297264dfff03ad6108219bd34de27a637add58528b9b4bee60fe" target="_blank" style="color: #ff0000;">QQ群：186155896</a></span></p>
	</td></tr>
	<tr><td style="padding-top:10px; color:#999b9e; border-top:solid 2px #e6e6e5">此为系统邮件，请勿回复<br />您在购买过程中有任何疑问，请查阅 <a href="{4}">帮助中心</a> 或联系客服电话：400-676-7170 (周一至周五8:30-17:15 节假日除外)。<br />
		用态度面对，用品质生活！关注校园先锋微博<a href="http://weibo.com/studenthero" target="_blank">@赛尔校园先锋</a> 更多交流，更多期待！校园先锋真心和所有先锋用户一起成长！
	</td></tr>
</table>
</body>
</html>';
	public static $Activity23CouponMailHeader = "恭喜您获得20元的新人红包！感谢您参与 “校园先锋新年嘉年华” 活动！";
	public static $Activity23CouponMailBody = '<html>
<body>
<table style="font-size:14px; font-family:微软雅黑, Arial">
	<tr><td style="font-size:26px; font-weight:bold"><img src="http://content.shop.edu.cn/images/logo.gif" alt="校园先锋 STUDENT HERO" /></td></tr>
	<tr><td style="font-size:16px; padding:5px 0"><strong>提示：</strong>这封邮件是您参与“校园先锋新年嘉年华 — Office特卖专场"活动的依据，请妥善保存此邮件。</td></tr>
	<tr><td style="border:solid 1px #e6e6e5; padding:10px">
		<p>亲爱的用户, <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;您好！</p>
		<p> 恭喜您参与”新人红包拿不停“抢到20元代金劵！2015年1月6日——2015年2月6日 期间注册的新用户都可以领取。赶快叫上你的好友一起来参与吧！</p>
		<p>以下是代金劵的详细信息：</p>
		<p style="font-size:14px"><strong>代金券串号为: </strong>{0}</p>
		<p><strong>代金劵抵扣金额：</strong>20元</p>
		<p><strong>代金劵使用范围：</strong>使用此优惠券购买校园先锋在线商店线上销售的<a href="http://item.shop.edu.cn/series?id=84" target="_blank">office2013专业增强版
</a>软件时可以抵值20元现金使用。</p>
		<p><strong>代金劵使用方法：</strong>请您登录<a href="http://shop.edu.cn" target="_blank">校园先锋在线商店（shop.edu.cn）</a>，选择购买您需要的商品，在订单确认页的下方会出现“<strong>使用代金券抵消部分金额</strong>”。点开后填入相应信息，提交代金券信息即可抵消部分金额。</p>
		<p style="border-bottom:solid 1px #e6e6e5"></p>
		<p><strong>提醒：</strong>该代金券仅能使用一次，不可重复或累加使用。代金劵使用截止时间为2015年2月28日。</p>
		<p><strong>校园先锋“新年嘉年华”活动正在火热进行中，礼品数量有限，速来参与！</strong></p>
		
	</td></tr>
	<tr><td style="padding-top:10px; color:#999b9e; border-top:solid 2px #e6e6e5"><p>欢迎继续支持我们的活动：关注 <span style="font-size: 14px; color: #ff0000; font-weight: bold;">新浪微博<a href="http://weibo.com/studenthero" target="_blank" style="color: #ff0000;">@赛尔校园先锋</a>&nbsp;&nbsp;&nbsp;，微信公众号<a href="http://shop.edu.cn" target="_blank" style="color: #ff0000;">student_hero</a></span></p>
		<p>了解更多精彩，尽在校园先锋！</p>
	</td></tr>
</table>
</body>
</html>';

	public static $Activity23_1CouponMailHeader = "恭喜您在 “校园先锋新年嘉年华” 活动中抽得 Parallels Access一份！新年福利大放送！";
	public static $Activity23_1CouponMailBody = '<html>
<body>
<table style="font-size:14px; font-family:微软雅黑, Arial">
	<tr><td style="font-size:26px; font-weight:bold"><img src="http://content.shop.edu.cn/images/logo.gif" alt="校园先锋 STUDENT HERO" /></td></tr>
	<tr><td style="font-size:16px; padding:5px 0"><strong>提示：</strong>这封邮件是您参与“校园先锋新年嘉年华 — Office特卖专场"活动的依据，请妥善保存此邮件。</td></tr>
	<tr><td style="border:solid 1px #e6e6e5; padding:10px">
		<p>亲爱的用户, <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;您好！</p>
		<p>感谢您参与我们的新年网站活动:"校园先锋新年嘉年华 — Office特卖专场",校园先锋在线商店赠送您Parallels Access一份，因为是实物奖品，请提供您的寄送信息！</p>
		<p>请回复邮件含以下真实信息：</p>
		<p><strong>1：</strong>姓名</p>
		<p><strong>2：</strong>手机号码</p>
		<p><strong>3：</strong>收货地址</p>
		<p><strong>4：</strong>邮政编码</p>
		<p style="border-bottom:solid 1px #e6e6e5"></p>
		<p><strong>提醒：</strong>为了礼品及时寄出，请在活动结束后五天之内（2月11日之前）回复邮件，我们将安排统一寄送！</p>
		<p><strong>校园先锋“新年嘉年华”活动正在火热进行中，礼品数量有限，速来参与！</strong></p>
		
	</td></tr>
	<tr><td style="padding-top:10px; color:#999b9e; border-top:solid 2px #e6e6e5"><p>欢迎继续支持我们的活动：关注 <span style="font-size: 14px; color: #ff0000; font-weight: bold;">新浪微博<a href="http://weibo.com/studenthero" target="_blank" style="color: #ff0000;">@赛尔校园先锋</a>&nbsp;&nbsp;&nbsp;，微信公众号<a href="http://shop.edu.cn" target="_blank" style="color: #ff0000;">student_hero</a></span></p>
		<p>了解更多精彩，尽在校园先锋！</p>
	</td></tr>
</table>
</body>
</html>';
	public static $Activity23_2CouponMailHeader = "恭喜您在 “校园先锋新年嘉年华” ,新年福利大放送！";
	public static $Activity23_2CouponMailBody = '<html>
<body>
<table style="font-size:14px; font-family:微软雅黑, Arial">
	<tr><td style="font-size:26px; font-weight:bold"><img src="http://content.shop.edu.cn/images/logo.gif" alt="校园先锋 STUDENT HERO" /></td></tr>
	<tr><td style="font-size:16px; padding:5px 0"><strong>提示：</strong>这封邮件是您参与“校园先锋新年嘉年华 — Office特卖专场"活动的依据，请妥善保存此邮件。</td></tr>
	<tr><td style="border:solid 1px #e6e6e5; padding:10px">
		<p>亲爱的用户, <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;您好！</p>
		<p>感谢您参与我们的新年网站活动:"校园先锋新年嘉年华 — Office特卖专场",校园先锋在线商店赠送您限量精品水壶一个，因为是实物奖品，请提供您的寄送信息！</p>
		<p>请回复邮件含以下真实信息：</p>
		<p><strong>1：</strong>姓名</p>
		<p><strong>2：</strong>手机号码</p>
		<p><strong>3：</strong>收货地址</p>
		<p><strong>4：</strong>邮政编码</p>
		<p style="border-bottom:solid 1px #e6e6e5"></p>
		<p><strong>提醒：</strong>为了礼品及时寄出，请在活动结束后五天之内（2月11日之前）回复邮件，我们将安排统一寄送！</p>
		<p><strong>校园先锋“新年嘉年华”活动正在火热进行中，礼品数量有限，速来参与！</strong></p>
		
	</td></tr>
	<tr><td style="padding-top:10px; color:#999b9e; border-top:solid 2px #e6e6e5"><p>欢迎继续支持我们的活动：关注 <span style="font-size: 14px; color: #ff0000; font-weight: bold;">新浪微博<a href="http://weibo.com/studenthero" target="_blank" style="color: #ff0000;">@赛尔校园先锋</a>&nbsp;&nbsp;&nbsp;，微信公众号<a href="http://shop.edu.cn" target="_blank" style="color: #ff0000;">student_hero</a></span></p>
		<p>了解更多精彩，尽在校园先锋！</p>
	</td></tr>
</table>
</body>
</html>';
	public static $Activity23_3CouponMailHeader = "恭喜您在 “校园先锋新年嘉年华” 活动中抽得40元礼券！新年福利大放送！";
	public static $Activity23_3CouponMailBody = '<html>
<body>
<table style="font-size:14px; font-family:微软雅黑, Arial">
	<tr><td style="font-size:26px; font-weight:bold"><img src="http://content.shop.edu.cn/images/logo.gif" alt="校园先锋 STUDENT HERO" /></td></tr>
	<tr><td style="font-size:16px; padding:5px 0"><strong>提示：</strong>这封邮件是您参与“校园先锋新年嘉年华 — Office特卖专场"活动的依据，请妥善保存此邮件。</td></tr>
	<tr><td style="border:solid 1px #e6e6e5; padding:10px">
		<p>亲爱的用户, <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;您好！</p>
		<p>感谢您参与我们的新年网站活动:"校园先锋新年嘉年华 — Office特卖专场",校园先锋在线商店赠送您价值为40元的office2013专业增强版代金券！</p>
		<p>以下是代金劵的详细信息：</p>
		<p style="font-size:14px"><strong>代金券串号为: </strong>{0}</p>
		<p><strong>代金劵抵扣金额：</strong>40元</p>
		<p><strong>代金劵使用范围：</strong>使用此优惠券购买校园先锋在线商店线上销售的<a href="http://item.shop.edu.cn/series?id=84" target="_blank">office2013专业增强版
</a>软件时可以抵值40元现金使用。</p>
		<p><strong>代金劵使用方法：</strong>请您登录<a href="http://shop.edu.cn" target="_blank">校园先锋在线商店（shop.edu.cn）</a>，选择购买您需要的商品，在订单确认页的下方会出现“<strong>使用代金券抵消部分金额</strong>”。点开后填入相应信息，提交代金券信息即可抵消部分金额。</p>
		<p style="border-bottom:solid 1px #e6e6e5"></p>
		<p><strong>提醒：</strong>该代金券仅能使用一次，不可重复或累加使用。代金劵使用截止时间为2015年2月28日。</p>
		<p><strong>校园先锋“新年嘉年华”活动正在火热进行中，礼品数量有限，速来参与！</strong></p>
		
	</td></tr>
	<tr><td style="padding-top:10px; color:#999b9e; border-top:solid 2px #e6e6e5"><p>欢迎继续支持我们的活动：关注 <span style="font-size: 14px; color: #ff0000; font-weight: bold;">新浪微博<a href="http://weibo.com/studenthero" target="_blank" style="color: #ff0000;">@赛尔校园先锋</a>&nbsp;&nbsp;&nbsp;，微信公众号<a href="http://shop.edu.cn" target="_blank" style="color: #ff0000;">student_hero</a></span></p>
		<p>了解更多精彩，尽在校园先锋！</p>
	</td></tr>
</table>
</body>
</html>';
	public static $Activity23_4CouponMailHeader = "恭喜您在 “校园先锋新年嘉年华” 活动中抽得20元礼券！新年福利大放送！";
	public static $Activity23_4CouponMailBody = '<html>
<body>
<table style="font-size:14px; font-family:微软雅黑, Arial">
	<tr><td style="font-size:26px; font-weight:bold"><img src="http://content.shop.edu.cn/images/logo.gif" alt="校园先锋 STUDENT HERO" /></td></tr>
	<tr><td style="font-size:16px; padding:5px 0"><strong>提示：</strong>这封邮件是您参与“校园先锋新年嘉年华 — Office特卖专场"活动的依据，请妥善保存此邮件。</td></tr>
	<tr><td style="border:solid 1px #e6e6e5; padding:10px">
		<p>亲爱的用户, <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;您好！</p>
		<p>感谢您参与我们的新年网站活动:"校园先锋新年嘉年华 — Office特卖专场",校园先锋在线商店赠送您价值为20元的office2013专业增强版代金券！</p>
		<p>以下是代金劵的详细信息：</p>
		<p style="font-size:14px"><strong>代金券串号为: </strong>{0}</p>
		<p><strong>代金劵抵扣金额：</strong>20元</p>
		<p><strong>代金劵使用范围：</strong>使用此优惠券购买校园先锋在线商店线上销售的<a href="http://item.shop.edu.cn/series?id=84" target="_blank">office2013专业增强版
</a>软件时可以抵值20元现金使用。</p>
		<p><strong>代金劵使用方法：</strong>请您登录<a href="http://shop.edu.cn" target="_blank">校园先锋在线商店（shop.edu.cn）</a>，选择购买您需要的商品，在订单确认页的下方会出现“<strong>使用代金券抵消部分金额</strong>”。点开后填入相应信息，提交代金券信息即可抵消部分金额。</p>
		<p style="border-bottom:solid 1px #e6e6e5"></p>
		<p><strong>提醒：</strong>该代金券仅能使用一次，不可重复或累加使用。代金劵使用截止时间为2015年2月28日。</p>
		<p><strong>校园先锋“新年嘉年华”活动正在火热进行中，礼品数量有限，速来参与！</strong></p>
		
	</td></tr>
	<tr><td style="padding-top:10px; color:#999b9e; border-top:solid 2px #e6e6e5"><p>欢迎继续支持我们的活动：关注 <span style="font-size: 14px; color: #ff0000; font-weight: bold;">新浪微博<a href="http://weibo.com/studenthero" target="_blank" style="color: #ff0000;">@赛尔校园先锋</a>&nbsp;&nbsp;&nbsp;，微信公众号<a href="http://shop.edu.cn" target="_blank" style="color: #ff0000;">student_hero</a></span></p>
		<p>了解更多精彩，尽在校园先锋！</p>
	</td></tr>
</table>
</body>
</html>';
	public static $Activity23_5CouponMailHeader = "恭喜您在 “校园先锋新年嘉年华” 活动中抽得5元礼券！新年福利大放送！";
	public static $Activity23_5CouponMailBody = '<html>
<body>
<table style="font-size:14px; font-family:微软雅黑, Arial">
	<tr><td style="font-size:26px; font-weight:bold"><img src="http://content.shop.edu.cn/images/logo.gif" alt="校园先锋 STUDENT HERO" /></td></tr>
	<tr><td style="border:solid 1px #e6e6e5; padding:10px">
		<p>亲爱的用户, <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;您好！</p>
		<p>感谢您参与我们的新年网站活动:"校园先锋新年嘉年华 — Office特卖专场",校园先锋在线商店赠送您价值为5元的office2013专业增强版代金券！</p>
		<p>以下是代金劵的详细信息：</p>
		<p style="font-size:14px"><strong>代金券串号为: </strong>{0}</p>
		<p><strong>代金劵抵扣金额：</strong>5元</p>
		<p><strong>代金劵使用范围：</strong>使用此优惠券购买校园先锋在线商店线上销售的<a href="http://item.shop.edu.cn/series?id=84" target="_blank">office2013专业增强版
</a>软件时可以抵值5元现金使用。</p>
		<p><strong>代金劵使用方法：</strong>请您登录<a href="http://shop.edu.cn" target="_blank">校园先锋在线商店（shop.edu.cn）</a>，选择购买您需要的商品，在订单确认页的下方会出现“<strong>使用代金券抵消部分金额</strong>”。点开后填入相应信息，提交代金券信息即可抵消部分金额。</p>
		<p style="border-bottom:solid 1px #e6e6e5"></p>
		<p><strong>提醒：</strong>该代金券仅能使用一次，不可重复或累加使用。代金劵使用截止时间为2015年2月28日。</p>
		<p><strong>校园先锋“新年嘉年华”活动正在火热进行中，礼品数量有限，速来参与！</strong></p>
		
	</td></tr>
	<tr><td style="padding-top:10px; color:#999b9e; border-top:solid 2px #e6e6e5"><p>欢迎继续支持我们的活动：关注 <span style="font-size: 14px; color: #ff0000; font-weight: bold;">新浪微博<a href="http://weibo.com/studenthero" target="_blank" style="color: #ff0000;">@赛尔校园先锋</a>&nbsp;&nbsp;&nbsp;，微信公众号<a href="http://shop.edu.cn" target="_blank" style="color: #ff0000;">student_hero</a></span></p>
		<p>了解更多精彩，尽在校园先锋！</p>
	</td></tr>
</table>
</body>
</html>';
	public static $Activity25CouponMailHeader = "感谢您参加先锋英语挑战赛活动";
	public static $Activity25_1CouponMailBody = '<html>
<body>
<table style="font-size:14px; font-family:微软雅黑, Arial">
	<tr><td style="font-size:26px; font-weight:bold"><img src="http://content.shop.edu.cn/images/logo.gif" alt="校园先锋 STUDENT HERO" /></td></tr>
	<tr><td style="border:solid 1px #e6e6e5; padding:10px">
		<p>亲爱的先锋用户:<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;您好！</p>
		<p>恭喜您已成功领取智课网《{0}》课程。以下是详细信息：</p>
		<p><strong>课程激活码：</strong>{1}</p>
		<p><strong>使用方法：</strong>您可以在智课网首页点击“注卡中心”，或点击下方链接，您在线下获得的会员卡、课程卡、充值卡、<br/>代金券，都需要在这里激活。激活地址：<a href="http://www.smartstudy.com/user/cardreg?hmsr=%E5%8C%97%E4%BA%AC%E9%87%91%E6%99%BA%E5%8D%8E%E6%95%99&hmmd=&hmpl=&hmkw=&hmci=">http://www.smartstudy.com/user/cardreg</a></p>
		<p><strong style="color:#ff0000">提醒：</strong>请在2015年6月21日前进行激活,该激活码仅能使用一次，不可重复使用。课程有效期为激活后15天，<br/>现在开始，一起学习吧！</p>
		<p style="border-bottom:solid 1px #e6e6e5"></p>
		<p>如需了解更多关于产品信息或有疑问请与客服人员取得联系，客服电话：400-011-9191, 客服邮箱:service@smartstudy.com</p>
	</td></tr>
	<tr style="border:0px;">
		<td>
		<p style="color:#000000">【我们将会不定期更新课程,敬请关注校园先锋在线商店(<a href="http://shop.edu.cn">shop.edu.cn</a>),更多交流，更多期待！校园先锋真心和所有先锋用户一起成长！】</p>
		</td>
	</tr>
	<tr>
	<td style="padding-top:10px; color:#000000;"><p>关注新浪微博<a href="http://weibo.com/studenthero" target="_blank" style="color: #165999;">@赛尔校园先锋</a>&nbsp;&nbsp;&nbsp;，微信公众号<a href="http://shop.edu.cn" target="_blank" style="color: #165999;">student_hero</a></p>
	</td></tr>
</table>
<div class="purchase_reason">
			<a href="http://weibo.com/studenthero" title="关注校园先锋新浪微博~" class="sina_weibo" id="sina"><em><span></span></em></a>
			<a href="http://wp.qq.com/wpa/qunwpa?idkey=36ce730a8de0297264dfff03ad6108219bd34de27a637add58528b9b4bee60fe" title="加入校园先锋QQ群~" rel="nofollow" target="_blank" class="qq"></a>
			<img style="width:635px" src="http://upload.shop.edu.cn/banner/adobe/21.jpg">
		</div>
		<link href="http://content.shop.edu.cn/css/main2.css" rel="stylesheet" type="text/css" />
</body>
</html>';
	public static $Activity25_2CouponMailBody = '<html>
<body>
<table style="font-size:14px; font-family:微软雅黑, Arial">
	<tr><td style="font-size:26px; font-weight:bold"><img src="http://content.shop.edu.cn/images/logo.gif" alt="校园先锋 STUDENT HERO" /></td></tr>
	<tr><td style="border:solid 1px #e6e6e5; padding:10px">
		<p>亲爱的先锋用户: <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;您好！</p>
		<p>恭喜您已成功领取智课网{0}元代金券1张。以下是详细信息： </p>
		<p><strong>代金券激活码：</strong>{1}</p>
		<p><strong>优惠详情：</strong>此代金券在购买智课网指定课程时，单笔订单满{2}元即可使用。</p>
		<p><strong>使用方法：</strong>您可以在智课网首页点击“注卡中心”，或点击下方链接，您在线下获得的会员卡、课程卡、充值卡、<br/>代金券，都需要在这里激活。激活地址：<a href="http://www.smartstudy.com/user/cardreg?hmsr=%E5%8C%97%E4%BA%AC%E9%87%91%E6%99%BA%E5%8D%8E%E6%95%99&hmmd=&hmpl=&hmkw=&hmci=">http://www.smartstudy.com/user/cardreg</a></p>
		<p><strong  style="color:#ff0000">提醒：</strong>请在<b>2015年6月20日</b>前进行激活,该代金券仅能使用一次，不可重复使用。代金券适用期为激活后<b>60</b>天，<br/>现在开始，一起学习吧！</p>
		<p style="border-bottom:solid 1px #e6e6e5"></p>
		<p>如需了解更多关于产品信息或有疑问请与客服人员取得联系，客服电话：400-011-9191, 客服邮箱:service@smartstudy.com</p>
	</td></tr>
	<tr>
		<td>
		<p  style="color:#000000">【我们将会不定期更新课程,敬请关注校园先锋在线商店(<a href="http://shop.edu.cn">shop.edu.cn</a>),更多交流，更多期待！校园先锋真心和所有先锋用户一起成长！】</p>
		</td>
	</tr>
	<tr>
	<td style="padding-top:10px; color:#000000;"><p>关注新浪微博<a href="http://weibo.com/studenthero" target="_blank" style="color: #165999;">@赛尔校园先锋</a>&nbsp;&nbsp;&nbsp;，微信公众号<a href="http://shop.edu.cn" target="_blank" style="color: #165999;">student_hero</a></p>
	</td></tr>
</table>
	<div class="purchase_reason">
			<a href="http://weibo.com/studenthero" title="关注校园先锋新浪微博~" class="sina_weibo" id="sina"><em><span></span></em></a>
			<a href="http://wp.qq.com/wpa/qunwpa?idkey=36ce730a8de0297264dfff03ad6108219bd34de27a637add58528b9b4bee60fe" title="加入校园先锋QQ群~" rel="nofollow" target="_blank" class="qq"></a>
			<img style="width:635px" src="http://upload.shop.edu.cn/banner/adobe/21.jpg">
		</div>
		<link href="http://content.shop.edu.cn/css/main2.css" rel="stylesheet" type="text/css" />
	
</body>
</html>';
	public static $Activity16CouponMailHeader = "先锋开学季 小年，武装起来！“活动代金券！";
	public static $Activity16CouponMailBody = '<html>
<body>
<table style="font-size:14px; font-family:微软雅黑, Arial">
	<tr><td style="font-size:26px; font-weight:bold"><img src="http://content.shop.edu.cn/images/logo.gif" alt="校园先锋 STUDENT HERO" /></td></tr>
	<tr><td style="font-size:16px; padding:5px 0"><strong>提示：</strong>这封邮件是您参与校园先锋《先锋开学季 小年，武装起来！》活动的获奖依据，请妥善保存此邮件，您购买软件时将会使用邮件中的代金券串号。</td></tr>
	<tr><td style="border:solid 1px #e6e6e5; padding:10px">
		<p>亲爱的用户, <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;您好！</p>
		<p>恭喜您在“先锋开学季 小年，武装起来！”兑换到到{1}元代金劵！活动期间，通过校园先锋Apple 教育商店购买iPad、iPhone、Mac（翻新机除外） 系列商品的就可获得Mac Office、PARALLELS DESKTOP等软件代金券。</p>
		<p>以下是代金劵的详细信息：</p>
		<p style="font-size:14px"><strong>代金券串号为: </strong>{0}</p>
		<p><strong>代金劵抵扣金额：</strong>{1}元</p>
		<p><strong>代金劵使用范围：</strong>使用此优惠券购买校园先锋在线商店线上销售的指定商品时可以抵值{1}元现金使用。</p>
		<p><strong>代金劵使用方法：</strong>请您登录<a href="http://shop.edu.cn" target="_blank">校园先锋在线商店（shop.edu.cn）</a>，选择购买您需要的商品，在订单确认页的下方会出现“<strong>使用代金券抵消部分金额</strong>”。点开后填入相应信息，提交代金券信息即可抵消部分金额。</p>
		<p style="border-bottom:solid 1px #e6e6e5"></p>
		<p><strong>提醒：</strong>该代金券仅能使用一次，不可重复或累加。请在2014年1月31日前使用。</p>
		<p>了解更多请关注 <span style="font-size: 14px; color: #ff0000; font-weight: bold;">新浪微博<a href="http://weibo.com/studenthero" target="_blank" style="color: #ff0000;">@赛尔校园先锋</a>&nbsp;&nbsp;&nbsp;<a href="http://wp.qq.com/wpa/qunwpa?idkey=36ce730a8de0297264dfff03ad6108219bd34de27a637add58528b9b4bee60fe" target="_blank" style="color: #ff0000;">QQ群：186155896</a></span></p>
	</td></tr>
	<tr><td style="padding-top:10px; color:#999b9e; border-top:solid 2px #e6e6e5">此为系统邮件，请勿回复<br />您在购买过程中有任何疑问，请查阅 <a href="{4}">帮助中心</a> 或联系客服电话：400-676-7170 (周一至周五8:30-17:15 节假日除外)。<br />
		用态度面对，用品质生活！关注校园先锋微博<a href="http://weibo.com/studenthero" target="_blank">@赛尔校园先锋</a> 更多交流，更多期待！校园先锋真心和所有先锋用户一起成长！
	</td></tr>
</table>
</body>
</html>';

	public static $Activity19CouponMailHeader = "先锋四周年调查问卷代金券";
	public static $Activity19CouponMailBody = '<html>
<body>
<table style="font-size:14px; font-family:微软雅黑, Arial">
	<tr><td style="font-size:26px; font-weight:bold"><img src="http://content.shop.edu.cn/images/logo.gif" alt="校园先锋 STUDENT HERO" /></td></tr>
	<tr><td style="font-size:16px; padding:5px 0"><strong>提示：</strong>这封邮件是您参与校园先锋《先锋四周年•有奖调查》的获奖依据，请妥善保存此邮件，您购买软件时将会使用邮件中的代金券串号。</td></tr>
	<tr><td style="border:solid 1px #e6e6e5; padding:10px">
		<p>亲爱的用户, <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;您好！</p>
		<p>2014年，是先锋的第四个年头。如果先锋君身在校园，也将是要毕业的节奏。</p>
		<p>这四年来，我们逐渐强大，有越来越多一流的国际厂商加入先锋事业。</p>
		<p>这四年来，我们逐渐壮大，拥有越来越多的支持者。</p>
		<p>无论你是学生、老师、厂商还是已经毕业的先锋学长，先锋君都欢迎你提出建议，助成长一臂之力。</p>
		<p>感谢您参与“先锋四周年•有奖调查”分享活动，我们随机赠送您{1}元代金劵。</p>
		<p>以下是代金劵的详细信息：</p>
		<p style="font-size:14px"><strong>代金券串号为: </strong>{0}</p>
		<p><strong>代金劵抵扣金额：</strong>{1}元。</p>
		<p><strong>代金劵使用范围：</strong>使用此优惠券购买校园先锋在线商店线上销售的软件商品可抵值{1}元。</p>
		<p><strong>代金劵使用方法：</strong>请您登录<a href="http://shop.edu.cn" target="_blank">校园先锋在线商店（shop.edu.cn）</a>，选择购买您需要的商品，在订单确认页的下方会出现“<strong>使用代金券抵消部分金额</strong>”。点开后填入相应信息，提交代金券信息即可抵消部分金额。</p>
		<p style="border-bottom:solid 1px #e6e6e5"></p>
		<p><strong>提醒：</strong>该代金券仅能使用一次，不可重复或累加。请在2014年5月4日前使用。</p>
		<p>了解更多请关注 <span style="font-size: 14px; color: #ff0000; font-weight: bold;">新浪微博<a href="http://weibo.com/studenthero" target="_blank" style="color: #ff0000;">@赛尔校园先锋</a>&nbsp;&nbsp;&nbsp;<a href="http://wp.qq.com/wpa/qunwpa?idkey=36ce730a8de0297264dfff03ad6108219bd34de27a637add58528b9b4bee60fe" target="_blank" style="color: #ff0000;">QQ群：186155896</a></span></p>
	</td></tr>
	<tr><td style="padding-top:10px; color:#999b9e; border-top:solid 2px #e6e6e5">此为系统邮件，请勿回复<br />您在购买过程中有任何疑问，请查阅 <a href="{4}">帮助中心</a> 或联系客服电话：400-676-7170 (周一至周五8:30-17:15 节假日除外)。<br />
		用态度面对，用品质生活！关注校园先锋微博<a href="http://weibo.com/studenthero" target="_blank">@赛尔校园先锋</a> 更多交流，更多期待！校园先锋真心和所有先锋用户一起成长！
	</td></tr>
</table>
</body>
</html>';

public static $Activity18CouponMailHeader = "”先锋开学季 少年，武装起来！“活动代金券！";
public static $Activity18CouponMailBody = '<html>
<body>
<table style="font-size:14px; font-family:微软雅黑, Arial">
	<tr><td style="font-size:26px; font-weight:bold"><img src="http://content.shop.edu.cn/images/logo.gif" alt="校园先锋 STUDENT HERO" /></td></tr>
	<tr><td style="font-size:16px; padding:5px 0"><strong>提示：</strong>这封邮件是您参与校园先锋《先锋开学季 少年，武装起来！》活动的获奖依据，请妥善保存此邮件，您购买软件时将会使用邮件中的代金券串号。</td></tr>
	<tr><td style="border:solid 1px #e6e6e5; padding:10px">
		<p>亲爱的{2}, <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;您好！</p>
		<p>感谢您参与我们的先锋开学季网站活动，选择校园先锋来购买您心仪的Apple商品，特此赠送您价值{1}元的代金券。以下是代金劵的详细信息：</p>
		<p style="font-size:14px"><strong>代金券串号为: </strong>{0}</p>
		<p><strong>购买地址：<a target="_blank" href="{5}">{5}</a></strong></p>
		<p><strong>代金劵抵扣金额：</strong>{1}元</p>
		<p><strong>代金劵使用范围：</strong>使用此优惠券购买{3} 时可以抵值{1}元现金使用。</p>
		<p><strong>使用方法：</strong>请您登录<a href="http://shop.edu.cn" target="_blank">校园先锋在线商店（shop.edu.cn）</a>，选择您需要兑换的商品，在订单确认页的下方会出现“<strong>使用代金券抵消部分金额</strong>”。点开后填入相应信息，提交代金券信息即可抵消部分金额。</p>
		<p style="border-bottom:solid 1px #e6e6e5"></p>
		<p><strong>提醒：</strong>该代金券仅能使用一次，不可重复使用。使用截止时间为{4}。</p>
	</td></tr>
	<tr><td style="padding-top:10px; color:#999b9e; border-top:solid 2px #e6e6e5">
		<p>如需了解更多关于产品信息，您可以<a href="http://help.shop.edu.cn/" target="_blank">点此查看帮助网页</a>或与我们的客服人员取得联系，客服电话：400-676-7170(周一至周五8:30-17:15 节假日除外）</p>
		<p>欢迎继续支持我们的活动 QQ群：186155896 新浪微博<a href="http://weibo.com/studenthero" target="_blank">@赛尔校园先锋</a>
		【用态度面对，用品质生活！<a href="http://www.shop.edu.cn" target="_blank">www.shop.edu.cn</a>更多交流，更多期待！校园先锋真心和所有先锋用户一起成长！】</p>
	</td></tr>
</table>
</body>
</html>';

	/**
	 * 代理商批量统收密钥邮件
	 * {0}	图片地址
	 * {1}	用户名称
	 * {2}	产品名称
	 * {3}	订单及密钥列表
	 * {4}	主站地址
	 * {5}	帐号
	 * {6}	身份证
	 * {7}	电话
	 */
public static $UcSellMailHeader = "产品授权邮件 - 校园先锋";
public static $UcSellMailBody = '<html>
									<body>
										<table style="font-size:14px; font-family:微软雅黑, Arial">
											<tr><td style="font-size:26px; font-weight:bold"><img src="{0}/images/logo.gif" alt="校园先锋" /></td></tr>
											<tr><td style="font-size:16px; padding:5px 0"><strong>提示：</strong>这是您加入赛尔校园先锋并购买软件密钥的依据，请您妥善保存此邮件，在您安装软件时将会使用邮件中的密钥。</td></tr>
											<tr><td style="border:solid 1px #e6e6e5; padding:10px">
												<p>亲爱的{1}用户，您好！<br />感谢您加入赛尔校园先锋并购买产品，以下是您购买产品的详细信息： </p>
												<p><strong>产品名称:</strong> {2}</p>
												<p><strong>产品密钥信息:</strong></p>
												{3}
												(请您妥善保管产品密钥，如果不慎丢失，您可以登录<a href="{4}">{4}</a>，进入会员中心→已购软件→点击“重发密钥”，密钥将重新发到您的注册邮箱中。)
												</br><strong>产品下载地址:</strong>{13}如有任何问题请致电: 400-676-7170 （周一至周五 8:30-17:15, 节假日除外），或发邮件至service@shop.edu.cn。
												<p style="border-bottom:solid 1px #e6e6e5"></p>
												<p>购买者姓名：{1}</p>
												<p>购买者帐号：{5}</p>
												<p>身份证号：{6}</p>
												<p>电话号码：{7}</p>
											</td></tr>
											<tr><td style="padding:10px 0">校园先锋的产品仅限于中国内地高校学生或教师群体购买、使用，不得进行出售、转让、拍卖等交易行为，如经查证有倒卖的现象，您购买产品的密钥将有可能被回收，我们保留追究其法律责任的权利。感谢您使用正版软件！</td></tr>
											<tr><td style="padding-top:10px; color:#999b9e; border-top:solid 2px #e6e6e5">此为系统邮件，请勿回复<br />您在购买过程中有任何疑问，请查阅 <a href="{10}">帮助中心</a> 或联系客服电话：400-676-7170 （周一至周五 8:30-17:15, 节假日除外）。</td></tr>
										</table>
									</body>
								</html>';

	// win8.1
	public static $Win8_1LicenseMailHeader = "Windows 8.1 授权邮件 - 校园先锋";
	public static $Win8_1LicenseMailBody = '<html>
										<body>
											<table style="font-size:14px; font-family:微软雅黑, Arial">
												<tr><td style="font-size:26px; font-weight:bold"><img src="{0}/images/logo.gif" alt="校园先锋" /></td></tr>
												<tr><td style="border:solid 1px #e6e6e5; padding:10px">
													<p>亲爱的{2}用户,您好！<br />感谢您加入赛尔校园正版俱乐部并购买产品，以下是您购买产品的详细信息： </p>
													<p><strong>产品名称:</strong> {3}</p>
													<p><strong>产品密钥:</strong> {4} <br />(请您妥善保管产品密钥，如果不慎丢失，您可以登录<a href="{1}">{1}</a>，进入会员中心→已购软件→点击“重发密钥”，密钥将重新发到您的注册邮箱中。)</p>
													<p><strong>安装方法:</strong>请您按以下步骤完成升级安装Windows 8.1 专业版<br />
													1. 确保电脑配置符合安装要求，<a href="{11}#requirement" target="_blank">查看配置要求</a>；<br />
													2. 下载WindowsSetupBox.exe，<a href="http://download.shop.edu.cn/Microsoft/W8DL/websetup8_1.html" target="_blank">点击此处下载</a>；<br />
														(如果上述文字点击无效，请把下面网页地址复制到浏览器地址栏中打开：<br /><a href="http://download.shop.edu.cn/Microsoft/W8DL/websetup8_1.html" target="_blank">http://download.shop.edu.cn/Microsoft/W8DL/websetup8_1.html</a>)<br />
														如果您还无法下载WindowsSetupBox.exe，请<a href="http://item.shop.edu.cn/disc" target="_blank">点击这里</a>索取客户端介质。<br />
													3. 运行WindowsSetupBox.exe，按照程序提示下载安装Windows 8.1。
													</p>
													{12}
													<p style="border-bottom:solid 1px #e6e6e5"></p>
													<p>订单编号：{5}</p>
													<p>付款日期：{6}</p>
													<p style="border-bottom:solid 1px #e6e6e5"></p>
													<p>购买者姓名：{2}</p>
													<p>购买者帐号：{7}</p>
													<p>身份证号：{8}</p>
													<p>电话号码：{9}</p>
												</td></tr>
												<tr><td style="padding:10px 0">校园先锋的产品仅限于赛尔校园正版俱乐部成员购买、使用，不得进行出售、转让、拍卖等交易行为，如经查证有倒卖的现象，您购买产品的密钥将有可能被回收，我们保留追究其法律责任的权利。感谢您使用正版软件！</td></tr>
												<tr><td style="padding-top:10px; color:#999b9e; border-top:solid 2px #e6e6e5">此为系统邮件，请勿回复<br />您在购买过程中有任何疑问，请查阅 <a href="{10}">帮助中心</a> 或联系客服电话：400-676-7170 （周一至周五 8:30-17:15, 节假日除外，或发邮件至service@shop.edu.cn）。</td></tr>
											</table>
										</body>
									</html>';
}

class ByUser
{
	public static function Id($userId) // 1
	{
		return array(1, $userId);
	}
	public static function LoggedId() // 2
	{
		return array(2, LoggedUser::Id());
	}
	public static function Email($email) // 3
	{
		return array(3, $email);
	}
}

class ByOrder
{
	public static function Id($orderId) //4
	{
		return array(4, $orderId);
	}
}

class MailSender
{
	const A = "MailSenderA";
	const B = "MailSenderB";
}

/**
 * 用户状态 (user.status)
 *
 */
class UserStatus
{
	const Inactive = 0; // 锁定, 未邮件激活
	const Normal = 1; // 正常
	const Locked = 2; // 未激活, 人工锁定
}

/**
 * 用户认证 (tbuserauth)
 *
 */
class UserAuthentication
{
	const Unauthorized = 0; // 未认证
	const NoUpload = 1; // 已认证, 但未上传图片
	const Uploaded = 2; // 已上传图片
	const Authorized = 3; // 认证通过
	const Rejected = 4; // 认证驳回
}

class UserRole
{
	const Unauthorized = 0; // 未认证
	const NoUpload = 1; // 已认证, 但未上传图片
	const Uploaded = 2; // 已上传图片
	const Authorized = 3; // 认证通过
	const Rejected = 4; // 认证驳回
}

/**
 * 订单类型, 和Constants:$OrderTitle配合获得订单标号
 *
 */
class OrderType
{
	const ProductOfOnlineSale = 0; // ste.shop.edu.cn网站销售
	const ProductOfDirectSale = 1;
	const Invoice = 2;
	const Esale = 3;
	const EsaleOut = 4; // esale 出货订单
	const EsaleReturned = 5; // esale 退货订单
	const Unified = 6; // 统收销售订单
	const Gift = 7; // 赠送商品订单 e.g. 买Mac:Office送Win Office 2010，以及支付金额为0的订单
	const Group = 8; // 团购
	const Activity = 9; // 活动订单
}

/**
 * 订单状态 (order.status)
 *
 */
class OrderStatus
{
	const Unpaid = 0; 		// 未支付
	const Paid = 1; 		// 已支付
	const Cancelled = 3; 	// 已取消
	const Locked = 4; 		// 已经锁定
	const Checking = 5;		// 待审核
	const NoPass = 6;		// 审核未通过
	const Passed = 7;		// 审核通过
}

class PurchaseStatus
{
	const NotPurchase = 0;
	const Ordered = 1;
	const Purchased = 2;
}

/**
 * 退货订单状态
 *
 */
class ReturnStatus
{
	const Pending  = 0; //等待处理
	const Confirm  = 1; //确认退货
	const Rejected = 2; //拒绝退货
}
/**
 * 支付方式
 */
class PayMethod
{
	const Alipay = 1; // 支付宝
	const ChinaBank = 2; // 网银在线
	const Cash = 3; // 现金支付
	const Free = 4; // 免费订单
}

/**
 * 投递方式
 */
class DeliverMethod
{
	const Self = 0; // 自提
	const Letter = 1; // 平邮
	const Express = 2; // 快递
	const Postage = 3; // 平邮
	const EMS = 4; // EMS
	const sf = 5;
}
//
///**
// * 商品类型
// */
//class ProductType
//{
//	const ESDWindows = 0;
//	const ESDMac = 1;
//	const ESDMPL = 2;
//	const ShrinkWindows = 3;
//	const ShrinkMac = 4;
//	const ShrinkMPL = 5;
//}
/**
 * 商品类型
 */
class ProductType
{
	const SoftWare = 1;
	const Book = 2;
	const Disc = 3;
	const Peripheral = 4;
	const Activity12_1 = 5;/** 活动：activeity12(毕业季再启程)(7月4日~7月5日) 不需要支付快递费用的活动商品商品 **/
	const Activity12_2 = 6;/** 活动：activeity12 \ activeity14 需要支付快递费用的活动商品商品 **/
}

/**
 * 投递状态
 */
class DeliveryStatus
{
	const NoDelivery = 0; // 未投递
	const Delivered = 1; // 已投递
	const Received = 2; // 已收货
	const Noticed = 3; // 已经通知发货
}

/**
 * 首页幻灯状态
 */
class SlideshowStatus
{
	const Disabled = 0; // 不显示
	const Enabled = 1;  // 显示
}

/**
 * 对应数据库config表的configid
 */
class Config
{
	const Warehouse = 1; // 库存切换
	const Presell = 2; // 预售状态切换
	const MainDomain = 3; // 网站主域名
	const CommonAPI = 4; // 通用接口函数
	const AdobeCommonAPI = 5; // Adobe通用函数
	const ParameterBAuthorization = 6; // 带b参数用户是否直接通过验证(0=验证; 1=直接通过)
}

class ProductMainId
{
	const Photoshop = 1;
	const Acrobat = 2;
	const Flash = 3;
	const Dreamweaver = 4;
	const Illustrator = 5;
	const Fireworks = 6;
	const InDesign = 7;
	const DesignStandard = 8;
	const DesignPremium = 9;
	const WebPremium = 10;
}

/**
 * 用户类型
 */
class UserType
{
	const Student = 0; // 学生
	const NewStudent = 1; // 新生
	const Teacher = 2; // 教师
}

/**
 * 控制器类型
 */
class ControllerType
{
	const Front = 0; // 前台
	const Admin = 1; // 管理后台
}

/**
 * 数据库配置信息
 */
class DataConfig
{
	const Common = "DataCommon"; // common库
	const Trade = "DataTrade"; // trade库
	const CallTrade = "CallDataTrade";
	const Stock = "DataStock"; // stock库
	const Shop = "DataShop"; // shop库
	const Esale = "DataEsale"; // esale库
	const BulkMail = "DataBulkMail"; // shop库
	const Finance = "DataFinance"; // finance库
	const Monitor = "DataMonitor"; // monitor库
	const InternalApi = "DataInternalApi"; // internalapi库
	const ExternalApi = "DataExternalApi"; // externalapi库
}

/**
 * 密钥状态
 */
class LicenseStatus
{
	const In = 0; // 未出库
	const Out = 1; // 已出库
	const ReceiveStock = 12; // 已预留
	const EsaleStock = 13; // 代理商库存
}

/**
 * 密钥库存
 */
class Warehouse
{
	const A = 0; // A库
	const B = 1; // B库
	const Test = 3; // 测试库
}

/**
 * 代金券类型
 */
class CouponType
{
	const ForAll = 0; // 所有adobe商品
	const ForSpecial = 1; // 指定某一款商品
}

/**
 * 代金券状态
 */
class CouponStatus
{
	const Available = 1; // 可用
	const NotExist = -1; // 不存在
	const NotActivated = -2; // 未激活
	const Disabled = -3; // 已过期
	const Locked = -4; // 已锁定
	const UseUp = -5; // 已耗尽
	const Illegal = -6; // 跨代金券类型, 非法使用
}

/**
 * 验证方式
 */
class AuthType
{
	const Normal = 0; // 正常验证 国政通
	const Special = 1; // 特殊用户
	const EduEmail = 2; // EDU邮箱认证
	const Manual = 3; // 人工认证
}

/**
 * 收款状态
 */
class PaymentStatus
{
	const Uncheck = 0; // 未对账
	const Unpaid = 1; // 未收款
	const Paid = 2; // 已收款
	const NotMatch = 3; // 金额不匹配
}

class EsaleRole
{
	const Unauthorized = 0; // 未验证
	const Authorizing = 1; // 等待验证
	const Frozen = 2; // 冻结, 不能进货, 可以销售库存
	const Locked = 3; // 不能使用esale功能
	const Authorized = 4; // 验证通过
	const Special = 5; // 特殊渠道
	const Rejected = 6; // 拒绝通过
}

class EsalePriceLevel
{
	const Level1 = 1; // 普通
	const Level2 = 2; // 中级
	const Level3 = 3; // 高级
}

class AppId
{
	const MainSite = 1;// shop.edu.cn 主站
	const T360 = 3;
	const Xunlei = 4;
	const EsaleSale = 7;// 代理商AE销售
	const Unified = 9;// 统收销售
}

class PromotionRule
{
	const SkipAuthorize = 1; // 不用验证
}

class PurchaseAuthLimit
{
	const AllowAll = 0;
	const TeacherAndStudent = 2;
	const Student = 1;
}

class ProductCategory
{
	const Single = 1; // 单品
	const Package = 2; // 套装
	const Multiple = 3; // 组合商品
	const Peripheral = 4; // 周边商品
	const SimplePacking = 5; // 简包装 (e.g. office mac)
}

class ProductId
{
	const Insurance = 33; //硬件意外维修
	const Officeformac = 101; //Office for Mac
	const Win8pro = 218; //win8 pro
	const Office2013 = 411; //win8 pro
}

class StockId
{
	const EAStock = 93; //从EA出库的license stock
	const Insurance = 99; //硬件意外维修保险单号库存
}

class CouponBatch
{
	const Win7Coupon = 15; // win7 抽奖页面用
	const GiftCoupon = 25; // 活动赠送代金券，总数1000个, 活动日期： 2012-09-11 ~ 2012-09-21
}

class Event
{
	const Win7Coupon = 1; // win7 抽奖
	const GiftCoupon = 2; // 活动赠送代金券，总数1000个, 活动日期： 2012-09-11 ~ 2012-09-21
	const Win8preSaleActivity = 3; // 领取15元代金券 2012-10-26 ~ 2012-10-30
	const GiftCoupon2 = 4; // 购买win8赠送30元代金券 2012-10-26 ~ 2012-10-30
	const TryingLuck = 5; // 试试手气
	const ChristmasA = 6; // 圣诞活动期间注册赠送兑换码
	const Christmas30 = 7; // 圣诞活动期间订购软件赠送兑换码
	const NewYearDay = 8; // 元旦
}


class SqlExcuteStatus
{
	const Unexecuted = 1; // 未执行
	const Executed = 2; // 已执行
	const Error = 3; // 错误
}

/**
 * 微博活动
 */
class WeiboEvent
{
	const Win8CutPrice = 1; // 11月14日  关注微博win8减价
}

class EventStatus
{
	const Did = 0;
	const Lost = 1;
	const Win = 2;
	const NotEnoughPoints = 3; //积分不够
}

class Brand
{
	const Microsoft = 1;
	const Adobe = 2;
	const Apple = 3;
}

class ProductSeriesStatus
{
	const Unreleased = 0; // 未发布
	const Released = 1; // 发布
	const NoDetail = 2; // 发布但未上线
	const Outofstock = 3; // 缺货
}

//TODO Debug mode
if (Constants::$Debug)
{
	Constants::$OrderTitle = array( // 订单打头字母
		0 => "TEST-AC", // 商品订单
		1 => "TEST-AZ", // 直销订单
		2 => "TEST-AB", // 发票订单
		3 => "TEST-AS", // esale 进货订单
		4 => "TEST-AE", // esale 销售订单
		5 => "TEST-AR",  // esale 退货订单
		6 => "TEST-AU",  // 统收销售订单
		7 => "TEST-AG"  // Gift 订单
	);
}
?>