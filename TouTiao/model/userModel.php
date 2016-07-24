<?php
require_once ("dao/userDao.php");
class UserModel {
	private $userDao;
	function __construct() {
		$this->userDao = new UserDao ();
	}
	function loginCheck($userAccount, $password, $rememberMe) {
		$result = $this->userDao->login ( md5 ( $userAccount ), md5 ( $password ) );
		$user_info = json_decode ( $result );
		$_SESSION ["userId"] = string2secret($user_info->user_id);
		$_SESSION ["userName"] = $user_info->user_name;
		setcookie ( "userId", string2secret($user_info->user_id) );
		setcookie ( "userName", $user_info->user_name );
		
		if (count ( $rememberMe )) {
			setcookie ( "userAccount", $userAccount);
			setcookie ( "password", $password );
		} else {
			setcookie ( "userAccount", "" );
			setcookie ( "password", "" );
		}
		return $result;
	}
	function registerU($user) {
		// writeData($user->account." ".$user->password );
		$user->account = md5 ( $user->account );
		$user->password = md5 ( $user->password );
		$num = count ( $user->interest );
		$val = 0;
		for($i = 0; $i < $num; ++ $i) {
			$val += $user->interest [$i];
		}
		$user->interest = $val;
		// writeData($user->account." ".$user->password );
		return $this->userDao->registerUser ( $user );
	}
	function checkAccount($userAccount) {
		return $this->userDao->checkUserAccount ( md5 ( $userAccount ) );
	}
}

//加密
function string2secret($str)
{
	$key = "123";
	$td = mcrypt_module_open(MCRYPT_DES,'','ecb','');
	$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
	$ks = mcrypt_enc_get_key_size($td);

	$key = substr(md5($key), 0, $ks);
	mcrypt_generic_init($td, $key, $iv);
	$secret = mcrypt_generic($td, $str);
	mcrypt_generic_deinit($td);
	mcrypt_module_close($td);
	return $secret;
}

//解密
function secret2string($sec)
{
	$key = "123";
	$td = mcrypt_module_open(MCRYPT_DES,'','ecb','');
	$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
	$ks = mcrypt_enc_get_key_size($td);

	$key = substr(md5($key), 0, $ks);
	mcrypt_generic_init($td, $key, $iv);
	$string = mdecrypt_generic($td, $sec);
	mcrypt_generic_deinit($td);
	mcrypt_module_close($td);
	return trim($string);
}
?>