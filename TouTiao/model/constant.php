<?php
$configure=file_get_contents("configure.xml");
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

?>