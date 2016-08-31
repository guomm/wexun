<?php
/*$configure=file_get_contents("configure.xml");
if(!$configure){
	$configure=file_get_contents("../configure.xml");
}
$xml = simplexml_load_string($configure);

define("sessionTime",(int)$xml->sessionTime);
define("cookieTime",(int)$xml->cookieData->cookieTime);

//writeData(cookieTime." ".cookiePath." ".cookieDomain." ".sessionTime." ");

define("mysqlUrl",(string)$xml->mysqlData->url);
define("mysqlUserName",(string)$xml->mysqlData->userName);
define("mysqlPassword",(string)$xml->mysqlData->password);
define("mysqlDBName",(string)$xml->mysqlData->dbName);

//writeData(mysqlUserName." ".mysqlPassword." ".mysqlDBName." ".mysqlUrl." ");

define("redisIP",(string)$xml->redisData->ip);
define("redisPort",(string)$xml->redisData->port);
define("newsContentOutTime",(int)$xml->redisData->redisOutTime->newsContentOutTime);
define("userRecomm",(int)$xml->redisData->redisOutTime->userRecomm);

//writeData(redisIP." ".redisPort." ".newsContentOutTime." ".userRecomm." ");

define("scanOutTime",(int)$xml->redisData->redisOutTime->scanOutTime);
define("tempOutTime",(int)$xml->redisData->redisOutTime->tempOutTime);
define("newsOutTime",(int)$xml->redisData->redisOutTime->newsOutTime);
define("userStorageOutTime",(int)$xml->redisData->redisOutTime->userStorageOutTime);
define("userInfoOutTime",(int)$xml->redisData->redisOutTime->userInfoOutTime);
define("searchOutTime",(int)$xml->redisData->redisOutTime->searchOutTime);

//writeData(scanOutTime." ".tempOutTime." ".newsOutTime." ".userStorageOutTime." ".userInfoOutTime);
 */
//-------------------------------------------------------------------
define("sessionTime",604800);
define("cookieTime",604800);

define("mysqlUrl","127.0.0.1:3306");
define("mysqlUserName","root");
define("mysqlPassword","wexun");
define("mysqlDBName","wexun");

define("redisIP","127.0.0.1");
define("redisPort","6379");
define("newsContentOutTime",7200);
define("userRecomm",1200);

define("scanOutTime",1200);
define("tempOutTime",3);
define("newsOutTime",7200);
define("userStorageOutTime",600);
define("userInfoOutTime",600);
define("searchOutTime",600);

define ("superUser",123);
?>
