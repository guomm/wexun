<?php
require_once ("model/controller.php");

session_start();
ini_set('session.gc_maxlifetime', 3600);
$_SESSION["default"]=0;
$_SESSION["science"]=0;
// 1 is redis
$controller=new Controller(1);
$type = $_POST ["type"];
//writeData($type);
switch ($type) {
	case "login" :
		$userName = $_POST ["user_name"];
		$password = $_POST ["password"];
		$rememberMe=$_POST ["remember_me"];
		//writeData($userName."   ".$password);
		$controller->login ($userName,$password,$rememberMe);
		break;
	case "register" :
		$userName = $_POST ["user_name"];
		$password = $_POST ["password"];
		$birthday = $_POST ["birthday"];
		$gender = $_POST ["gender"];
		$account = $_POST ["user_account"];
		$interest = $_POST ["interest"];
		$user=new User($userName, $gender, $account, $birthday, $interest,$password);
		$controller->register ($user);
		break;
	case "checkAccount" :
		$userAccount = $_POST ["userAccount"];
		$controller->checkAccount($userAccount);
		break;
	case "getRecommendNews" :
		$num = $_POST ["num"];
		$controller->getRecommendNews($num);
		break;
	case "getNewsByLabel" :
		$num = $_POST ["num"];
		$labelId = $_POST ["labelId"];
		$labelName=$_POST ["labelName"];
		$controller->getNewsByLabel($labelId,$num,$labelName);
		break;
	case "getDetailNews" :
		$news_id = $_POST ["news_id"];
		$controller->getDetailNews($news_id);
		break;
	case "recommendNews" :
		$news_id = $_POST ["news_id"];
		$controller->recommendNews($news_id);
		break;
	case "shareNews" :
		//$news_id = $_POST ["news_id"];
		//$controller->shareNews($news_id);
		break;
	case "storageNews" :
		$news_id = $_POST ["news_id"];
		$controller->storageNews($news_id);
		break;
	case "removeStorageNews" :
		$news_id = $_POST ["news_id"];
		$controller->removeStorage($news_id);
		break;
	case "removeRecommendNews" :
		$news_id = $_POST ["news_id"];
		$controller->removeRecomm($news_id);
		break;
	case "reportNews" :
		$news_id = $_POST ["news_id"];
		$describe = $_POST ["describe"];
		$controller->reportNews($news_id,$describe);
		break;
	case "getUserById" :
		$controller->getUserById();
		break;
		
	case "updateUser" :
		$userName = $_POST ["user_name"];
		$birthday = $_POST ["birthday"];
		$gender = $_POST ["gender"];
		$interest = $_POST ["interest"];
		writeData("len:".count($interest));
		$user=new User($userName, $gender, "", $birthday, $interest,"");
		$controller->updateUser ($user);
		break;
	case "getStorageById" :
		$num = $_POST ["num"];
		$offset = $_POST ["offset"];
		$controller->getUserStorage($num,$offset);
		break;
	case "getStoragePageCount" :
		$controller->getStoragePageCount();
		break;
	case "searchVal" :
		$num = $_POST ["num"];
		$offset = $_POST ["offset"];
		$search_val=$_POST ["search_val"];
		$pageCount=$_POST ["pageCount"];
		$controller->getSearchVal($search_val,$offset,$num,$pageCount);
		break;
	case "searchValCount" :
		$search_val=$_POST ["search_val"];
		$controller->getSearchValCount($search_val);
		break;
	case "logout" :
		$controller->logout();
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