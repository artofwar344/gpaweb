<?php
namespace Ca\Service;

class DesService
{
    static $key = "4vUf7UHQ";
    static $iv = "rsqlTDU3"; //偏移量
   
    public static function DES($key = '4vUf7UHQ', $iv='rsqlTDU3' ) {
    //key长度8例如:1234abcd
        self::$key = $key;
        if( $iv == 0 ) {
            self::$iv = $key; //默认以$key 作为 iv
        } else {
            self::$iv = $iv; //mcrypt_create_iv ( mcrypt_get_block_size (MCRYPT_DES, MCRYPT_MODE_CBC), MCRYPT_DEV_RANDOM );
        }
    }
   
    public static function encrypt($str) {
    //加密，返回大写十六进制字符串
        $size = mcrypt_get_block_size ( MCRYPT_DES, MCRYPT_MODE_CBC );
        $str = self::pkcs5Pad ( $str, $size );
        //$str = mcrypt_cbc(MCRYPT_DES, self::$key, $str, MCRYPT_ENCRYPT, self::$iv );
        //PHP 5.5 已经废除 mcrypt_cbc()函数，使用下面代码代替
        $td = mcrypt_module_open('des', '', 'cbc', '');
        @mcrypt_generic_init($td,self::$key, self::$iv);
        $str = mcrypt_generic($td, $str);

        return strtoupper( bin2hex( $str ) );
    }
   
    public static function decrypt($str) {
    //解密
        $strBin = self::hex2bin( strtolower( $str ) );
        //$str = mcrypt_cbc( MCRYPT_DES, self::$key, $strBin, MCRYPT_DECRYPT, self::$iv );
        //PHP 5.5 已经废除 mcrypt_cbc()函数，使用下面代码代替
        $td = mcrypt_module_open('des', '', 'cbc', '');
        @mcrypt_generic_init($td, self::$key , self::$iv);
        $str = mdecrypt_generic($td, $strBin);
        $str = self::pkcs5Unpad( $str );
        return $str;
    }
   
    public static function hex2bin($hexData) {
        $binData = "";
        for($i = 0; $i < strlen ( $hexData ); $i += 2) {
            $binData .= chr ( hexdec ( substr ( $hexData, $i, 2 ) ) );
        }
        return $binData;
    }

    public static function pkcs5Pad($text, $blocksize) {
        $pad = $blocksize - (strlen ( $text ) % $blocksize);
        return $text . str_repeat ( chr ( $pad ), $pad );
    }
   
    public static function pkcs5Unpad($text) {
        $pad = ord ( $text {strlen ( $text ) - 1} );
        if ($pad > strlen ( $text ))
            return false;
        if (strspn ( $text, chr ( $pad ), strlen ( $text ) - $pad ) != $pad)
            return false;
        return substr ( $text, 0, - 1 * $pad );
    }
   
}