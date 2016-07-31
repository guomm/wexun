<?php
require 'userDao.php';
require 'newsDao.php';
class RedisDao {
	private $redis;
	private $userDao;
	private $newsDao;
	static $userInfoOutTime = 600;
	static $userStorageOutTime = 600;
	static $newsOutTime = 7200;
	static $searchOutTime = 7200;
	function __construct() {
		$this->redis = new Redis ();
		$this->redis->connect ( '127.0.0.1', 6379 );
	}
	function getUserDao() {
		if (! $this->userDao) {
			$this->userDao = new UserDao ();
			// echo "create userDao";
		}
		return $this->userDao;
	}
	function getNewsDao() {
		if (! $this->newsDao) {
			$this->newsDao = new NewsDao ();
			// echo "create userDao";
		}
		return $this->newsDao;
	}
	
	// function test(){
	
	// }
	function getUserById($userId) {
		$result = $this->redis->hGetAll ( "user:" . $userId );
		if (! $result) {
			// redis不存在个人信息，从数据库中加载
			$result = $this->getUserDao ()->getUserInfo ( $userId );
			$this->redis->hMset ( "user:" . $userId, $result );
			$this->redis->expire ( "user:" . $userId, slef::$userInfoOutTime );
		}
		return json_encode ( $result );
	}
	function updateUser($user, $userId) {
		$result = $this->getUserDao ()->updateUser ( $user, $userId );
		$userChanges = array (
				"user_gender" => $user->gender,
				"user_name" => $user->name,
				"user_birthday" => $user->birthday,
				"user_label" => $user->interest 
		);
		if ($result) {
			$this->redis->hMset ( "user:" . $userId, $userChanges );
			$this->redis->expire ( "user:" . $userId, slef::$userInfoOutTime );
		}
		return $result;
	}
	function getUserStorage($userId, $num, $offset) {
		$userStorageIds = $this->redis->zRange ( "userStorage:" . $userId, $offset, $offset + $num - 1 );
		if ($userStorageIds) {
			// echo "have userStorageIds";
			// 从redis中取出了收藏的新闻id，根据新闻Id获取新闻
			return $this->getNewsByIds ( $userStorageIds );
		} else {
			// echo "no userStorageIds" . $userId;
			// print_r($userStorageIds);
			// redis中不存在用户的收藏，从本地数据库中取出
			$userStorage = $this->getUserDao ()->getStorageByUserId ( $userId );
			// echo "dbuserStorage:" . $userStorage . "<br>";
			if ($userStorage) {
				$find1 = ";";
				$replace = ",";
				// 将取出的数据放入redis,数据库中的数据存储格式为 storagetime,newsId;storagetime,newsId
				// $userStorage
				$userStorage = str_replace ( $find1, $replace, $userStorage );
				// echo "dbuserStorage:".$userStorage."<br>";
				$this->redis->pipeline ();
				$redisVal = "\$this->redis->zAdd('userStorage:" . $userId . "'," . $userStorage . ");";
				// echo $redisVal;
				eval ( $redisVal );
				$this->redis->expire ( "userStorage:" . $userId, self::$userStorageOutTime );
				$this->redis->zRange ( "userStorage:" . $userId, $offset, $offset + $num - 1 );
				$userStorageIds = $this->redis->exec () [2];
				// print_r ( $userStorageIds );
				return $this->getNewsByIds ( $userStorageIds );
			}
			return 0;
		}
	}
	function test() {
		// $val=array("val3"=>"3","val4"=>"4");
		// print_r(explode ( " ", $val ));
		// $val="13,val13;14,val14";
		// $find1=";";
		// $replace=",";
		// $userId="s";
		// $userStorage=str_replace($find1, $replace, $val);
		// echo $userStorage."<br>";
		// $redisVal="\$this->redis->zAdd(s" . $userId.",".$userStorage.");";
		// eval($redisVal);
		// call_user_func_array(array($this->redis, "zAdd"), array("ss","7", "val7","8" ,"val8"));
		// $this->redis->zAdd('ss', eval( "9,val9,10,val10") );
		// $dd="\$this->redis->zAdd(ss,".$val.");";
		// echo $dd."<br>";
		// eval($dd);
		// eval("\$this->redis->zAdd(\"ss\",".$val.")");
		// print_r ($this->redis->zRange("ss",0,-1));
		
		// $this->redis->pipeline();
		// $newsIds=array("1","2","3","4");
		// foreach ( $newsIds as $newsId ) {
		// $this->redis->hGetAll ( "ne:" . $newsId );
		// //$news [] = $curNews;
		// }
		// $news=$this->redis->exec();
		// for($i=0;$i<count($news); $i++){
		// if(!$news[$i]){
		// echo $i."<br>";
		// }
		// }
		// print_r($news) ;
		
		// $a=array("1","2");
		// $a=array("v","c");
		// print_r($a) ;
	}
	function getNewsByIds($newsIds) {
		$unexistNewsIds = '';
		$this->redis->pipeline ();
		foreach ( $newsIds as $newsId ) {
			$this->redis->hGetAll ( "news:" . $newsId );
		}
		$newss = $this->redis->exec ();
		// print_r($newss);
		$counts = count ( $newss );
		for($i = 0; $i < $counts; $i ++) {
			if (! $newss [$i]) {
				$unexistNewsIds = $unexistNewsIds . $newsIds [$i] . ",";
			}
		}
		// echo "<br>unexistNewsIds:".$unexistNewsIds."<br>";
		if (strlen ( $unexistNewsIds )) {
			// 说明有些新闻不存在，将这些新闻从数据库中取出
			$unexistNewsIds = substr ( $unexistNewsIds, 0, - 1 );
			
			$newssDb = $this->getNewsDao ()->getNewsByIds ( $unexistNewsIds );
			// print_r($newssDb);
			// echo "<br>";
			// 将从数据库取出的新闻信息放入redis
			$this->redis->pipeline ();
			foreach ( $newssDb as $tempNews ) {
				// echo $tempNews["news_id"]." a ";
				$this->redis->hMset ( "news:" . $tempNews ["news_id"], $tempNews );
				$this->redis->expire ( "news:" . $tempNews ["news_id"], self::$newsOutTime );
			}
			// $this->redis->exec();
			// 重置news，依次根据新闻id从redis中获取内容
			// unset ( $newss );
			// $this->redis->pipeline();
			foreach ( $newsIds as $newsId ) {
				$this->redis->hGetAll ( "news:" . $newsId );
			}
			// $newss=$this->redis->exec ();
			$newss = array_slice ( $this->redis->exec (), count ( $newssDb)*2 );
		}
		return $newss;
	}
	function getStoragePageCount($userId) {
		$count = $this->redis->zCard ( "userStorage:" . $userId );
		// echo " a ".$count;
		if (! $count) {
			// redis中不存在用户的收藏，从本地数据库中取出
			$userStorage = $this->getUserDao ()->getStorageByUserId ( $userId );
			if ($userStorage) {
				$find1 = ";";
				$replace = ",";
				echo self::$userStorageOutTime . " dd";
				// 将取出的数据放入redis,数据库中的数据存储格式为 storagetime,newsId;storagetime,newsId
				$userStorage = str_replace ( $find1, $replace, $userStorage );
				echo "dbuserStorage:" . $userStorage . "<br>";
				$this->redis->pipeline ();
				$redisVal = "\$this->redis->zAdd('userStorage:" . $userId . "'," . $userStorage . ");";
				// echo $redisVal;
				eval ( $redisVal );
				$this->redis->expire ( "userStorage:" . $userId, self::$userStorageOutTime );
				$this->redis->zCard ( "userStorage:" . $userId );
				$count = $this->redis->exec () [2];
				// print_r($count);
				// $count=$count[2];
			}
		}
		return ceil ( $count / 10 );
	}
	
	function getSearchValCount($search_val) {
		$count=$this->redis->get("searchCount:".$search_val);
		if(!$count){
			$count= $this->getUserDao()->getSearchValCount ( $search_val );
			$this->redis->setex("searchCount:".$search_val,self::$searchOutTime,$count);
		}
		return $count;
	}
	
	function getSearchVal($search_val,$offset,$num,$pageCount) {
		//$this->redis->pipeline();
		$page=intval($offset/$num)+1;
		$searchNewsIds=$this->redis->lrange("searchVal:".$search_val.":".$page,0,$num-1);
		if(!$searchNewsIds){
			echo "d";
			 //该页对应的内容不在redis中，需要去数据库中取,一次取5页
			 //判断是从左边还是右边
			 if($page==$pageCount){
			 	$offset=$offset-$num*4;
			 }else if($this->redis->lindex("searchVal:".$search_val.":".($page+1),0)){
			 	$offset=$offset-$num*4;
			 }
			$news=$this->getUserDao()->getSearchVal ( $num*5, $offset,$search_val );
			//print_r($news);
			$count=count($news);
			//echo "count:".$count."<br>";
			$this->redis->pipeline();
			for($i=0;$i<$count;$i++){
				$key="searchVal:".$search_val.":".(intval(($offset+$i)/$num)+1);
				$this->redis->rPush($key,$news[$i]["news_id"]);
				$this->redis->expire($key,self::$searchOutTime);
				if(!$this->redis->hExists("news:".$news[$i]["news_id"],"news_id")){
					$this->redis->hMset("news:".$news[$i]["news_id"],$news[$i]);
				}
				$this->redis->expire("news:".$news[$i]["news_id"],self::$newsOutTime);
			}
			$this->redis->exec();
			return array_slice($news,0,$num-1);
		}else{
			return $this->getNewsByIds($searchNewsIds);
		}
	}
	
	
}

?> 