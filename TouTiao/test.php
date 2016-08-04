<?php
// require 'dao/redisDao.php';
// require 'model/common.php';
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
$redis = new Redis ();
$redis->connect ( '127.0.0.1', 6379 );

$userIds = array (
		"17",
		"1" 
);
$labels = array (
		"default",
		"science"
);
$num = 227;

resetUserRecomm ($redis, $userIds, $num );
resetLabel($redis,$labels,$num);




function resetUserRecomm($redis, $userIds, $num) {
	foreach ( $userIds as $userId ) {
		$redis->set ( "recomUpdate:" . $userId, $num );
		$redis->del ( "scan:" . $userId );
	}
}

function resetLabel($redis, $labels, $num){
	foreach ( $labels as $labelTemp ) {
		$key=$labelTemp . ":update";
		echo $key."<br>";
		$redis->set ($key, $num );
	}
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