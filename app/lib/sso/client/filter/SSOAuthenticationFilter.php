<?php

include_once(dirname(__FILE__).'/Filter.php');

class SSOAuthenticationFilter extends AbstractFilter {
	public function __construct() { }
    /**
     * The URL to the CAS Server login.
     */
	private $casServerUrlPrefix = ___casServerUrlPrefix;
    
    /**
     * Whether to send the renew request or not.
     */
    private $renew = false;

    /**
     * Whether to send the gateway request or not.
     */
    private $gateway = false;
    
    private $appServerServiceUrlSuffix = ___appServerServiceUrlSuffix;
    
    private $appServerLoginUserKey = ___appServerLoginUserKey;
    private $appServerLoginPassKey = ___appServerLoginPassKey;
    private $appServerLoginCodeKey = ___appServerLoginCodeKey;
    
    private $appServerLocalCodeClass = ___appServerLocalCodeClass;
    
	public final function init() {
		$this->casServerUrlPrefix = ___casServerUrlPrefix;
		$this->casServerUrlPrefix = CommonUtil::endsWith($this->casServerUrlPrefix, ("/")) ? $this->casServerUrlPrefix : $this->casServerUrlPrefix + "/";
		
		$this->appServerValidateUrlSuffix = ___appServerValidateUrlSuffix;
		$this->appServerLoginUrlSuffix = ___appServerLoginUrlSuffix;
		$this->appServerLogoutUrlSuffix = ___appServerLogoutUrlSuffix;
		$this->appServerValidateUrlSuffix = CommonUtil::isBlank($this->appServerValidateUrlSuffix) ? "/sso/validate.php" : $this->appServerValidateUrlSuffix;
		$this->appServerLoginUrlSuffix = CommonUtil::isBlank($this->appServerLoginUrlSuffix) ? "/sso/login.php" : $this->appServerLoginUrlSuffix;
		$this->appServerLogoutUrlSuffix = CommonUtil::isBlank($this->appServerLogoutUrlSuffix) ? "/sso/logout.php" : $this->appServerLogoutUrlSuffix;
		
		$this->appServerServiceUrlSuffix = ___appServerServiceUrlSuffix;
		$this->appServerServiceUrlSuffix = CommonUtil::isBlank($this->appServerServiceUrlSuffix) ? "/sso/service.php" : $this->appServerServiceUrlSuffix;
				
		$this->appServerLoginUserKey = ___appServerLoginUserKey;
		$this->appServerLoginPassKey = ___appServerLoginPassKey;
		$this->appServerLoginCodeKey = ___appServerLoginCodeKey;
		$this->appServerLoginUserKey = CommonUtil::isBlank($this->appServerLoginUserKey) ? "username" : $this->appServerLoginUserKey;
		$this->appServerLoginPassKey = CommonUtil::isBlank($this->appServerLoginPassKey) ? "password" : $this->appServerLoginPassKey;
		$this->appServerLoginCodeKey = CommonUtil::isBlank($this->appServerLoginCodeKey) ? "code" : $this->appServerLoginCodeKey;
		
		$this->appServerLocalCodeClass = ___appServerLocalCodeClass;
	}
	
    public final function doFilter() {

    	$requestURI = $this->getRequestURI();
    	$queryString = $this->getQueryString();
    	
    	$filterURI = $requestURI;

    	if ( CommonUtil::endsWith($filterURI, $this->appServerLoginUrlSuffix) ) {
	    	$errorCode = $_GET["errorCode"];
	        if (!empty($errorCode)) {
	        	return;
			}

	        $user = $_REQUEST["username"];
	        $pass = $_REQUEST["password"];
	        if (!empty($user) && !empty($pass)) {
		        phpCAS :: client(CAS_VERSION_2_0, $this->casServerUrlPrefix);
		        phpCAS :: setNoCasServerValidation();
	        	
	        	$_SESSION[$this->appServerLoginUserKey] = $user;
		        $_SESSION[$this->appServerLoginPassKey] = $pass;

	        	$_SESSION[CAS_USER_KEY] = $user;
	        	$_SESSION[CAS_PASS_KEY] = $pass;
	        	
	        	phpCAS :: renewAuthentication();
	        } else {
		        $user = $_REQUEST[this.appServerLoginUserKey];
		        $pass = $_REQUEST[this.appServerLoginPassKey];
		        
		        $code = $_REQUEST[this.appServerLoginCodeKey];
		
		        if (!empty($user) && !empty($pass)) {
			        phpCAS :: client(CAS_VERSION_2_0, $this->casServerUrlPrefix);
			        phpCAS :: setNoCasServerValidation();
		        	
		        	$_SESSION[$this->appServerLoginCodeKey] = $code;
		        	
		        	if (!empty($this->appServerLocalCodeClass) && $code!=null) {
		        		$className = $this->appServerLocalCodeClass;
						$local = new $className();
		        		$ret = $local->doLocal();
		        		if (!$ret) {
		        			header('Location: ' . $this->appServerServiceUrlSuffix . "?errorCode=003");
		        			exit();
		        		}
		        	}
		        	
			        $_SESSION[$this->appServerLoginUserKey] = $user;
		        	$_SESSION[$this->appServerLoginPassKey] = $pass;
		        	
		        	$_SESSION[$this->appServerLoginCodeKey] = null;
		        	
		        	$_SESSION[CAS_USER_KEY] = $user;
		        	$_SESSION[CAS_PASS_KEY] = $pass;


		        	phpCAS :: renewAuthentication();
		        } else {
			        phpCAS :: client(CAS_VERSION_2_0, $this->casServerUrlPrefix);
			        phpCAS :: setNoCasServerValidation();
			        
		        	// 如果已登录，则直接访问
		        	$currentUser = $_SESSION[CURRENT_LOGIN_USER_KEY];

		        	if ($currentUser==null) {
		        		if (phpCAS::isAuthenticated()) {
		        			//$user = phpCAS :: getUser();
		        			//$attributes = phpCAS :: getAttributes();
					    	//$_SESSION[CAS_AUTHENTICATION_USER_KEY] = $user;
					    	//$_SESSION[CAS_AUTHENTICATION_ATTRIBUTE_KEY] = $attributes;
					    	
					    	//print_r($_SESSION);exit(); 
		        		} else {
		        			phpCAS :: forceAuthentication();
		        		}
		        	}
		        	
		        	//print_r($_SESSION);
		        	//exit();
		        }
	        }
	    } else {
	    	//session_start();
	    	//$currentUser = $_SESSION[CURRENT_LOGIN_USER_KEY];
	    	//print_r($_SESSION);
	    }
	    
    }
}
?>