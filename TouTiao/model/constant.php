<?php
require_once 'common.php';
$xml = simplexml_load_string(file_get_contents("configure.xml"));
define("sessionTime",(string)$xml->sessionTime);
define("cookieTime",(int)$xml->cookieData->cookieTime);

//writeData(cookieTime." ".cookiePath." ".cookieDomain." ".sessionTime." ");

define("mysqlUrl",(string)$xml->mysqlData->url);
define("mysqlUserName",(string)$xml->mysqlData->userName);
define("mysqlPassword",(string)$xml->mysqlData->password);
define("mysqlDBName",(string)$xml->mysqlData->dbName);

//writeData(mysqlUserName." ".mysqlPassword." ".mysqlDBName." ".mysqlUrl." ");

define("redisIP",(string)$xml->redisData->ip);
define("redisPort",(string)$xml->redisData->port);
define("newsContentOutTime",(string)$xml->redisData->redisOutTime->newsContentOutTime);
define("userRecomm",(string)$xml->redisData->redisOutTime->userRecomm);

//writeData(redisIP." ".redisPort." ".newsContentOutTime." ".userRecomm." ");

define("scanOutTime",(string)$xml->redisData->redisOutTime->scanOutTime);
define("tempOutTime",(string)$xml->redisData->redisOutTime->tempOutTime);
define("newsOutTime",(string)$xml->redisData->redisOutTime->newsOutTime);
define("userStorageOutTime",(string)$xml->redisData->redisOutTime->userStorageOutTime);
define("userInfoOutTime",(string)$xml->redisData->redisOutTime->userInfoOutTime);

//writeData(scanOutTime." ".tempOutTime." ".newsOutTime." ".userStorageOutTime." ".userInfoOutTime);

?>