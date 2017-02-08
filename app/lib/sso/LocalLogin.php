<?php
require_once (dirname(__FILE__).'/SSOFilter.php');
?>

<?php
		$appServerLoginUrl = ___appServerLoginUrl;
        $appServerWelcomeUrl = ___appServerWelcomeUrl;
        
        $appServerLoginUserKey = SESSSION_USERNAME_KEY;
        $appServerLoginPassKey = SESSSION_PASSWORD_KEY;

        $errorCode = $_REQUEST["errorCode"];

        if (CommonUtil::isBlank($errorCode))
        {
        	// 登录成功
            $currentLoginUser = $_SESSION[CURRENT_LOGIN_USER_KEY];
            
            if (CommonUtil::isBlank($currentLoginUser))
            {
            	header('Location: ' . appServerLoginUrl . "?errorCode=000");
                exit();
            }
            else
            {
                $username = CurrentLoginUser::getLoginUserAccount();

                /*
                // TODO 写入本地Session
                */
                $_SESSION["LocalSession_LoginUserName"] = $username;

                /*			
                // 跳转到最终访问页面
                */
                $urlToRedirectTo = CommonUtil::getTargetURI();

                header('Location: ' . $urlToRedirectTo);
                exit();
            }
        }
        else if ($errorCode=="000")
        {
            // 认证服务器未认证

            header('Location: ' . $appServerLoginUrl . "?errorCode=" . $errorCode);
            exit();
        }
        else if ($errorCode=="999")
        {
            // 认证服务器无效

            #region 本地认证示例
            // 可进行本地认证
            $user = $_SESSION[$appServerLoginUserKey];
            $pass = $_SESSION[$appServerLoginPassKey];

        	if ($user=="admin" && $pass=="admin")
            {
                /*
                // 写入API Session，以保证本地认证通过后，用户的访问不会再被API 认证拦截
                */
                $loginUserAccount = $user;

                CurrentLoginUser::setLoginUserAccount($loginUserAccount);
                
				CurrentLoginUser::setLoginUserName("本地管理员");

                $username = CurrentLoginUser::getLoginUserAccount();
                /*
                // TODO 写入本地Session
                */
                $_SESSION["LocalSession_LoginUserName"] = $username;

                /*			
                // 跳转到最终访问页面
                */
                $urlToRedirectTo = CommonUtil::getTargetURI();

                header('Location: ' . $urlToRedirectTo);
                exit();
            }
            else
            {
            	header('Location: ' . $appServerLoginUrl . "?errorCode=" . $errorCode);
                exit();
            }
            #endregion
        }
        else if ($errorCode=="001" || $errorCode=="002")
        {
            // 用户名不存在或密码错误

            #region 本地认证示例
            // 可进行本地认证
            $user = $_SESSION[$appServerLoginUserKey];
            $pass = $_SESSION[$appServerLoginPassKey];

            if ($user=="admin" && $pass=="admin")
            {
                /*
                // 写入API Session，以保证本地认证通过后，用户的访问不会再被API 认证拦截
                */
                $loginUserAccount = $user;

                CurrentLoginUser::setLoginUserAccount($loginUserAccount);

				CurrentLoginUser::setLoginUserName("本地管理员");

                $username = CurrentLoginUser::getLoginUserAccount();
                /*
                // TODO 写入本地Session
                */
                $_SESSION["LocalSession_LoginUserName"] = $username;

                /*			
                // 跳转到最终访问页面
                */
                $urlToRedirectTo = CommonUtil::getTargetURI();

                header('Location: ' . $urlToRedirectTo);
                exit();
            }
            else
            {
            	header('Location: ' . $appServerLoginUrl . "?errorCode=" . $errorCode);
                exit();
            }
            #endregion
        }
        else if ($errorCode=="003")
        {
            // 校验码错误

            header('Location: ' . $appServerLoginUrl . "?errorCode=" . $errorCode);
            exit();
        }
        else
        {
        	header('Location: ' . $appServerLoginUrl . "?errorCode=000");
            exit();
        }
?>