<?php    
//require 'dao/redisDao.php';
//require 'model/common.php';
 //$redisDao=new RedisDao();
//$redisDao->test();
//print_r($redisDao->getUserStorage(1,10,0)) ;
// print_r( $redisDao->getSearchVal("瓶梅",210,10));
 //print_r($redisDao->getNewsByLabel(100, 60, "science", 1));
 //print_r($redisDao->getDetailNews(580));
 //$redisDao->removeRecomm(394, 1);
// echo $redisDao->pingRedis();
$a="a;b;c;";
		echo mb_substr_count( $a,";");

 //echo time();
// echo "get :".$redisDao->getUserById(1);
// echo "hh :".$redisDao->getUserById(1);



//test str replace
// $str="1,23454;2,453453;6,676";
// $find1=";";
// $find2=",";
// $replace=" ";
// $time=microtime(true);
// for($i=0;$i<10000;$i++){
// 	str_replace_multi($str,$find1,$find2,$replace);
// 	$str="1,23454;2,453453;6,676";
// }
// echo microtime(true)-$time."<br>";
// $time=microtime(true);
// for($i=0;$i<10000;$i++){
// 	str_replace($find1, $replace, $str);
// 	str_replace($find2, $replace, $str);
// 	$str="1,23454;2,453453;6,676";
// }
// echo microtime(true)-$time."<br>";

// $str=str_replace($find1, $replace, $str);
// $str=str_replace($find2, $replace, $str);
// echo $str;
?> 