<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 14-7-28
 * Time: 下午4:21
 * To change this template use File | Settings | File Templates.
 */

namespace Ca\Service;


/*class EncryptService {

}*/
/**
 * des加密和解密
 * Class DesUtil
 * @package Ca
 */
class DesUtil {
	var $key;
	function __construct($key)
	{
		$this->key = $key;
	}

	function encrypt($input)
	{
		$size = mcrypt_get_block_size('des', 'ecb');
		$input = $this->pkcs5_pad($input, $size);
		$key = $this->key;
		$td = mcrypt_module_open('des', '', 'ecb', '');
		$iv = @mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		@mcrypt_generic_init($td, $key, $iv);
		$data = mcrypt_generic($td, $input);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		$data = base64_encode($data);
		return $data;
	}

	function decrypt($encrypted)
	{
		$encrypted = base64_decode($encrypted);
		$key =$this->key;
		$td = mcrypt_module_open('des','','ecb','');
		//使用MCRYPT_DES算法,cbc模式
		$iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		$ks = mcrypt_enc_get_key_size($td);
		@mcrypt_generic_init($td, $key, $iv);
		//初始处理
		$decrypted = mdecrypt_generic($td, $encrypted);
		//解密
		mcrypt_generic_deinit($td);
		//结束
		mcrypt_module_close($td);
		$y=$this->pkcs5_unpad($decrypted);
		return $y;
	}


	function pkcs5_pad ($text, $blocksize)
	{
		$pad = $blocksize - (strlen($text) % $blocksize);
		return $text . str_repeat(chr($pad), $pad);
	}

	function pkcs5_unpad($text)
	{
		$pad = ord($text{strlen($text)-1});
		if ($pad > strlen($text))
			return false;
		if (strspn($text, chr($pad), strlen($text) - $pad) != $pad)
			return false;
		return substr($text, 0, -1 * $pad);
	}

}

/**
 * rsa加密和解密
 * Class RsaUtil
 * @package Ca
 */
class RsaUtil {
	var $pubkey = null;
	var $prikey = null;
	var $padding = OPENSSL_PKCS1_PADDING;

	public function setPublicKey($publickey)
	{
		$this->pubkey = openssl_pkey_get_public($publickey);
		return $this;
	}

	public function setPrivateKey($privatekey)
	{
		$this->prikey = openssl_pkey_get_private($privatekey);
		return $this;
	}

	public function setPaddingMethod($padding)
	{
		$this->padding = $padding;
		return $this;
	}


	public function publicEncrypt($input)
	{
		openssl_public_encrypt($input, $encrypted, $this->pubkey, $this->padding);
		return base64_encode($encrypted);
	}

	public function privateDecrypt($input)
	{
		openssl_private_decrypt(base64_decode($input), $decrypted, $this->prikey, $this->padding);
		return $decrypted;
	}

	public function privateEncrypt($input)
	{
		openssl_private_encrypt($input, $encrypted, $this->prikey, $this->padding);
		return base64_encode($encrypted);
	}

	public function publicDecrypt($input)
	{
		openssl_public_decrypt(base64_decode($input), $decrypted, $this->pubkey, $this->padding);
		return $decrypted;
	}
}