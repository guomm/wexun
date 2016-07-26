<?php
require_once ("model/userModel.php");
class UserController {
	private $userModel;
	function __construct() {
		$this->userModel = new UserModel ();
	}
	function login($userAccount, $password,$rememberMe) {
		$result = $this->userModel->loginCheck ( $userAccount, $password,$rememberMe);
		if ($result) {
			//登录成功
			//writeData($result);
			
			
			//writeData($_SESSION["userId"]."   ".$_SESSION["userName"]);
			echo $result;
			//拉取信息
			
			//跳转到主页
			//jumpPage("view/index.php");
		}else{
			//登录失败
			echo 0;
		}
	}
	
	function register($user) {
		echo $this->userModel->registerU ( $user );
	}
	
	function updaetUser ($user){
		echo $this->userModel->updateUser ( $user );
	}
	
	function checkAccount($userAccount) {
		$result = $this->userModel->checkAccount ( $userAccount );
		if ($result)
			echo 'false';
		else
			echo 'true';
	}
	
	function getUserById(){
		echo $this->userModel->getUserById();
	}
	
	function getStorageById($num,$offset){
		echo $this->userModel->getStorageById($num,$offset);
	}
	function getStoragePageCount(){
		echo $this->userModel->getStoragePageCount();
	}
	
	function getSearchValCount($search_val){
		echo $this->userModel->getSearchValCount($search_val);
	}
	
	function getSearchVal($num,$offset){
		echo $this->userModel->getSearchVal($num,$offset);
	}
}



function jumpPage($url) {
	echo "<script type='text/javascript'>";
	echo "window.location.href='$url';";
	echo "</script>";
}
?>