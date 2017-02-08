<?php
namespace Ca\Service;

/**
 * 提供给客户端登录验证用的CAS接口，由php访问CAS登录页面，模拟提交
 * Class CASClient
 */
class CasService
{
	/**
	 * 使用 V8JS 扩展
	 * @param $str
	 * @return mixed
	 */
	public static function encryptPassword($str)
	{
		$js = new \V8Js();
		$scripts = <<<EOT
			keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
			Number.prototype.toHexStr = function(){
			    var s="", v;
			    for (var i=7; i>=0; i--) { v = (this>>>(i*4)) & 0xf; s += v.toString(16); }
			    return s;
			}
			function ROTL(x, n){
			    return (x<<n) | (x>>>(32-n));
			}

			function fHexStr(s, x, y, z){
			    switch(s) {
			    case 0: return (x & y) ^ (~x & z);
			    case 1: return x ^ y ^ z;
			    case 2: return (x & y) ^ (x & z) ^ (y & z);
			    case 3: return x ^ y ^ z;
			    }
			}
			function encode64(input) {
			  input = escape(input);
			  var output = "";
			  var chr1, chr2, chr3 = "";
			  var enc1, enc2, enc3, enc4 = "";
			  var i = 0;

			  do {
				 chr1 = input.charCodeAt(i++);
				 chr2 = input.charCodeAt(i++);
				 chr3 = input.charCodeAt(i++);

				 enc1 = chr1 >> 2;
				 enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
				 enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
				 enc4 = chr3 & 63;

				 if (isNaN(chr2)) {
					enc3 = enc4 = 64;
				 } else if (isNaN(chr3)) {
					enc4 = 64;
				 }

				 output = output +
					keyStr.charAt(enc1) +
					keyStr.charAt(enc2) +
					keyStr.charAt(enc3) +
					keyStr.charAt(enc4);
				 chr1 = chr2 = chr3 = "";
				 enc1 = enc2 = enc3 = enc4 = "";
			  } while (i < input.length);

			  return output;
			}

			function getHashCode(msg){
			    // constants [4.2.1]
			    var K = new Array(0x5a827999, 0x6ed9eba1, 0x8f1bbcdc, 0xca62c1d6);

			    // PREPROCESSING
			    msg += String.fromCharCode(0x80);  // add trailing '1' bit to string [5.1.1]

			    // convert string msg into 512-bit/16-integer blocks arrays of ints [5.2.1]
			    var l = Math.ceil(msg.length/4) + 2;  // long enough to contain msg plus 2-word length
			    var N = Math.ceil(l/16);              // in N 16-int blocks
			    var M = new Array(N);

			    for (var i=0; i<N; i++) {
			        M[i] = new Array(16);
			        for (var j=0; j<16; j++) {  // encode 4 chars per integer, big-endian encoding
			            M[i][j] = (msg.charCodeAt(i*64+j*4)<<24) | (msg.charCodeAt(i*64+j*4+1)<<16) |
			                      (msg.charCodeAt(i*64+j*4+2)<<8) | (msg.charCodeAt(i*64+j*4+3));
			        } // note running off the end of msg is ok 'cos bitwise ops on NaN return 0
			    }
			    // add length (in bits) into final pair of 32-bit integers (big-endian) [5.1.1]
			    M[N-1][14] = ((msg.length-1) >>> 30) * 8;
			    M[N-1][15] = ((msg.length-1)*8) & 0xffffffff;
			    // set initial hash value [5.3.1]
			    var H0 = 0x67452301;
			    var H1 = 0xefcdab89;
			    var H2 = 0x98badcfe;
			    var H3 = 0x10325476;
			    var H4 = 0xc3d2e1f0;

			    // HASH COMPUTATION [6.1.2]

			    var W = new Array(80); var a, b, c, d, e;
			    for (var i=0; i<N; i++) {
			        // 1 - prepare message schedule 'W'
			        for (var t=0;  t<16; t++) W[t] = M[i][t];
			        for (var t=16; t<80; t++) W[t] = ROTL(W[t-3] ^ W[t-8] ^ W[t-14] ^ W[t-16], 1);

			        // 2 - initialise five working variables a, b, c, d, e with previous hash value
			        a = H0; b = H1; c = H2; d = H3; e = H4;

			        // 3 - main loop
			        for (var t=0; t<80; t++) {
			            var s = Math.floor(t/20); // seq for blocks of 'f' functions and 'K' constants
			            T = (ROTL(a,5) + fHexStr(s,b,c,d) + e + K[s] + W[t]) & 0xffffffff;
			            e = d;
			            d = c;
			            c = ROTL(b, 30);
			            b = a;
			            a = T;
			        }

			        // 4 - compute the new intermediate hash value
			        H0 = (H0+a) & 0xffffffff;  // note 'addition modulo 2^32'
			        H1 = (H1+b) & 0xffffffff;
			        H2 = (H2+c) & 0xffffffff;
			        H3 = (H3+d) & 0xffffffff;
			        H4 = (H4+e) & 0xffffffff;
			    }
			    return H0.toHexStr() + H1.toHexStr() + H2.toHexStr() + H3.toHexStr() + H4.toHexStr();
			}
EOT;

		$js->executeString($scripts);
		$ret = $js->executeString("encode64(getHashCode(encode64(getHashCode('" . $str . "'))))");
		return $ret;
	}
	public static function validateOut($params = "")
	{
		include_once(app_path() . "/lib/CAS.php");
		$phpCas = new \phpCAS();
		
		$phpCas->setDebug('./CASlog.log');
		$phpCas->client(CAS_VERSION_2_0,'auth.eurasia.edu',80,'cas');
		// 验证用户
		$phpCas->setNoCasServerValidation();
		$phpCas->setServerLoginUrl("http://user.ms.eurasia.edu/login"); 
		$phpCas->forceAuthentication();
		$phpCas->logout($params);
	}
	/**
	 * 验证$_GET['ticket']
	 * @return bool
	 */
	public static function validateTicket(&$name)
	{
		//require_once app_path() . "/lib/sust/config.php";
		require_once app_path() . "/lib/CAS.php";
		$phpCas = new \phpCAS();
		$phpCas->client(CAS_VERSION_2_0,'auth.eurasia.edu',80,'cas');
		//$phpCas::setServerLoginUrl('http://login.sust.edu.cn/cas/login');
		// NO SSL validation for the CAS server
		$phpCas->setNoCasServerValidation();
		// 登录成功后访问页面 不设置默认当前页面
		// phpCAS::setServerLoginUrl("http://ip:port/xxx"); 
		//$phpCas->setServerLoginUrl("http://ms.sust.edu.cn"); 
		//这里会检测服务器端的退出的通知，就能实现php和其他语言平台间同步登出了  
		$phpCas->handleLogoutRequests();
		// force CAS authentication
		/*$phpCas->forceAuthentication();
		echo $phpCas->getUser();*/
		/*print basename($_SERVER['SCRIPT_NAME']);
		print session_name();
		print session_id();
		echo phpCAS::getUser(); 
		echo phpCAS::getVersion();*/
		try
		{
			$phpCas->forceAuthentication();
			$user = $phpCas->getUser();
			$name = $phpCas->getName();

			$allInfo = $phpCas->getAttributes();


			$info = array(
				"username" => $user,
				"name" => $name,
				'usertype' => $allInfo['type']
				);
			if(trim($allInfo['type']) != 'T01') {
				return false;
			}else{
				return $info;
			}
		}
		catch (\CAS_AuthenticationException $e)
		{
			
			return false;
		}
	}

	public static function validateEurasia($username, $password)
	{

		//$url = "http://auth.eurasia.edu/cas/v1/tickets";
		$data = array(
				"username" => $username, 
				"password" => $password,
				"service" => "http://user.ms.eurasia.edu/login"
			);
		//echo time() . "<br/>";
		// 获取TGT
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://auth.eurasia.edu/cas/v1/tickets');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		$output = curl_exec($ch);
		curl_close($ch);


		// 获取 ST
		preg_match_all('/action=[\'|"](.*?)[\'|"]/i', $output, $match);
		$action = str_replace('action=', '', $match[0][0]);
		$action = str_replace('"', '', $action);
		$post = array('service' => 'http://user.ms.eurasia.edu/login');

		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $action);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		$ret = curl_exec($ch);
		curl_close($ch);
		// var_dump($ret);
		// echo '<hr/>';

		// // 获取用户信息

		$stInfo = array(
				'ticket' => $ret,
				'service' => utf8_encode('http://user.ms.eurasia.edu/login')
			);
		$ch = curl_init();
		//curl_setopt($ch,CURLOPT_HTTPHEADER,$this_header);
		curl_setopt($ch, CURLOPT_URL, 'http://auth.eurasia.edu/cas/serviceValidate');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($stInfo));

		$info = curl_exec($ch);
		curl_close($ch);

		$xmlparser = xml_parser_create();

		xml_parse_into_struct($xmlparser,$info,$values);

		xml_parser_free($xmlparser);
		$status = false;
		//var_dump($values);exit;
		foreach ($values as $key => $val) {
			if($val['tag'] == "CAS:TYPE"){
				if(trim($val['value']) == "T01"){
					$status = true;
				}
			}
		}

		return $status;

	}
	/**
	 * 使用CURL模拟登录, 提供给客户端使用
	 * @param $username
	 * @param $password
	 * @return bool
	 */
	public static function validate($username, $password)
	{
		$sessionName = 'JSESSIONID';
		$userAgent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36';

		$serviceURL = 'http://' . app()->environment() . '/login';
		$loginURL = ParamsService::get('casloginurl') . '?service=' . $serviceURL;
		$validateURL = ParamsService::get('casvalidateurl');

		//测试
//		$serviceURL = 'http://user.jzhj.gp.test/login';
//		$loginURL = 'http://my.dlmu.edu.cn/cas/login?service=' . $serviceURL;
//		$validateURL = 'http://my.dlmu.edu.cn/cas/serviceValidate';
		// 获取 cookie, 和表单内的lt
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_URL, $loginURL);
		curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
		$contents = curl_exec($ch);
		curl_close ($ch);
		preg_match('/Set-Cookie: ' . $sessionName . '=([^;]*);/', $contents, $cookies);
		preg_match('/<input type="hidden" name="lt" value="([^"]*)"/', $contents, $tmp);

		if (empty($tmp))
		{
			return false;
		}
		$password = static::encryptPassword($password);
		$post = array(
			'loginType' => 0,
			'username'  => $username,
			'password'  => $password,
			'lt'        => $tmp[1],
			'_eventId'  => 'submit',
			'submit.x'  => 41,
			'submit.y'  => 22,
			'submit'    => '登录',
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $loginURL);
		curl_setopt($ch, CURLOPT_COOKIE, 'JSESSIONID=' . $cookies[1]);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_REFERER, $loginURL);
		curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post) );
		curl_exec($ch);
		$info = curl_getinfo($ch);
		print_r($info);
		curl_close ($ch);
		// 登录失败
		if (empty($info['redirect_url']))
		{
			return false;
		}
		preg_match('/ticket=(.*)/', $info['redirect_url'], $tmp2);
		$ticket = $tmp2[1];
		// 验证ticket
		$url = $validateURL. '?ticket=' . $ticket . '&service=' . $serviceURL;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		$contents = curl_exec($ch);
		curl_close ($ch);
		if (preg_match('/cas:authenticationSuccess/', $contents))
		{
			return true;
		}
		return false;
	}

	public static function check($username, $password)
	{
		if (static::validateEurasia($username, $password))
		{
			$email = $username . '@' . app()->env;
			// 获取userid, 并且保存用户信息
			$userId = UserService::saveUser($username, $email, $username);
			return $userId;
		}
		return false;
	}
}
//
//$x = new CASClient();
//$x->login('2011050021', '000000');