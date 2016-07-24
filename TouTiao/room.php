<?php
require_once ("controller/userController.php");
require_once ("controller/newsController.php");
//include ("bean/user.php");
session_start();
ini_set('session.gc_maxlifetime', 3600);
$userController=new UserController();
$newsController=new NewsController();

$type = $_POST ["type"];
//writeData($type);
switch ($type) {
	case "login" :
		$userName = $_POST ["user_name"];
		$password = $_POST ["password"];
		$rememberMe=$_POST ["remember_me"];
		//writeData($userName."   ".$password);
		$userController->login ($userName,$password,$rememberMe);
		break;
	case "register" :
		$userName = $_POST ["user_name"];
		$password = $_POST ["password"];
		$birthday = $_POST ["birthday"];
		$gender = $_POST ["gender"];
		$account = $_POST ["user_account"];
		$interest = $_POST ["interest"];
		$user=new User($userName, $gender, $account, $birthday, $interest,$password);
		$userController->register ($user);
		
		break;
	case "checkAccount" :
		$userAccount = $_POST ["userAccount"];
		$userController->checkAccount($userAccount);
		break;
	case "getRecommendNews" :
		$num = $_POST ["num"];
		$newsController->getRecommendNews($num);
		break;
	case "getNewsByLabel" :
		$num = $_POST ["num"];
		$labelId = $_POST ["labelId"];
		$labelName=$_POST ["labelName"];
		$newsController->getNewsByLabel($labelId,$num,$labelName);
		break;
}

//test mysql
//add data 
//createData();
//createNewsLabelData();
// find data
//$time1=microtime(true);
//echo "<br>time".microtime()."<br>";
//echo $newsDao->getRecommendNews($userId, 10);
// echo "<br>time is :".(microtime(true)-$time1)."<br>";
 
//  $time1=microtime(true);
//   $newsDao->getRecommendNewsdd($userId, $num);
// echo "<br>time is :".(microtime(true)-$time1)."<br>";

function createcommendData(){
	 $newsDao=new NewsDao();
	 $userId=1;
	 $num=300;
	$newsId='';
	for ($i = 1; $i < 301; $i++) {
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
	$labelId=1;
	
	for($i = 1; $i < 301; $i++){
		$newsDao->addNewsLabel($labelId,$i,date("Y-m-d H:i:s",strtotime("+".$i." day")));
	}
}
function writeData($data){
	$file = fopen("D://tt.txt","a");
	fwrite($file,$data);
	fclose($file);
}
?>