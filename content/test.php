<?php
// Load the settings from the central config file
require_once 'config.php';

// Load the CAS lib
require_once 'CAS.php';

// Enable debugging
//phpCAS::setDebug();

// Initialize phpCAS
phpCAS::client(CAS_VERSION_2_0, $cas_host, $cas_port, $cas_context);

//phpCAS::setServerLoginUrl('https://www.baidu.com/');

// NO SSL validation for the CAS server
phpCAS::setNoCasServerValidation();

// 登录成功后访问页面 不设置默认当前页面
// phpCAS::setServerLoginUrl("http://ip:port/xxx"); 

// 这里会检测服务器端的退出的通知，就能实现php和其他语言平台间同步登出了  
phpCAS::handleLogoutRequests();

// force CAS authentication
phpCAS::forceAuthentication();

// at this step, the user has been authenticated by the CAS server
// and the user's login name can be read with phpCAS::getUser().

// logout if desired
if (isset($_REQUEST['logout'])) {
	phpCAS::logout();
}

// for this test, simply print that the authentication was successfull
?>
<html>
  <head>
    <title>phpCAS simple client</title>
  </head>
  <body>
    <h1>Successfull Authentication!</h1>
    <dl style='border: 1px dotted; padding: 5px;'>
      <dt>Current script</dt><dd><?php print basename($_SERVER['SCRIPT_NAME']); ?></dd>
      <dt>session_name():</dt><dd> <?php print session_name(); ?></dd>
      <dt>session_id():</dt><dd> <?php print session_id(); ?></dd>
    </dl>
    <p>the user's login is <b><?php echo phpCAS::getUser(); ?></b>.</p>
    <p>phpCAS version is <b><?php echo phpCAS::getVersion(); ?></b>.</p>
    <p><a href="?logout=">Logout</a></p>
  </body>
</html>
