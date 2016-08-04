<?php
function createConnection() {
	$url = "localhost:3307";
	$userName = "root";
	$password = "1234";
	$db = "wexun";
	$conn = new mysqli ( $url, $userName, $password, $db );
	return $conn;
}

function closeConnection($conn) {
	if($conn)$conn->close();
}

function writeData($data) {
	$file = fopen ( "D://tt.txt", "a" );
	if(is_array($data)){
		foreach ($data as $temp){
			if(is_array($temp)){
				foreach ($temp as $temp1)
				fwrite ( $file, $temp1 );
			}else{
				fwrite ( $file, $temp );
			}
		}
		
	}else{
		fwrite ( $file, $data );
	}
	
	fclose ( $file );
}
function file_get_contents_utf8($fn) {
	$content = file_get_contents ( $fn );
	return mb_convert_encoding ( $content, 'UTF-8', mb_detect_encoding ( $content, array (
			"ASCII",
			"UTF-8",
			"GB2312",
			"GBK",
			"BIG5"
	) ) );
}
function getIp() {
	$ip = 0;
	if (getenv ( "HTTP_CLIENT_IP" ) && strcasecmp ( getenv ( "HTTP_CLIENT_IP" ), "unknown" ))
		$ip = getenv ( "HTTP_CLIENT_IP" );
		else if (getenv ( "HTTP_X_FORWARDED_FOR" ) && strcasecmp ( getenv ( "HTTP_X_FORWARDED_FOR" ), "unknown" ))
			$ip = getenv ( "HTTP_X_FORWARDED_FOR" );
			else if (getenv ( "REMOTE_ADDR" ) && strcasecmp ( getenv ( "REMOTE_ADDR" ), "unknown" ))
				$ip = getenv ( "REMOTE_ADDR" );
				else if (isset ( $_SERVER ['REMOTE_ADDR'] ) && $_SERVER ['REMOTE_ADDR'] && strcasecmp ( $_SERVER ['REMOTE_ADDR'], "unknown" ))
					$ip = $_SERVER ['REMOTE_ADDR'];
					else
						$ip = 0;

						if ($ip)
							$ip = sprintf ( '%u', ip2long ( $ip ) );
							return $ip;
}

// 加密
function string2secret($str) {
	$key = "123";
	$td = mcrypt_module_open ( MCRYPT_DES, '', 'ecb', '' );
	$iv = mcrypt_create_iv ( mcrypt_enc_get_iv_size ( $td ), MCRYPT_RAND );
	$ks = mcrypt_enc_get_key_size ( $td );

	$key = substr ( md5 ( $key ), 0, $ks );
	mcrypt_generic_init ( $td, $key, $iv );
	$secret = mcrypt_generic ( $td, $str );
	mcrypt_generic_deinit ( $td );
	mcrypt_module_close ( $td );
	return $secret;
}

// 解密
function secret2string($sec) {
	$key = "123";
	$td = mcrypt_module_open ( MCRYPT_DES, '', 'ecb', '' );
	$iv = mcrypt_create_iv ( mcrypt_enc_get_iv_size ( $td ), MCRYPT_RAND );
	$ks = mcrypt_enc_get_key_size ( $td );

	$key = substr ( md5 ( $key ), 0, $ks );
	mcrypt_generic_init ( $td, $key, $iv );
	$string = mdecrypt_generic ( $td, $sec );
	mcrypt_generic_deinit ( $td );
	mcrypt_module_close ( $td );
	return trim ( $string );
}
function str_n_pos($str, $find,$n) {
	if (! $n)
		return 0;
		$length = strlen ( $str );
		$j = 0;
		for($i = 0; $i <= $length; $i ++) {
			if ($str {$i} == $find)
				$j ++;
				if ($j == $n)
					return $i;
		}
		return $length;
}
function str_replace_multi($str, $find1, $find2, $replace) {
	if (! $str || ! $find1 || ! $find2){
		return 0;
	}else{
		$length = strlen ( $str );
		for($i = 0; $i <= $length; $i ++) {
			if ($str {$i} == $find1 || $str {$i} == $find2)
				$str {$i} = $replace;
		}
		return $str;
	}
}
?>