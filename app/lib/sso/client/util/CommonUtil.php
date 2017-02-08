<?php
define("CURRENT_URI", 'SsoClientCurrentURI');

define("SERVICE_URI", 'SsoClientServiceURI');

define("SESSSION_USERNAME_KEY", 'SsoClientUsername');
define("SESSSION_PASSWORD_KEY", 'SsoClientPassword');

class CommonUtil
{
	public static function encodeServiceURI($serviceURI)
	{
	    if (CommonUtil::isBlank($serviceURI)) return "";
	    
	    $encodedServiceURI = base64_encode(iconv('GB18030','UTF-8',$serviceURI));

	    return $encodedServiceURI;
	}

	public static function decodeServiceURI($encodedServiceURI)
	{
	    if (CommonUtil::isBlank($encodedServiceURI)) return "";

	    $serviceURI = base64_decode($encodedServiceURI);
	    
	    return $serviceURI;
	}
	
	public static function signOut($isSSO=false) {
		if ($isSSO) {
			$urlToSignOut = ___appServerName.___appServerContextPath."/sso/logout";
			header('Location: ' . $urlToSignOut);
		} else {
			$_SESSION[CURRENT_LOGIN_USER_KEY] = null;

			$loginUserKey = CommonUtil::isBlank(___appServerLoginUserKey)?"username":___appServerLoginUserKey;
			$loginPassKey = CommonUtil::isBlank(___appServerLoginPassKey)?"password":___appServerLoginPassKey;
			$_SESSION[$loginUserKey] = null;
			$_SESSION[$loginPassKey] = null;
							
			$_SESSION[SESSSION_USERNAME_KEY] = null;
			$_SESSION[SESSSION_PASSWORD_KEY] = null;
			
		session_unset();
		session_destroy();
			
		}
	}

	public static function isAuthentication() {
		$currentUser = CurrentLoginUser::getLoginUserAccount();
		return !CommonUtil::isBlank($currentUser);
	}
	
	public static function isCasAlive($url, $timeout = 0)
	{return true;
	    $response = CommonUtil::PerformHttpGet($url, true, $timeout);

	    if ($response != null
			&& strpos($response, "<meta name=\"description\" content=\"cas\" />") > 0)
		{
			return true;
	    }
	    else
	    {
			return false;
	    }
	}
	
	public static function isBlank($str)
	{
	    return empty($str) || $str=="";
	}
	
	public static function indexOf($haystack, $needle, $offset=null) {
		$ret = strpos($haystack, $needle, $offset);
		
		if ($ret==null)
			return -1;
			
		return $ret;
	}

	public static function startsWith($haystack, $needle)
	{
	    $length = strlen($needle);
	    return (substr($haystack, 0, $length) === $needle);
	}
	
	public static function endsWith($haystack, $needle)
	{
	    $length = strlen($needle);
	    $start  = $length * -1;
	    return (substr($haystack, $start) === $needle);
	}

	public static function PerformHttpGet($url, $requireHttp200 = true, $timeout = 0) {
		$ch = curl_init();//初始化curl
		curl_setopt($ch, CURLOPT_URL, $url);//抓取指定网页
		curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
		curl_setopt($ch, CURLOPT_POST, 0);//post提交方式
		$responseBody = curl_exec($ch);//运行curl
		curl_close($ch);
		
		if ($responseBody === FALSE)
			return null;
		
		return $responseBody;
	}
	
	public static function getTargetURI($appServerLoginUrl = null, $appServerWelcomeUrl = null)
	{
	    $appServerLoginUrl = CommonUtil::isBlank($appServerLoginUrl) ? ___appServerLoginUrl : $appServerLoginUrl;
	    $appServerWelcomeUrl = CommonUtil::isBlank($appServerWelcomeUrl) ? ___appServerWelcomeUrl : $appServerWelcomeUrl;

	    $serviceURI = $_REQUEST[SERVICE_URI];
	    $serviceURI = CommonUtil::decodeServiceURI($serviceURI);

	    $serviceURI = CommonUtil::isBlank($serviceURI) ? $appServerWelcomeUrl : $serviceURI;

	    $urlToRedirectTo = $serviceURI;

	    return CommonUtil::startsWith($urlToRedirectTo, $appServerLoginUrl) ? $appServerWelcomeUrl : $urlToRedirectTo;
	}
}
?>