<?php    
require 'dao/redisDao.php';

$redisDao=new RedisDao();
echo "get :".$redisDao->getUserById(1);
echo "hh :".$redisDao->getUserById(1);
?> 