<?php
abstract class AbstractModel {
	protected $dao;
	function __construct($dao) {
		$this->dao = $dao;
	}
	function login($userAccount, $password, $rememberMe) {
		$result = $this->dao->login ( md5 ( $userAccount ), md5 ( $password ) );
		if ($result) {
			$user_info = json_decode ( $result );
			$_SESSION ["userId"] = $user_info->user_id ;
			$_SESSION ["login"] = 1;
			setcookie ( "userId", string2secret ( $user_info->user_id ) ,time()+cookieTime);
			setcookie("PHPSESSID",session_id(),time()+cookieTime);
			setcookie ( "userName", $user_info->user_name ,time()+cookieTime);
			setcookie ( "userAccount", string2secret ($userAccount),time()+cookieTime );
			if (count ( $rememberMe )) {
				setcookie ( "password", string2secret ($password) ,time()+cookieTime);
			} else {
				setcookie ( "password", "" ,time()+cookieTime);
			}
			
		}
		return $result;
	}
	function register($user) {
		$user->account = md5 ( $user->account );
		$user->password = md5 ( $user->password );
		$num = count ( $user->interest );
		$val = 0;
		for($i = 0; $i < $num; ++ $i) {
			$val += $user->interest [$i];
		}
		$user->interest = $val;
		return $this->dao->registerUser ( $user );
	}
	
	function checkAccount($userAccount) {
		$result = $this->dao->checkUserAccount ( md5 ( $userAccount ) );
		$returnVal = '';
		if ($result)
			$returnVal = 'false';
		else
			$returnVal = 'true';
		return $returnVal;
	}
	function recommendNews($news_id, $userId) {
		// 写入推荐事件
		$result = $this->dao->recommendNews ( $news_id, $userId );
		// 更改新闻推荐个数
		//$this->dao->addOneRecommendNum ( $news_id, 1 );
		return $result;
	}
	function storageNews($news_id, $userId) {
		// 写入收藏事件
		//writeData($news_id);
		//writeData($userId);
		return $this->dao->storageNews ( $news_id, $userId );
	}
	function removeStorage($news_id,$userId) {
		return $this->dao->removeStorage ( $news_id, $userId );
	}
	function removeRecomm($news_id, $userId) {
		$result = $this->dao->removeRecomm ( $news_id, $userId );
		// 更改新闻推荐个数
		//$this->dao->addOneRecommendNum ( $news_id, - 1 );
		return $result;
	}
	function reportNews($news_id, $userId,$describe) {
		echo $this->dao->reportNews ( $news_id, $userId,$describe );
	}
	function logout() {
		session_destroy();
		setcookie ( "password", "", time () - 3600);
		setcookie ( "userName", "", time () - 3600);
		echo 1;
	}
	abstract function updateUser($user,$userId);
	abstract function getUserById($userId);
	abstract function getStorageById($userId, $num, $offset);
	abstract function getStoragePageCount($userId);
	abstract function getSearchValPageCount($search_val);
	abstract function getSearchVal($search_val, $offset, $num, $pageCount);
	abstract function getRecommendNews($num,$userId);
	abstract function getDetailNews($news_id,$userId);
	abstract function getNewsByLabel($labelId, $num, $labelName,$userId);
}
?>