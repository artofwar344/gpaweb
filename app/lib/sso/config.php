<?php

include_once (dirname(__FILE__).'/../local/login.php');
include_once (dirname(__FILE__).'/../local/logout.php');
include_once (dirname(__FILE__).'/../local/code.php');
include_once (dirname(__FILE__).'/../local/validate.php');

define("___casServerUrlPrefix", 'http://login.sust.edu.cn/cas/');
define("___casServerLoginUrl", 'http://login.sust.edu.cn/cas/login');
define("___casServerLogoutUrl", 'http://login.sust.edu.cn/cas/logout');
define("___casServerStatusUrl", 'http://login.sust.edu.cn/cas/status.htm');

define("___casServerTimeout", 0);

define("___appServerName", 'http://user.ms.sust.edu.cn');
define("___appServerContextPath", '/login');

define("___appServerLoginUserKey", 'username');
define("___appServerLoginPassKey", 'password');
define("___appServerLoginCodeKey", 'code');

define("___appServerLoginUrl", 'http://user.ms.sust.edu.cn/login');
define("___appServerWelcomeUrl", 'http://ms.sust.edu.cn');

define("___appServerSecurityResourceRegex", '^/clientWeb/(?!((sso/)|(images/)|(css/)|(js/)))(.+(\.php)?(\?.+)?)?$');

define("___appServerLocalLoginUrl", 'http://user.ms.sust.edu.cn/sso/LocalLogin.php');

define("___appServerLocalLoginClass", "LocalLogin");
define("___appServerLocalLogoutClass", "LocalLogout");
define("___appServerLocalCodeClass", "LocalCode");
define("___appServerLocalValidateClass", "LocalValidate");

define("___appServerLoginUrlSuffix", "/sso/login.php");
define("___appServerLogoutUrlSuffix", "/sso/logout.php");
define("___appServerValidateUrlSuffix", "/sso/validate.php");
define("___appServerServiceUrlSuffix", "/sso/service.php");

define("___appCode", '');
?>