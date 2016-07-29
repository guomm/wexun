<?php
require_once ("model/userModel.php");
require_once ("model/newsModel.php");
//include ("bean/user.php");
session_start();
ini_set('session.gc_maxlifetime', 3600);
$userModel=new UserModel();
$newsModel=new NewsModel();

$type = $_POST ["type"];
//writeData($type);
switch ($type) {
	case "login" :
		$userName = $_POST ["user_name"];
		$password = $_POST ["password"];
		$rememberMe=$_POST ["remember_me"];
		//writeData($userName."   ".$password);
		$userModel->login ($userName,$password,$rememberMe);
		break;
	case "register" :
		$userName = $_POST ["user_name"];
		$password = $_POST ["password"];
		$birthday = $_POST ["birthday"];
		$gender = $_POST ["gender"];
		$account = $_POST ["user_account"];
		$interest = $_POST ["interest"];
		$user=new User($userName, $gender, $account, $birthday, $interest,$password);
		$userModel->register ($user);
		
		break;
	case "checkAccount" :
		$userAccount = $_POST ["userAccount"];
		$userModel->checkAccount($userAccount);
		break;
	case "getRecommendNews" :
		$num = $_POST ["num"];
		$newsModel->getRecommendNews($num);
		break;
	case "getNewsByLabel" :
		$num = $_POST ["num"];
		$labelId = $_POST ["labelId"];
		$labelName=$_POST ["labelName"];
		$newsModel->getNewsByLabel($labelId,$num,$labelName);
		break;
	case "getDetailNews" :
		$news_id = $_POST ["news_id"];
		$newsModel->getDetailNews($news_id);
		break;
	case "recommendNews" :
		$news_id = $_POST ["news_id"];
		$newsModel->recommendNews($news_id);
		break;
	case "shareNews" :
		$news_id = $_POST ["news_id"];
		$newsModel->shareNews($news_id);
		break;
	case "storageNews" :
		$news_id = $_POST ["news_id"];
		$newsModel->storageNews($news_id);
		break;
	case "removeStorageNews" :
		$news_id = $_POST ["news_id"];
		$newsModel->removeStorageNews($news_id);
		break;
	case "removeRecommendNews" :
		//$news_id = $_POST ["news_id"];
		//$newsModel->removeStorageNews($news_id);
		echo 1;
		break;
	case "reportNews" :
		$news_id = $_POST ["news_id"];
		$describe = $_POST ["describe"];
		$newsModel->reportNews($news_id,$describe);
		break;
	case "getUserById" :
		$userModel->getUserById();
		break;
		
	case "updateUser" :
		$userName = $_POST ["user_name"];
		$birthday = $_POST ["birthday"];
		$gender = $_POST ["gender"];
		$interest = $_POST ["interest"];
		$user=new User($userName, $gender, $account, $birthday, $interest,$password);
		$userModel->updateUser ($user);
		//echo 1;
		break;
	case "getStorageById" :
		$num = $_POST ["num"];
		$offset = $_POST ["offset"];
		$userModel->getStorageById($num,$offset);
		break;
	case "getStoragePageCount" :
		$userModel->getStoragePageCount();
		break;
	case "searchVal" :
		$num = $_POST ["num"];
		$offset = $_POST ["offset"];
		$userModel->getSearchVal($num,$offset);
		break;
	case "searchValCount" :
		$search_val=$_POST ["search_val"];
		$userModel->getSearchValCount($search_val);
		break;
	case "logout" :
		$userModel->logout();
		break;
}
//test unlogin
//$newsModel->getRecommendNews(10);

//test storage
//$userModel->getStorageById(10,2);

//test mysql
//add data 
//createcommendData();
//createNewsLabelData();
// find data
//$time1=microtime(true);
//echo "<br>time".microtime()."<br>";
//echo $newsDao->getRecommendNews($userId, 10);
// echo "<br>time is :".(microtime(true)-$time1)."<br>";
 
//  $time1=microtime(true);
//   $newsDao->getRecommendNewsdd($userId, $num);
// echo "<br>time is :".(microtime(true)-$time1)."<br>";
//echo getDataByFileName("D:/wa.txt");

//test storage and remvoe
//$news_id=array(2,6,7);
//$newsModel->store($news_id);
//$newsModel->remove($news_id);

//test login
//testLogin();

function  testLogin(){
	$time1=microtime(true);
	for($i=0;$i<50;$i++){
		$ddserDao=new UserDao();
		$ddserDao->login ("123","123");
		$ddserDao->closeConn();
	}
	echo "time is ".(microtime(true)-$time1)." <br>";
	
	$time1=microtime(true);
	for($i=0;$i<200;$i++){
		$ddserDao=new UserDao();
		$ddserDao->loginTest("123","123");
		$ddserDao->closeConn();
	}
	echo "time is ".(microtime(true)-$time1)." <br>";
	
	$time1=microtime(true);
	for($i=0;$i<200;$i++){
		$ddserDao=new UserDao();
		$ddserDao->login ("123","123");
		$ddserDao->closeConn();
	}
	echo "time is ".(microtime(true)-$time1)." <br>";
}

function createcommendData(){
	 $newsDao=new NewsDao();
	 $userId=1;
	 $num=300;
	$newsId='';
	for ($i = 370; $i < 597; $i++) {
		$newsId=$newsId.$i.',';
	}
	$newsId=substr($newsId, 0,-1);
	//echo($newsId);
	if(!$newsDao->addRecommendNews($userId,$newsId)){
		echo "add to newsdd error";
	};
}

function createNewsLabelData(){
	$newsDao=new NewsDao();
	$labelId1=1;
	$labelId2=100;
	for($i = 370; $i < 597; $i++){
		$newsDao->addNewsLabel($labelId1,$i,date("Y-m-d H:i:s",strtotime("+".$i." day")));
	}
	for($i = 370; $i < 597; $i++){
		$newsDao->addNewsLabel($labelId2,$i,date("Y-m-d H:i:s",strtotime("+".$i." day")));
	}
}

?>