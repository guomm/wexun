<?php
require_once ("dao/userDao.php");
require_once("common.php");
class UserModel {
	private $userDao;
	function __construct() {
		$this->userDao = new UserDao ();
	}
	function login($userAccount, $password, $rememberMe) {
		$result = $this->userDao->login ( md5 ( $userAccount ), md5 ( $password ) );
		if ($result) {
			$user_info = json_decode ( $result );
			$_SESSION ["userId"] = string2secret ( $user_info->user_id );
			$_SESSION ["userName"] = $user_info->user_name;
			setcookie ( "userId", string2secret ( $user_info->user_id ) );
			setcookie ( "userName", $user_info->user_name );
			setcookie ( "userAccount", $userAccount );
			if (count ( $rememberMe )) {
				setcookie ( "password", $password );
			} else {
				setcookie ( "password", "" );
			}
		}
		
		echo $result;
		$this->userDao->closeConn();
	}
	function register($user) {
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
		echo $this->userDao->registerUser ( $user );
		$this->userDao->closeConn();
	}
	function updateUser($user) {
		// writeData($user->account." ".$user->password );
		$num = count ( $user->interest );
		$val = 0;
		for($i = 0; $i < $num; ++ $i) {
			$val += $user->interest [$i];
		}
		$user->interest = $val;
		
		$userId = secret2string ( $_SESSION ["userId"] );
		// writeData($user->account." ".$user->password );
		echo $this->userDao->updateUser ( $user, $userId );
		$this->userDao->closeConn();
	}
	function checkAccount($userAccount) {
		$result = $this->userDao->checkUserAccount ( md5 ( $userAccount ) );
		$returnVal='';
		if ($result)
			$returnVal= 'false';
		else
			$returnVal= 'true';
		echo $returnVal;
		
		$this->userDao->closeConn();
	}
	function getUserById() {
		// writeData($_SESSION ["userId"]);
		$userId = secret2string ( $_SESSION ["userId"] );
		// writeData(" ".$userId);
		echo $this->userDao->getUserInfo ( $userId );
		$this->userDao->closeConn();
	}
	function getStorageById($num, $offset) {
		$userId = secret2string ( $_SESSION ["userId"] );
		echo $this->userDao->getStorageById ( $userId, $num, $offset );
		$this->userDao->closeConn();
	}
	function getStoragePageCount() {
		$userId = secret2string ( $_SESSION ["userId"] );
		echo $this->userDao->getStoragePageCount ( $userId );
		$this->userDao->closeConn();
	}
	function getSearchValCount($search_val) {
		echo $this->userDao->getSearchValCount ( $search_val );
		$this->userDao->closeConn();
	}
	function getSearchVal($num, $offset) {
		echo $this->userDao->getSearchVal ( $num, $offset );
		$this->userDao->closeConn();
	}
	
	function logout(){
		unset($_SESSION['userId']);
		unset($_SESSION['userName']);
		setcookie ( "userId", '',time()-3600 );
		setcookie ( "userName", '',time()-3600);
		echo 1;
		$this->userDao->closeConn();
	}
}


?>