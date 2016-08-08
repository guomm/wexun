<?php
require 'model/common.php';
require_once 'model/constant.php';
require 'dao/abstractDao.php';
 require 'dao/redisDao.php';


// $redisDao=new RedisDao();
// $redisDao->test();
// print_r($redisDao->getUserStorage(1,10,0)) ;
// print_r( $redisDao->getSearchVal("瓶梅",210,10));
// print_r($redisDao->getNewsByLabel(100, 60, "science", 1));
// print_r($redisDao->getDetailNews(580));
// $redisDao->removeRecomm(394, 1);
// echo $redisDao->pingRedis();
// $a="a;b;c;";
// echo mb_substr_count( $a,";");

// require 'model/constant.php';
// echo time();
// echo "<br>";
// echo time()+cookieTime;
// echo "<br>";
// echo session_id()."  u<br>";
// echo $_COOKIE["PHPSESSID"];
// echo "$sessionTime    $cookieTime <br>";
// //$xml = simplexml_load_string(configure);
// print_r($xml);
// $login = $xml->mysqlData->url;//这里返回的依然是个SimpleXMLElement对象
// echo "<br>";
// print_r($login);
// echo "<br>";
// $login =  $xml->redisData;//在做数据比较时，注意要先强制转换
// print_r($login);

	
// $login = (string) $xml->login;//在做数据比较时，注意要先强制转换
// print_r($login);
//$a="dsadads\tfdfdsfdsf\r\nfdfdsfdf\r\nhh";
//writeData($a);
//require 'model/writeToDisk.php';

 
 //updateData();

// $result=$redis->keys("news:*");
// print_r($result);
// echo "<br>";
// foreach ($result as $tempKey){
// 	$temp=$redis->hGetAll($tempKey);
// 	print_r($temp);
// 	echo "<br>";
// }

function updateData(){
	$redis = new Redis ();
	$redis->connect ( '127.0.0.1', 6379 );
	$userIds = array (
			"21",
			"1"
	);
	$labels = array (
			"def",
			"sc"
	);
	$num = 227;
	
	resetUserRecomm ($redis, array(1), $num );
	resetLabel($redis,$labels,$num,$userIds);
}





function resetUserRecomm($redis, $userIds, $num) {
	foreach ( $userIds as $userId ) {
		$redis->set ( "recu:" . $userId, $num );
	//	$redis->del ( "scan:" . $userId );
	}
}

function resetLabel($redis, $labels, $num,$userIds){
	foreach ( $labels as $labelTemp ) {
		foreach ($userIds as $userId){
			$key=$labelTemp .$userId. ":u";
			echo $key."<br>";
			$redis->set ($key, $num );
		}
		
	}
	$reidsDao=new RedisDao();
	$result = $reidsDao->getNewsByLabel ( 100, 0,227 );
				$redis->pipeline ();
				foreach ( $result as $newsId ) {
					$redis->lPush (  "def:v", $newsId ["news_id"] );
				}
		$redis->exec ();
}

// $a="\$redis->zAdd('userStorage:1',55,380,18,380,23,381,323,382,2323,383,10323,384,12123,385,12313,386,12332,388,632,388,532,389,4332,390,32,391);";
// eval($a);
// echo $redis->zCard('userStorage:1');
// echo time();
// echo "get :".$redisDao->getUserById(1);
// echo "hh :".$redisDao->getUserById(1);

// test str replace
// $str="1,23454;2,453453;6,676";
// $find1=";";
// $find2=",";
// $replace=" ";
// $time=microtime(true);
// for($i=0;$i<10000;$i++){
// str_replace_multi($str,$find1,$find2,$replace);
// $str="1,23454;2,453453;6,676";
// }
// echo microtime(true)-$time."<br>";
// $time=microtime(true);
// for($i=0;$i<10000;$i++){
// str_replace($find1, $replace, $str);
// str_replace($find2, $replace, $str);
// $str="1,23454;2,453453;6,676";
// }
// echo microtime(true)-$time."<br>";

// $str=str_replace($find1, $replace, $str);
// $str=str_replace($find2, $replace, $str);
// echo $str;
?> 