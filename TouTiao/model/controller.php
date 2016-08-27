<?php
require_once  'model/redisFactory.php';
require_once 'dao/redisDao.php';
require_once 'model/redisModel.php';
class Controller {
	private $model;
	private $userId;
	
	function __construct() {
// 		$type=1;
// 		if($type ==1){
// 			//redis
// 			$dao=new RedisDao();
// 			//$ip="127.0.0.1";
// 			//$port=6379;
// 			$this->model=new RedisModel ($dao,redisIP,redisPort);
// 		}
		$factory=RedisFactory::singleton();
		$this->model=$factory->createModel();
		//$this->model=ModelFactory::singleton()->createModel($type);
		//$factory=ModelFactory::singleton();
		//$this->model=$factory->
		if ($_SESSION ["userId"])
			$this->userId = $_SESSION ["userId"];
	}
	
	//function login
	function login ($userName,$password,$rememberMe){
		echo $this->model->login($userName, $password, $rememberMe);
	}
	
	function register ($user){
		echo $this->model->register($user);
	}
	
	function checkAccount($userAccount){
		echo $this->model->checkAccount($userAccount);
	}
	function getUserById() {
		echo $this->model->getUserById($this->userId);
	}
	function updateUser($user) {
		echo $this->model->updateUser($user, $this->userId);
	}
	function getUserStorage( $num, $offset) {
		echo json_encode($this->model->getStorageById($this->userId, $num, $offset));
	}

	
	function getStoragePageCount() {
		echo $this->model->getStoragePageCount($this->userId);
	}
	
	function getSearchValCount($search_val) {
		echo $this->model->getSearchValPageCount($search_val);
	}
	
	function getSearchVal($search_val, $offset, $num, $pageCount) {
		echo json_encode($this->model->getSearchVal($search_val, $offset, $num, $pageCount));
	}
	
	function getRecommendNews($num) {
		
		$result= $this->model->getRecommendNews($num, $this->userId);
		if($result) echo json_encode($result);
		else echo 0;
	}
	
	function getNewsByLabel($labelId, $num, $labelName) {
		echo json_encode($this->model->getNewsByLabel($labelId, $num, $labelName,$this->userId));
	}
	
	function getDetailNews($news_id){
		echo json_encode($this->model->getDetailNews($news_id,$this->userId)) ;
	} 
	
	function recommendNews($news_id){
		echo $this->model->recommendNews($news_id, $this->userId);
	
	}
	
	function storageNews($news_id){
		echo $this->model->storageNews($news_id, $this->userId);
	}
	
	function removeStorage($news_id) {
		echo $this->model->removeStorage($news_id, $this->userId);
	}
	
	function removeRecomm($news_id){
		echo $this->model->removeRecomm($news_id, $this->userId);
	}
	
	function reportNews($news_id,$describe){
		echo $this->model->reportNews($news_id,$this->userId,$describe);
	}

	function logout(){
		echo $this->model->logout();
	}
	
}

?> 