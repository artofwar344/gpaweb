<?php
$routesDetail = array(
	'customer' => function()
	{
		Route::group(array('before' => 'auth'), function()
		{
			Route::get('/down/{file}', 'Customer\HomeController@down');
		});
		//	Route::controller(Controller::detect());

		Route::get('/', 'Customer\HomeController@index');
		Route::get('/help/client', 'Customer\HelpController@client');
//		Route::get('/help/client3', 'Customer\HelpController@client3');
		Route::get('/help/faq/{categoryid}', 'Customer\HelpController@faqdetail');
		Route::get('/help/faq', 'Customer\HelpController@faq');
		Route::get('/help/kms', 'Customer\HelpController@kms');
		Route::get('/help/codeNew', 'Customer\HelpController@codeNew');
		Route::get('/help/errorcode', 'Customer\HelpController@errorcode');
		Route::get('/download.html', 'Customer\DownloadController@index');
		Route::get('/download/{name}.html', 'Customer\DownloadController@detail');
		Route::any('/download/file', 'Customer\DownloadController@file');

	},
	'customerManage' => function ()
	{
		Route::get('/', 'CustomerManage\HomeController@getIndex');
		Route::get('captcha', function()
		{
			Ca\Captcha::generate();
		});
		Route::any('filemanager', function()
		{
			Ca\Filemanager::index();
		});
		Route::post('filemanager/upload', function()
		{
			Ca\Filemanager::upload();
		});

		Route::controllers(array(
			'home' => 'CustomerManage\HomeController',
			'department' => 'CustomerManage\DepartmentController',
			'user' => 'CustomerManage\UserController',
			'userinfo' => 'CustomerManage\UserinfoController',
			'manager' => 'CustomerManage\ManagerController',
			'app' => 'CustomerManage\AppController',
			'tag' => 'CustomerManage\TagController',
			'sensitive' => 'CustomerManage\SensitiveController',
			'ad' => 'CustomerManage\AdController',
			'soft' => 'CustomerManage\SoftController',
			'softlog' => 'CustomerManage\SoftlogController',
			'articlecategory' => 'CustomerManage\ArticlecategoryController',
			'article' => 'CustomerManage\ArticleController',
			'knowscategory' => 'CustomerManage\KnowscategoryController',
			'knowssubcategory' => 'CustomerManage\KnowssubcategoryController',
			'knows' => 'CustomerManage\KnowsController',
			'answer' => 'CustomerManage\AnswerController',
			'documentcategory' => 'CustomerManage\DocumentcategoryController',
			'documentsubcategory' => 'CustomerManage\DocumentsubcategoryController',
			'document' => 'CustomerManage\DocumentController',
			'meeting' => 'CustomerManage\MeetingController',
			'meetingenroll' => 'CustomerManage\MeetingenrollController',
			'reportdocument' => 'CustomerManage\ReportdocumentController',
			'reportquestion' => 'CustomerManage\ReportquestionController',
			'reportanswer' => 'CustomerManage\ReportanswerController',
			'reportmeetingcomment' => 'CustomerManage\ReportmeetingcommentController',
			'product' => 'CustomerManage\ProductController',
			'productpkg' => 'CustomerManage\ProductpkgController',
			'productpkguser' => 'CustomerManage\ProductpkgUserController',
			'productpermission' => 'CustomerManage\ProductpermissionController',
			'key' => 'CustomerManage\KeyController',
			'exchangecode' => 'CustomerManage\ExchangecodeController',
			'keyusage' => 'CustomerManage\KeyusageController',
			'departmentkeyassign' => 'CustomerManage\DepartmentkeyassignController',
			'keyassign' => 'CustomerManage\keyassignController',
			'subkeyassign' => 'CustomerManage\SubkeyassignController',
			'chartkeycount' => 'CustomerManage\ChartkeycountController',
			'chartkeyassign' => 'CustomerManage\ChartkeyassignController',
			'chartkeyusage' => 'CustomerManage\ChartkeyusageController',
			'chartuser' => 'CustomerManage\ChartuserController',
			'chartsoft' => 'CustomerManage\ChartsoftController',
			'chartproductactivate' => 'CustomerManage\ChartproductactivateController',
			'charterror' => 'CustomerManage\CharterrorController',
			'activationstatus' => 'CustomerManage\ActivationstatusController',
			'faq' => 'CustomerManage\FaqController',
			'faqcategory' => 'CustomerManage\FaqcategoryController',
			'autoassign' => 'CustomerManage\AutoAssignController',
			'autoassignuser' => 'CustomerManage\AutoAssignUserController',
			'helpcategory' => 'CustomerManage\HelpcategoryController',
			'helpedit' => 'CustomerManage\HelpeditController',
		));
	},

	'manage' => function()
	{
		Route::get('/', 'Manage\HomeController@getIndex');
		Route::get('captcha', function()
		{
			Ca\Captcha::generate();
		});
		Route::any('filemanager', function()
		{
			Ca\Filemanager::index();
		});
		Route::post('filemanager/upload', function()
		{
			Ca\Filemanager::upload();
		});

		Route::controllers(array(
			'home' => 'Manage\HomeController',
			'activationerror' => 'Manage\ActivationerrorController',
			//'ad' => 'Manage\AdController',
			'adminer' => 'Manage\AdminerController',
			//'app' => 'Manage\AppController',
			//'appcategory' => 'Manage\AppcategoryController',
			//'article' => 'Manage\ArticleController',
			'sensitive' => 'Manage\SensitiveController',
			'createdatabase' => 'Manage\CreatedatabaseController',
			'customer' => 'Manage\CustomerController',
			'customerparams' => 'Manage\CustomerparamsController',
			'customersetting' => 'Manage\CustomersettingController',
			'customerstatistics' => 'Manage\CustomerstatisticsController',
			//'database' => 'Manage\DatabaseController',
			//'deletedabase' => 'Manage\DeletedabaseController',
			//'department' => 'Manage\DepartmentController',
			'keyassign' => 'Manage\KeyassignController',
			//'manager' => 'Manage\ManagerController',
			//'module' => 'Manage\ModuleController',
			//'product' => 'Manage\ProductController',
			'soft' => 'Manage\SoftController',
			'softversion' => 'Manage\SoftversionController',
			'softcategory' => 'Manage\SoftcategoryController',
			'softsubcategory' => 'Manage\SoftsubcategoryController',
			'softversionhistory' => 'Manage\SoftversionhistoryController',

		));
	},

	'user' => function()
	{
		Route::get('captcha', function()
		{
			Ca\Captcha::generateFromPng();
		});
		Route::get('/', 'User\HomeController@index');
		Route::any('/login', 'User\HomeController@login');
		Route::any('/register', 'User\HomeController@register');
		Route::any('/forgetpwd', 'User\HomeController@forgetpwd');
		Route::any('/logout', 'User\HomeController@logout');
		Route::any('/regresult', 'User\HomeController@regresult');
		Route::any('/resetpwd', 'User\HomeController@resetpwd');

		Route::post('/tjjwauth', 'User\TjjwauthController@index');
		Route::post('/tjjwauth/validate', 'User\TjjwauthController@validate');

		Route::post('/ldapauth', 'User\LdapauthController@index');
		Route::post('/ldapauth/validate', 'User\LdapauthController@validate');

		Route::post('/idpauth', 'User\IdpauthController@index');
		Route::post('/idpauth/validate', 'User\IdpauthController@validate');

		Route::post('/radiusauth', 'User\RadiusauthController@index');
		Route::post('/radiusauth/validate', 'User\RadiusauthController@validate');

		Route::post('/casauth', 'User\CasauthController@index');
		Route::post('/casauth/validate', 'User\CasauthController@validate');


		Route::post('/jsdcasauth', 'User\JsdCasauthController@index');
		Route::post('/jsdcasauth/validate', 'User\JsdCasauthController@validate');

		Route::post('/apiauth', 'User\ApiauthController@index');
		Route::post('/apiauth/validate', 'User\ApiauthController@validate');

		Route::post('/webserviceauth', 'User\WebserviceauthController@index');
		Route::post('/webserviceauth/validate', 'User\WebserviceauthController@validate');

		Route::group(array('before' => 'auth'), function()
		{
			Route::any('/profile', 'User\HomeController@profile');
			Route::any('/changepwd', 'User\HomeController@changepwd');
		});
	},

	'soft' => function()
	{
		Route::get('/', 'Soft\HomeController@index');
		Route::get('/top/{categoryid}', 'Soft\SoftController@topCategory');
		Route::get('/category/{categoryid}', 'Soft\SoftController@Category');
		Route::get('/newest', 'Soft\SoftController@lastest');
		Route::get('/soft/{softid}.html', 'Soft\SoftController@detail');
		Route::get('/soft/download/{softid}', 'Soft\SoftController@download');
		Route::get('/news', 'Soft\NewsController@newsList');
		Route::get('/news/list-{categoryid}.html', 'Soft\NewsController@category');
		Route::get('/news/{articleid}.html', 'Soft\NewsController@detail');
		Route::any('/search', 'Soft\SearchController@index');

	},

	'share' => function()
	{
		Route::get('/crossdomain.xml', function()
		{
			return file_get_contents(app_path() . '/crossdomain.xml');
		});

		Route::get('/', 'Share\HomeController@getIndex');
		Route::post('/validationEngine', 'Share\HomeController@postValidationEngine');

		Route::get('/document/detail', 'Share\DocumentController@getDetail');
		Route::get('/document/detailpage', 'Share\DocumentController@getDetailpage');
		Route::any('/document/preview', 'Share\DocumentController@anyPreview');
		Route::get('/document/list/{id}','Share\DocumentController@getDocumentList')->where(array('id' => '[0-9]+'));
//		Route::post('/document/check_report', 'Share\DocumentController@postCheckReport');
		Route::get('/topic', 'Share\DocumentController@getTopic');
		Route::get('/{name}','Share\DocumentController@getSubcateList')->where(array('name' => '(pro||edu||form)'));
		Route::get('/topic/detail', 'Share\DocumentController@getTopicDetail');

		Route::get('/knows', 'Share\KnowsController@getIndex');
		Route::get('/knows/question', 'Share\KnowsController@getQuestion');
		Route::get('/knows/list/{id}', 'Share\KnowsController@getKnowsList')->where(array('id' => '[0-9]+'));
		Route::get('/knows/tag/{id}', 'Share\KnowsController@getTag')->where(array('id' => '[0-9]+'));
		Route::post('/knows/accept', 'Share\KnowsController@postAccept');
		Route::any('/knows/search', 'Share\KnowsController@anySearch');
//		Route::post('/knows/check_report', 'Share\KnowsController@postCheckReport');

		Route::get('/meeting', 'Share\MeetingController@getIndex');
		Route::get('/meeting/detail', 'Share\MeetingController@getDetail');
		Route::get('/meeting/active', 'Share\MeetingController@getActive');
		Route::get('/meeting/over', 'Share\MeetingController@getOver');
		Route::get('/meeting/tag/{id}', 'Share\MeetingController@getTag')->where(array('id' => '[0-9]+'));

		Route::any('/search', 'Share\SearchController@anyIndex');

		Route::post('/usercenter/topic/ajaxcheckdocumenturl', 'Share\UserCenterController@postAjaxCheckDocumentUrl');

		Route::post('/usercenter/document/uploads', 'Share\UserCenterController@postDocumentUploads');

		Route::group(array('before' => 'auth'), function()
		{
			Route::post('/message/newmessage', 'Share\MessageController@postNewMessage');

			Route::post('/document/favorites', 'Share\DocumentController@postFavorites');
			Route::get('/document/downloads', 'Share\DocumentController@getDownloads');
			Route::post('/document/rating', 'Share\DocumentController@postRating');
			Route::post('/document/report', 'Share\DocumentController@postReport');
			Route::post('/document/topic/favorites', 'Share\DocumentController@postTopicfavorites');

//			Route::any('/knows/new', 'Share\KnowsController@anyNewQuestion');
			Route::get('/knows/new', 'Share\KnowsController@anyNewQuestion');
//			Route::post('/knows/answer', 'Share\KnowsController@postAnswer');
			Route::post('/knows/favorites', 'Share\KnowsController@postFavorites');
			Route::post('/knows/similarquestion', 'Share\KnowsController@postSimilarQuestion');
			Route::post('/knows/sensitivecheck', 'Share\KnowsController@postSensitiveCheck');
			Route::post('/knows/report', 'Share\KnowsController@postReport');
//			Route::post('/knows/askmore', 'Share\KnowsController@postAskMore');
//			Route::post('/knows/updatequestion', 'Share\KnowsController@postUpdateQuestion');
//			Route::post('/knows/updatecategory', 'Share\KnowsController@postUpdateCategory');

			Route::post('/meeting/apply', 'Share\MeetingController@postApply');
//			Route::post('/meeting/comment', 'Share\MeetingController@postComment');
			Route::post('/meeting/report', 'Share\MeetingController@postReport');

			Route::get('/usercenter', 'Share\UserCenterController@getDocument');

			Route::get('/usercenter/message', 'Share\UserCenterController@getMessageHistory');
			Route::get('/usercenter/message/history', 'Share\UserCenterController@getMessageHistory');
			Route::post('/usercenter/message/delete', 'Share\UserCenterController@postMessageDelete');

			Route::get('/usercenter/document', 'Share\UserCenterController@getDocument');
			Route::get('/usercenter/document/favorite', 'Share\UserCenterController@getDocumentFavorite');
			Route::get('/usercenter/document/download', 'Share\UserCenterController@getDocumentDownload');
//			Route::any('/usercenter/document/publish', 'Share\UserCenterController@anyDocumentPublish');
			Route::get('/usercenter/document/publish', 'Share\UserCenterController@anyDocumentPublish');
			Route::post('/usercenter/document/list', 'Share\UserCenterController@postDocumentList');
			Route::post('/usercenter/document/folderlist', 'Share\UserCenterController@postDocumentFolderList');
			Route::post('/usercenter/document/move', 'Share\UserCenterController@postDocumentMove');
			Route::post('/usercenter/document/new', 'Share\UserCenterController@postDocumentNew');
			Route::post('/usercenter/document/changename', 'Share\UserCenterController@postDocumentChangeName');
			Route::post('/usercenter/document/delete', 'Share\UserCenterController@postDocumentDelete');
			Route::post('/usercenter/document/deletedownload', 'Share\UserCenterController@postDocumentDeleteDownload');
			Route::post('/usercenter/document/checkfreespace', 'Share\UserCenterController@postCheckfreespace');
			Route::post('/usercenter/document/attachment', 'Share\UserCenterController@postDocumentAttachment');
			Route::post('/usercenter/document/deleteattachment', 'Share\UserCenterController@postDocumentDeleteAttachment');

			Route::get('/usercenter/topic', 'Share\UserCenterController@getTopic');
//			Route::any('/usercenter/topic/new', 'Share\UserCenterController@anyTopicNew');
			Route::get('/usercenter/topic/new', 'Share\UserCenterController@anyTopicNew');
			Route::post('/usercenter/topic/delete', 'Share\UserCenterController@postTopicDelete');
			Route::post('/usercenter/topic/deletedocument', 'Share\UserCenterController@postTopicDeleteDocument');
			Route::post('/usercenter/topic/adddocument', 'Share\UserCenterController@postTopicAddDocument');
//			Route::any('/usercenter/topic/modify', 'Share\UserCenterController@anyTopicModify');
			Route::get('/usercenter/topic/modify', 'Share\UserCenterController@anyTopicModify');
			Route::get('/usercenter/topic/favorite', 'Share\UserCenterController@getTopicFavorite');
			Route::post('/usercenter/topic/deletefavorite', 'Share\UserCenterController@postTopicDeleteFavorite');

			Route::get('/usercenter/knows', 'Share\UserCenterController@getKnowsAttention');
			Route::get('/usercenter/knows/attention', 'Share\UserCenterController@getKnowsAttention');
			Route::get('/usercenter/knows/ask', 'Share\UserCenterController@getKnowsAsk');
			Route::get('/usercenter/knows/ask/{condition}', 'Share\UserCenterController@getKnowsAsk')->where(array('condition' => '(all||answered||unanswered)'));
			Route::get('/usercenter/knows/answer', 'Share\UserCenterController@getKnowsAnswer');
			Route::get('/usercenter/knows/answer/{condition}', 'Share\UserCenterController@getKnowsAnswer')->where(array('condition' => '(all||answered||unanswered)'));
			Route::get('/usercenter/knows/favorite', 'Share\UserCenterController@getKnowsFavorite');
			Route::get('/usercenter/knows/favorite/{condition}', 'Share\UserCenterController@getKnowsFavorite')->where(array('condition' => '(all||answered||unanswered)'));
			Route::post('/usercenter/knows/deletefavorite', 'Share\UserCenterController@postKnowsDeleteFavorite');
			Route::post('/usercenter/knows/addattentiontag', 'Share\UserCenterController@postKnowsAddAttentionTag');
			Route::post('/usercenter/knows/deleteattentiontag', 'Share\UserCenterController@postKnowsDeleteAttentionTag');
			Route::post('/usercenter/knows/addattentioncategory', 'Share\UserCenterController@postKnowsAddAttentionCategory');
			Route::post('/usercenter/knows/deleteattentioncategory', 'Share\UserCenterController@postKnowsDeleteAttentionCategory');

			Route::get('/usercenter/meeting', 'Share\UserCenterController@getMeeting');
			Route::get('/usercenter/meeting/{condition}', 'Share\UserCenterController@getMeeting')->where(array('condition' => '(all||active||over)'));

			Route::group(array('before' => 'csrf'), function() {
				Route::post('/knows/new', 'Share\KnowsController@anyNewQuestion');
				Route::post('/knows/answer', 'Share\KnowsController@postAnswer');
				Route::post('/knows/askmore', 'Share\KnowsController@postAskMore');
				Route::post('/knows/updatequestion', 'Share\KnowsController@postUpdateQuestion');
				Route::post('/knows/updatecategory', 'Share\KnowsController@postUpdateCategory');

				Route::post('/meeting/comment', 'Share\MeetingController@postComment');

				Route::post('/usercenter/document/publish', 'Share\UserCenterController@anyDocumentPublish');
				Route::post('/usercenter/topic/new', 'Share\UserCenterController@anyTopicNew');
				Route::post('/usercenter/topic/modify', 'Share\UserCenterController@anyTopicModify');
			});

		});
	},

	'api' => function()
	{
		Route::controllers(array(
			'user' => 'Api\UserController'
		));
		Route::post('/license', 'Api\LicenseController@license');
		Route::post('/licenseNew', 'Api\LicenseController@licenseNew');
		//Route::get('/test', 'Api\LicenseController@test');
	},

	'client' => function()
	{
		Route::controllers(array(
			'bugreport' => 'ClientContent\BugreportController',
			'info' => 'ClientContent\InfoController',
			'soft' => 'ClientContent\SoftController',
			'token' => 'ClientContent\TokenController',
			'update' => 'ClientContent\UpdateController'
		));
	},

	'activate' => function()
	{
		Route::post('/activate/end', 'Activate\ActivateController@end');
		Route::get('/', 'Activate\HomeController@index');
		Route::post('/home/products', 'Activate\HomeController@products');
		Route::post('/activate/applykey', 'Activate\ActivateController@applykey');
		Route::post('/activate/begin', 'Activate\ActivateController@begin');
		Route::get('/request', 'Activate\RequestController@index');
		Route::get('/usage', 'Activate\UsageController@index');

	}
//	Route::group(array('before' => 'auth'), function()
//	{
//		Route::get('/', 'Activate\HomeController@index');
//		Route::post('/home/products', 'Activate\HomeController@products');
//		Route::post('/activate/applykey', 'Activate\ActivateController@applykey');
//		Route::post('/activate/begin', 'Activate\ActivateController@begin');
//		Route::get('/request', 'Activate\RequestController@index');
//		Route::get('/usage', 'Activate\UsageController@index');
//	});
);
