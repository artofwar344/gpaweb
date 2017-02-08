<?php

include_once(dirname(__FILE__).'/Filter.php');

class SSOClientFilter extends AbstractFilter {
	public function __construct() { }

	private $casServerUrlPrefix;
	/**
	 * The URL to the CAS Server login.
	 */
	private $casServerLoginUrl;
	/**
	 * The URL to the CAS Server logout.
	 */
	private $casServerLogoutUrl;

	private $casServerTimeout;

	private $appServerLoginUrl;
	private $appServerWelcomeUrl;
	private $appServerLocalLoginUrl;
	
	/**
	 * The name of the server.  Should be in the following format: {protocol}:{hostName}:{port}.
	 * Standard ports can be excluded. 
	 */
	private $appServerName;

	private $appServerLoginUserKey;
	private $appServerLoginPassKey;
	private $appServerLoginCodeKey;
	
	private $appServerLocalValidateClass;
	private $appServerLocalLoginClass;
	private $appServerLocalLogoutClass;
	private $appServerLocalCodeClass;
	
	private $appServerSecurityResourceRegex;
	
	private $appServerValidateUrlSuffix;
	private $appServerLoginUrlSuffix;
	private $appServerLogoutUrlSuffix;
	private $appServerServiceUrlSuffix;

	public final function init() {
		$this->casServerUrlPrefix = ___casServerUrlPrefix;
		$this->casServerUrlPrefix = CommonUtil::endsWith($this->casServerUrlPrefix, ("/")) ? $this->casServerUrlPrefix : $this->casServerUrlPrefix . "/";
		$this->casServerLoginUrl = ___casServerLoginUrl;
		$this->casServerLogoutUrl = ___casServerLogoutUrl;
		$this->casServerStatusUrl = ___casServerStatusUrl;
		$this->casServerLoginUrl = CommonUtil::isBlank($this->casServerLoginUrl) ? $this->casServerUrlPrefix + "login" : $this->casServerLoginUrl;
		$this->casServerLogoutUrl = CommonUtil::isBlank($this->casServerLogoutUrl) ? $this->casServerUrlPrefix + "logout" : $this->casServerLogoutUrl;
		$this->casServerStatusUrl = CommonUtil::isBlank($this->casServerStatusUrl) ? $this->casServerUrlPrefix + "status.htm" : $this->casServerStatusUrl;

		$this->casServerTimeout = ___casServerTimeout;

		$this->appServerName = ___appServerName;
		$this->appServerName = CommonUtil::endsWith($this->appServerName, ("/")) ? $this->appServerName.Substring(0, $this->appServerName.Length - 1) : $this->appServerName;

		$this->appServerLoginUserKey = ___appServerLoginUserKey;
		$this->appServerLoginPassKey = ___appServerLoginPassKey;
		$this->appServerLoginCodeKey = ___appServerLoginCodeKey;
		$this->appServerLoginUserKey = CommonUtil::isBlank($this->appServerLoginUserKey) ? "username" : $this->appServerLoginUserKey;
		$this->appServerLoginPassKey = CommonUtil::isBlank($this->appServerLoginPassKey) ? "password" : $this->appServerLoginPassKey;
		$this->appServerLoginCodeKey = CommonUtil::isBlank($this->appServerLoginCodeKey) ? "code" : $this->appServerLoginCodeKey;

		$this->appServerLoginUrl = ___appServerLoginUrl;
		$this->appServerWelcomeUrl = ___appServerWelcomeUrl;

		$this->appServerSecurityResourceRegex = ___appServerSecurityResourceRegex;
		
		$this->appServerLocalLoginUrl = ___appServerLocalLoginUrl;

		$this->appServerLocalLoginClass = ___appServerLocalLoginClass;
		$this->appServerLocalLogoutClass = ___appServerLocalLogoutClass;
		$this->appServerLocalCodeClass = ___appServerLocalCodeClass;
		$this->appServerLocalValidateClass = ___appServerLocalValidateClass;

		$this->appServerLoginUrlSuffix = ___appServerLoginUrlSuffix;
		$this->appServerLogoutUrlSuffix = ___appServerLogoutUrlSuffix;
		$this->appServerValidateUrlSuffix = ___appServerValidateUrlSuffix;
		$this->appServerServiceUrlSuffix = ___appServerServiceUrlSuffix;
		$this->appServerLoginUrlSuffix = CommonUtil::isBlank($this->appServerLoginUrlSuffix) ? "/sso/login.php" : $this->appServerLoginUrlSuffix;
		$this->appServerLogoutUrlSuffix = CommonUtil::isBlank($this->appServerLogoutUrlSuffix) ? "/sso/logout.php" : $this->appServerLogoutUrlSuffix;
		$this->appServerValidateUrlSuffix = CommonUtil::isBlank($this->appServerValidateUrlSuffix) ? "/sso/validate.php" : $this->appServerValidateUrlSuffix;
		$this->appServerServiceUrlSuffix = CommonUtil::isBlank($this->appServerServiceUrlSuffix) ? "/sso/service.php" : $this->appServerServiceUrlSuffix;
	}
	
	public final function doFilter() {

		$requestURI = $this->getRequestURI();
		$queryString = $this->getQueryString();
		
		$protocol = ($this->isHttps()) ? 'https' : 'http';	//$_SERVER['SERVER_PROTOCOL'];
		$serverName = $_SERVER['SERVER_NAME'];
		$serverPort = $_SERVER['SERVER_PORT'];
		$contextPath = ___appServerContextPath; //httpRequest.ApplicationPath;
		
		$serverUri = $protocol . "://" . $serverName . ($serverPort=="80" ? "" : ":" . $serverPort);
		$serverUri = (CommonUtil::isBlank($this->appServerName)) ? $serverUri : $this->appServerName;
		
		$requestUrl = $serverUri . $requestURI;
		$requestMethod = $_SERVER['REQUEST_METHOD'];
		
		$urlToRedirectTo = "";
		
		// 如果请求地址以 /sso/validate.php 结束，则处理本地帐户校验
		if (CommonUtil::endsWith($requestURI, $this->appServerValidateUrlSuffix))
		{
			#region Validate
			if (strtoupper($requestMethod) == "POST")
			{
				$validateResult = "";
				if (CommonUtil::isBlank($this->appServerLocalValidateClass))
				{

				}
				else
				{	
					$className = $this->appServerLocalValidateClass;
					$local = new $className();
					$validateResult = $local->doLocal();
				}

				if (CommonUtil::isBlank($validateResult)) {
					$validateResult = "{}";
				}

			//	httpResponse.ContentType = "application/json;charset=UTF-8";
			//	httpResponse.Write(validateResult);
			//	httpResponse.End();

				ob_clean();
				header('ContentType: ' . "application/json;charset=UTF-8");
				echo $validateResult;
			}

			exit();
			#endregion
		}
		
		// 如果请求地址以 /sso/logout.php 结束，则处理注销
		if (CommonUtil::endsWith($requestURI, $this->appServerLogoutUrlSuffix))
		{
			#region Logout
			$currentUser = CurrentLoginUser::getLoginUserAccount();

			// 注销
			if (CommonUtil::isBlank($currentUser))
			{
				// 由CAS注销后返回时，处理
				// 页面须被转向到系统首页 或 系统注销时提供的返回地址

				$serviceURI = $_REQUEST[SERVICE_URI];
				$serviceURI = CommonUtil::decodeServiceURI($serviceURI);

				$serviceURI = CommonUtil::isBlank($serviceURI) ? $this->appServerLoginUrl : $serviceURI;

				$urlToRedirectTo = CommonUtil::isBlank($serviceURI) ?
					CommonUtil::isBlank($this->appServerLoginUrl) ?
						$serverUri . $contextPath :
							$this->appServerLoginUrl :
								$serviceURI;

				header('Location: ' . $urlToRedirectTo);
				exit();
			}
			else
			{
				$localUrl = "";

				if (CommonUtil::isBlank($this->appServerLocalLogoutClass)) { }
				else
				{
					// 清除本地系统保存的Session，由业务系统开发商实现接口 com.kingstar.sso.client.Local
					// 执行本地登录信息清除，若须自行跳转，则可返回注销完成后须跳转的地址，否则返回空即可
					$className = $this->appServerLocalLogoutClass;
					$local = new $className();
					
					$localUrl = $local->doLocal();
				}

				// 清楚API保存的Session
				$_SESSION[CURRENT_LOGIN_USER_KEY] = null;

				$_SESSION[$this->appServerLoginUserKey] = null;
				$_SESSION[$this->appServerLoginPassKey] = null;

				$_SESSION[SESSSION_USERNAME_KEY] = null;
				$_SESSION[SESSSION_PASSWORD_KEY] = null;
				
				
				$_SESSION['phpCAS'] = null;

				#region CAS Logout

				$serviceURI = $_REQUEST[SERVICE_URI];
				$serviceURI = CommonUtil::decodeServiceURI($serviceURI);

				$serviceURI = CommonUtil::isBlank($serviceURI) ? $this->appServerWelcomeUrl : $serviceURI;

				$serviceURI = CommonUtil::isBlank($localUrl) ? $serviceURI :
					(CommonUtil::startsWith($localUrl, "http://") || CommonUtil::startsWith($localUrl, "https://")) ? $localUrl : $serverUri . $localUrl;

				$serviceURI = CommonUtil::encodeServiceURI($serviceURI);
				$serviceUrl = $serverUri . $contextPath . $this->appServerLogoutUrlSuffix
					. "?" . SERVICE_URI . "=" . $serviceURI;

				$isCasAlive = CommonUtil::isCasAlive($this->casServerStatusUrl, $this->casServerTimeout);

				if ($isCasAlive)
				{
					$urlToRedirectTo = CommonUtil::isBlank($serviceUrl) ?
						$this->casServerLogoutUrl :
						$this->casServerLogoutUrl . "?service=" . urlencode($serviceUrl);
				}
				else
				{
					$urlToRedirectTo = $serviceUrl;
				}

				header('Location: ' . $urlToRedirectTo);
				exit();
				#endregion
			}

			#endregion
		}
		
		$currentLoginUser = "";

		@$user = $_REQUEST[$this->appServerLoginUserKey];
		@$pass = $_REQUEST[$this->appServerLoginPassKey];

		if (CommonUtil::isBlank($user) || CommonUtil::isBlank($pass))
		{
			if (CommonUtil::endsWith($requestURI, $this->appServerLoginUrlSuffix))
			{
				$currentLoginUser = "";
			}
			else
			{
				$currentLoginUser = CurrentLoginUser::getLoginUserAccount();
			}
		}
		else
		{
			$currentLoginUser = "";
			
			#region 登录验证码验证
			// 登录验证码验证
			if (CommonUtil::isBlank($this->appServerLocalCodeClass)) { }
			else
			{
				// 验证码验证，由业务系统开发商实现接口 com.kingstar.sso.client.Local

				// 执行本地登录信息清除，若须自行跳转，则可返回注销完成后须跳转的地址，否则返回空即可
				$className = $this->appServerLocalCodeClass;
				$local = new $className();
				$ret = $local->doLocal();
				
				if (!empty($ret) && $ret=="true")
				{ } else {
					header('Location: ' . $this->appServerServiceUrlSuffix . "?errorCode=003");
					exit();
				}
			}
			#endregion
		}
		
		
		if (CommonUtil::isBlank($currentLoginUser))
		{
			#region 判断是否已登录
			$isCASLogin = false;
			$loginUserAccount = null;

			// 如果不存在 API保存的Session，则获取请求中的信息
			if (CommonUtil::isBlank($user) || CommonUtil::isBlank($pass)) {
				$loginUserAccount = $_SESSION['phpCAS']['user'];
				if (CommonUtil::isBlank($loginUserAccount)) {
					$isCASLogin = false;
				} else {
					$isCASLogin = true;
				}
			} else {
				$_SESSION[$this->appServerLoginUserKey] = $user;
				$_SESSION[$this->appServerLoginPassKey] = $pass;

				$_SESSION[SESSSION_USERNAME_KEY] = $user;
				$_SESSION[SESSSION_PASSWORD_KEY] = $pass;
				
				$isCASLogin = false;
			}
			#endregion
			
			//echo $loginUserAccount;

			if (!$isCASLogin) {
				$errorCode = $_REQUEST["errorCode"];
				if (CommonUtil::isBlank($errorCode))
				{
					// 如果请求中不存在用户登录信息，则须跳转到CAS进行认证 或 获取票据
					// 系统登录时须跳转回去的地址，保存到Session中
					//Regex r = new Regex($this->appServerSecurityResourceRegex);
					$r = '/'.(preg_replace('/\//', '\/', $this->appServerSecurityResourceRegex)).'/';
					
					if (preg_match($r, $requestURI))
					{
						#region CAS Login
						$serviceURI = $_REQUEST[SERVICE_URI];
						if (CommonUtil::isBlank($serviceURI))
						{
							// 若重新登录时，读取到session中的值
							if ($_SESSION[SERVICE_URI] == null) { }
							else
							{
								$serviceURI = $_SESSION[SERVICE_URI];
								$serviceURI = CommonUtil::decodeServiceURI(serviceURI);
								$_SESSION[SERVICE_URI] = "";
							}
						}

						$currentURI
							= ($requestUrl==$this->appServerLoginUrl ? $this->appServerWelcomeUrl : $requestUrl)
							. (CommonUtil::isBlank($queryString) ? "" : "?" . $queryString);

						$serviceURI = CommonUtil::isBlank($serviceURI) ? $currentURI : $serviceURI;

						// 验证cas服务器的有效性
						$isCasAlive = CommonUtil::isCasAlive($this->casServerStatusUrl, $this->casServerTimeout);

						if ($isCasAlive)
						{
							// 如果当前请求的地址需要认证后才能访问的，则须跳转到CAS进行认证 或 获取票据
							$serviceURI = CommonUtil::encodeServiceURI($serviceURI);
							$urlToRedirectTo = $serverUri . $contextPath . $this->appServerLoginUrlSuffix .
								"?" . SERVICE_URI . "=" . $serviceURI;

							if (CommonUtil::isBlank($user) || CommonUtil::isBlank($pass)) { }
							else
							{
								$urlToRedirectTo .= "&username=" . $user . "&password=" . $pass . "&renew=true";
							}

							header('Location: ' . $urlToRedirectTo);
							exit();
						} else {
							if (CommonUtil::isBlank($this->appServerLocalLoginUrl))
							{
								if (CommonUtil::isBlank($this->appServerLocalLoginClass))
								{
								// 未配置本地认证处理页面或处理类，则回到登陆页
								if (CommonUtil::isBlank($this->appServerLoginUrl))
								{
									#region 配置异常
									$filterError = "请配置[appSettings]参数[appServerLoginUrl]";
								   // httpResponse.ContentType = "text/html;charset=UTF-8";
								   // httpResponse.Write(filterError);
								   // httpResponse.End();
								   // return;
								   	exit();
									#endregion
								}
								else
								{
									#region 跳转到登录页面 登录
									$serviceURI = CommonUtil::encodeServiceURI($serviceURI);
									$urlToRedirectTo = $this->appServerLoginUrl
										. (CommonUtil::indexOf($this->appServerLoginUrl, "?")!=-1 ? "&" : "?")
										. SERVICE_URI . "=" . $serviceURI
										. "&" . "errorCode=999";

									// 同时保存到session中， 以便重新登录时使用
									$_SESSION[SERVICE_URI] = $serviceURI;

									header('Location: ' . $urlToRedirectTo);
									exit();
									#endregion
								}
								}
								else
								{
									#region 执行本地认证处理类
									// 执行本地认证处理类
									$localUrl = "";
	
									// 保存本地系统的Session，由业务系统开发商实现接口 com.kingstar.sso.client.Local
									
									// 执行本地登录信息保存，若须自行跳转，则可返回登录完成后须跳转的地址，否则返回空即可
									$className = $this->appServerLocalLoginClass;
									$local = new $className();
	
									$localUrl = $local->doLocal();

									$urlToRedirectTo = CommonUtil::isBlank($localUrl) ? $serviceURI :
										(CommonUtil::startsWith($localUrl, "http://") || CommonUtil::startsWith($localUrl, "https://")) ? $localUrl : $serverUri . $localUrl;
	
									// 同时保存到session中， 以便重新登录时使用
									//$_SESSION[SERVICE_URI] = serviceURI;
	
									header('Location: ' . $urlToRedirectTo);
									exit();
									#endregion
								}
							}
							else
							{
								#region 跳转到本地认证处理页面 处理
								$serviceURI = CommonUtil::encodeServiceURI($serviceURI);
								$urlToRedirectTo = $this->appServerLocalLoginUrl
									. (CommonUtil::indexOf($this->appServerLocalLoginUrl, "?")!=-1 ? "&" : "?")
									. SERVICE_URI . "=" . $serviceURI
									. "&" . "errorCode=999";

								// 同时保存到session中， 以便重新登录时使用
								$_SESSION[SERVICE_URI] = $serviceURI;

								header('Location: ' . $urlToRedirectTo);
								exit();
								#endregion
							}
						}
						#endregion
					} else { } // 否则，允许直接访问 
				} else if ($errorCode=="000") {
					if (CommonUtil::startsWith($this->appServerLoginUrl, $requestUrl)
						|| CommonUtil::startsWith($this->appServerLocalLoginUrl, $requestUrl)) {
							
					} else if (CommonUtil::endsWith($requestURI, $this->appServerLoginUrlSuffix) 
						|| CommonUtil::endsWith($requestURI, $this->appServerServiceUrlSuffix))
					{
						if (CommonUtil::isBlank($this->appServerLoginUrl))
						{
							#region 配置异常
							$filterError = "请配置[appSettings]参数[appServerLoginUrl]";
						   // httpResponse.ContentType = "text/html;charset=UTF-8";
						   // httpResponse.Write(filterError);
						   // httpResponse.End();
						   // return;
						   	exit();
							#endregion
						}
						else
						{
							#region 跳转到本地登录页面
							$serviceURI = $_REQUEST[SERVICE_URI];
							$serviceURI = CommonUtil::decodeServiceURI($serviceURI);

							$serviceURI = CommonUtil::isBlank($serviceURI) ? $this->appServerWelcomeUrl : $serviceURI;

							$serviceURI = CommonUtil::encodeServiceURI($serviceURI);
							$urlToRedirectTo = $this->appServerLoginUrl
								. (CommonUtil::indexOf($this->appServerLoginUrl, "?") != -1 ? "&" : "?")
								. SERVICE_URI . "=" . $serviceURI
								. "&errorCode=" . $errorCode;

							// 同时保存到session中， 以便重新登录时使用
							$_SESSION[SERVICE_URI] = $serviceURI;

							header('Location: ' . $urlToRedirectTo);
							exit();
							#endregion
						}
					}
					else { }
				} else {
					if (CommonUtil::startsWith($this->appServerLoginUrl, $requestUrl)
						|| CommonUtil::startsWith($this->appServerLocalLoginUrl, $requestUrl)) {

					} else if (CommonUtil::endsWith($requestURI, $this->appServerLoginUrlSuffix) 
						|| CommonUtil::endsWith($requestURI, $this->appServerServiceUrlSuffix)) {
						if (CommonUtil::isBlank($this->appServerLocalLoginUrl))
						{
							if (CommonUtil::isBlank($this->appServerLocalLoginClass))
							{
								// 未配置本地认证处理页面或处理类，则回到登陆页
								if (CommonUtil::isBlank($this->appServerLoginUrl))
								{
									#region 配置异常
									$filterError = "请配置[appSettings]参数[appServerLoginUrl]";
								   // httpResponse.ContentType = "text/html;charset=UTF-8";
								   // httpResponse.Write(filterError);
								   // httpResponse.End();
								   // return;
								   	exit();
									#endregion
								}
								else
								{
									#region 跳转到登录页面 登录
									$serviceURI = $_REQUEST[SERVICE_URI];
									$serviceURI = CommonUtil::decodeServiceURI($serviceURI);

									$serviceURI = CommonUtil::isBlank($serviceURI) ? $this->appServerWelcomeUrl : $serviceURI;

									$serviceURI = CommonUtil::encodeServiceURI($serviceURI);
									$urlToRedirectTo = $this->appServerLoginUrl
										. (CommonUtil::indexOf($this->appServerLoginUrl, "?") != -1 ? "&" : "?")
										. SERVICE_URI . "=" . $serviceURI
										. "&" . "errorCode=" . $errorCode;

									// 同时保存到session中， 以便重新登录时使用
									$_SESSION[SERVICE_URI] = $serviceURI;

									header('Location: ' . $urlToRedirectTo);
									exit();
									#endregion
								}
							}
							else
							{
								#region 执行本地认证处理类
								// 执行本地认证处理类
								$localUrl = "";

								// 保存本地系统的Session，由业务系统开发商实现接口 com.kingstar.sso.client.Local

								// 执行本地登录信息保存，若须自行跳转，则可返回登录完成后须跳转的地址，否则返回空即可
								$className = $this->appServerLocalLoginClass;
								$local = new $className();

								$localUrl = $local->doLocal();

								$serviceURI = $_REQUEST[SERVICE_URI];
								$serviceURI = CommonUtil::decodeServiceURI($serviceURI);

								$serviceURI = CommonUtil::isBlank($serviceURI) ? $this->appServerWelcomeUrl : $serviceURI;

								$urlToRedirectTo = CommonUtil::isBlank($localUrl) ? $serviceURI :
									(CommonUtil::startsWith($localUrl, "http://") || CommonUtil::startsWith($localUrl, "https://")) ? $localUrl : $serverUri . $localUrl;
									
								
								// 同时保存到session中， 以便重新登录时使用
								//$_SESSION[SERVICE_URI] = $serviceURI;

								header('Location: ' . $urlToRedirectTo);
								exit();
								#endregion
							}
						}
						else
						{
							#region 跳转到本地认证处理页面 处理
							$serviceURI = $_REQUEST[SERVICE_URI];
							$serviceURI = CommonUtil::decodeServiceURI($serviceURI);

							$serviceURI = CommonUtil::isBlank($serviceURI) ? $this->appServerWelcomeUrl : $serviceURI;

							$serviceURI = CommonUtil::encodeServiceURI($serviceURI);
							$urlToRedirectTo = $this->appServerLocalLoginUrl
								. (CommonUtil::indexOf($this->appServerLocalLoginUrl, "?") != -1 ? "&" : "?")
								. SERVICE_URI . "=" . $serviceURI
								. "&" . "errorCode=" . $errorCode;

							// 同时保存到session中， 以便重新登录时使用
							$_SESSION[SERVICE_URI] = $serviceURI;

							header('Location: ' . $urlToRedirectTo);
							exit();
							#endregion
						}
					} else {
						if (CommonUtil::isBlank($this->appServerLocalLoginUrl))
						{
							if (CommonUtil::isBlank($this->appServerLocalLoginClass))
							{
								// 未配置本地认证处理页面或处理类，则回到登陆页
								if (CommonUtil::isBlank($this->appServerLoginUrl))
								{
									#region 配置异常
									$filterError = "请配置[appSettings]参数[appServerLoginUrl]";
								   // httpResponse.ContentType = "text/html;charset=UTF-8";
								   // httpResponse.Write(filterError);
								   // httpResponse.End();
								   // return;
								   	exit();
									#endregion
								}
								else
								{
									#region 跳转到登录页面 登录
									$serviceURI = $_REQUEST[SERVICE_URI];
									$serviceURI = CommonUtil::decodeServiceURI($serviceURI);

									$serviceURI = CommonUtil::isBlank($serviceURI) ? $this->appServerWelcomeUrl : $serviceURI;

									$serviceURI = CommonUtil::encodeServiceURI($serviceURI);
									$urlToRedirectTo = $this->appServerLoginUrl
										. (CommonUtil::indexOf($this->appServerLoginUrl, "?") != -1 ? "&" : "?")
										. SERVICE_URI . "=" . $serviceURI
										. "&" . "errorCode=" . $errorCode;

									// 同时保存到session中， 以便重新登录时使用
									$_SESSION[SERVICE_URI] = $serviceURI;

									header('Location: ' . $urlToRedirectTo);
									exit();
									#endregion
								}
							}
							else
							{
								#region 执行本地认证处理类
								// 执行本地认证处理类
								$localUrl = "";

								// 保存本地系统的Session，由业务系统开发商实现接口 com.kingstar.sso.client.Local

								// 执行本地登录信息保存，若须自行跳转，则可返回登录完成后须跳转的地址，否则返回空即可
								$className = $this->appServerLocalLoginClass;
								$local = new $className();

								$localUrl = $local->doLocal();

								$serviceURI = $_REQUEST[SERVICE_URI];
								$serviceURI = CommonUtil::decodeServiceURI($serviceURI);

								$serviceURI = CommonUtil::isBlank($serviceURI) ? $this->appServerWelcomeUrl : $serviceURI;

								$urlToRedirectTo = CommonUtil::isBlank($localUrl) ? $serviceURI :
									(CommonUtil::startsWith($localUrl, "http://") || CommonUtil::startsWith($localUrl, "https://")) ? $localUrl : $serverUri . $localUrl;
									
								
								// 同时保存到session中， 以便重新登录时使用
								//$_SESSION[SERVICE_URI] = $serviceURI;

								header('Location: ' . $urlToRedirectTo);
								exit();
								#endregion
							}
						}
						else
						{
							#region 跳转到本地认证处理页面 处理
							$serviceURI = $_REQUEST[SERVICE_URI];
							$serviceURI = CommonUtil::decodeServiceURI($serviceURI);

							$serviceURI = CommonUtil::isBlank($serviceURI) ? $this->appServerWelcomeUrl : $serviceURI;

							$serviceURI = CommonUtil::encodeServiceURI($serviceURI);
							$urlToRedirectTo = $this->appServerLocalLoginUrl
								. (CommonUtil::indexOf($this->appServerLocalLoginUrl, "?") != -1 ? "&" : "?")
								. SERVICE_URI . "=" . $serviceURI
								. "&" . "errorCode=" . $errorCode;

							// 同时保存到session中， 以便重新登录时使用
							$_SESSION[SERVICE_URI] = $serviceURI;

							header('Location: ' . $urlToRedirectTo);
							exit();
							#endregion
						}
					}
				}
			} else {
				#region SSO Login
				// 如果请求中存在用户登录信息，则获取信息后，保存到API的Session中
				//$currentLoginUser = new CurrentLoginUser($loginUserAccount);

				//CasPrincipal principal = (CasPrincipal)Thread.CurrentPrincipal;

				//CasPrincipal principal = (CasPrincipal)httpContext.User;
				//IDictionary<string, IList<string>> principalAttributes = principal.Assertion.Attributes;
				//$principalAttributes = $_SESSION['phpCAS']['attributes'];//phpCAS::getAttributes();

				//$currentLoginUser->setLoginUserSSOAccount($this->getDecodeString($principalAttributes, LOGIN_USER_SSO_ACCOUNT));

				//$currentLoginUser->setLoginUserLocalAccount($this->getDecodeString($principalAttributes, LOGIN_USER_LOCAL_ACCOUNT));
				//$currentLoginUser->setLoginUserLocalPass($this->getDecodeString($principalAttributes, LOGIN_USER_LOCAL_PASS));

				//$currentLoginUser->setLoginUserId($this->getDecodeString($principalAttributes, LOGIN_USER_ID));
				//$currentLoginUser->setLoginUserName($this->getDecodeString($principalAttributes, LOGIN_USER_NAME));
				//$currentLoginUser->setLoginUserNick($this->getDecodeString($principalAttributes, LOGIN_USER_NICK));
				//$currentLoginUser->setLoginUserEmail($this->getDecodeString($principalAttributes, LOGIN_USER_EMAIL));
				//$currentLoginUser->setLoginUserTel($this->getDecodeString($principalAttributes, LOGIN_USER_TEL));
				//$currentLoginUser->setLoginUserMobile($this->getDecodeString($principalAttributes, LOGIN_USER_MOBILE));
				//$currentLoginUser->setLoginUserIDCard($this->getDecodeString($principalAttributes, LOGIN_USER_IDCARD));
				//$currentLoginUser->setLoginUserOrgId($this->getDecodeString($principalAttributes, LOGIN_USER_ORG_ID));
				//$currentLoginUser->setLoginUserOrgCode($this->getDecodeString($principalAttributes, LOGIN_USER_ORG_CODE));
				//$currentLoginUser->setLoginUserOrgName($this->getDecodeString($principalAttributes, LOGIN_USER_ORG_NAME));

				//$currentLoginUser->setLoginUserStaffNo($this->getDecodeString($principalAttributes, LOGIN_USER_STAFF_NO));
				//$currentLoginUser->setLoginUserStudentNo($this->getDecodeString($principalAttributes, LOGIN_USER_STUDENT_NO));

				//$_SESSION[CURRENT_LOGIN_USER_KEY] = $loginUserAccount;
				
				CurrentLoginUser::setLoginUserAccount($loginUserAccount);
				
				$principalAttributes = $_SESSION['phpCAS']['attributes'];
				CurrentLoginUser::setLoginUserSSOAccount($this->getDecodeString($principalAttributes, LOGIN_USER_SSO_ACCOUNT));
				
				CurrentLoginUser::setLoginUserLocalAccount($this->getDecodeString($principalAttributes, LOGIN_USER_LOCAL_ACCOUNT));
				CurrentLoginUser::setLoginUserLocalPass($this->getDecodeString($principalAttributes, LOGIN_USER_LOCAL_PASS));
				
				CurrentLoginUser::setLoginUserId($this->getDecodeString($principalAttributes, LOGIN_USER_ID));
				CurrentLoginUser::setLoginUserName($this->getDecodeString($principalAttributes, LOGIN_USER_NAME));
				CurrentLoginUser::setLoginUserNick($this->getDecodeString($principalAttributes, LOGIN_USER_NICK));
				CurrentLoginUser::setLoginUserEmail($this->getDecodeString($principalAttributes, LOGIN_USER_EMAIL));
				CurrentLoginUser::setLoginUserTel($this->getDecodeString($principalAttributes, LOGIN_USER_TEL));
				CurrentLoginUser::setLoginUserMobile($this->getDecodeString($principalAttributes, LOGIN_USER_MOBILE));
				CurrentLoginUser::setLoginUserIDCard($this->getDecodeString($principalAttributes, LOGIN_USER_IDCARD));
				CurrentLoginUser::setLoginUserOrgId($this->getDecodeString($principalAttributes, LOGIN_USER_ORG_ID));
				CurrentLoginUser::setLoginUserOrgCode($this->getDecodeString($principalAttributes, LOGIN_USER_ORG_CODE));
				CurrentLoginUser::setLoginUserOrgName($this->getDecodeString($principalAttributes, LOGIN_USER_ORG_NAME));
				
				CurrentLoginUser::setLoginUserStaffNo($this->getDecodeString($principalAttributes, LOGIN_USER_STAFF_NO));
				CurrentLoginUser::setLoginUserStudentNo($this->getDecodeString($principalAttributes, LOGIN_USER_STUDENT_NO));
				
				#endregion
				//print_r($_SESSION);
				#region Local Login
				$localUrl = "";

				if (CommonUtil::isBlank($this->appServerLocalLoginUrl))
				{
					if (CommonUtil::isBlank($this->appServerLocalLoginClass)) { }
					else
					{
						$className = $this->appServerLocalLoginClass;
						$local = new $className();

						$localUrl = $local->doLocal();
					}

					$serviceURI = $_REQUEST[SERVICE_URI];
					$serviceURI = CommonUtil::decodeServiceURI($serviceURI);

					$serviceURI = CommonUtil::isBlank($serviceURI) ? $this->appServerWelcomeUrl : $serviceURI;

					$urlToRedirectTo = CommonUtil::isBlank($localUrl) ? $serviceURI :
						(CommonUtil::startsWith($localUrl, "http://") || CommonUtil::startsWith($localUrl, "https://")) ? $localUrl : $serverUri + $localUrl;

					// 同时保存到session中， 以便重新登录时使用
					//$_SESSION[SERVICE_URI] = $serviceURI;

					header('Location: ' . $urlToRedirectTo);
					exit();
				}
				else
				{
					$serviceURI = $_REQUEST[SERVICE_URI];
					$serviceURI = CommonUtil::decodeServiceURI($serviceURI);

					$serviceURI = CommonUtil::isBlank($serviceURI) ? $this->appServerWelcomeUrl : $serviceURI;

					$serviceURI = CommonUtil::encodeServiceURI($serviceURI);
					$urlToRedirectTo = $this->appServerLocalLoginUrl
						. (CommonUtil::indexOf($this->appServerLocalLoginUrl, "?") != -1 ? "&" : "?")
						. SERVICE_URI . "=" . $serviceURI;

					// 同时保存到session中， 以便重新登录时使用
					$_SESSION[SERVICE_URI] = $serviceURI;

					header('Location: ' . $urlToRedirectTo);
					exit();
				}
				#endregion
			}
		}
	}

	private function getDecodeString($principalAttributes, $key)
	{
		$v = $principalAttributes[$key];
		return CommonUtil::isBlank($v) ? "" : trim($v);
	}
}
?>