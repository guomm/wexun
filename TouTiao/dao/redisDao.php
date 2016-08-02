<?php
require 'userDao.php';
require 'newsDao.php';
class RedisDao {
	private $redis;
	private $userDao;
	private $newsDao;
	private $userId;
	static $userInfoOutTime = 600;
	static $userStorageOutTime = 600;
	static $newsOutTime = 7200;
	static $searchOutTime = 7200;
	static $tempOutTime = 3;
	static $userRecomm=1200;
	static $scanOutTime=1200;
	static $newsContentOutTime=7200;
	function __construct() {
		$this->redis = new Redis ();
		$this->redis->connect ( '127.0.0.1', 6379 );
		if ($_SESSION ["userId"])
			$this->userId = secret2string ( $_SESSION ["userId"] );
	}
	function getUserDao() {
		if (! $this->userDao) {
			$this->userDao = new UserDao ();
		}
		return $this->userDao;
	}
	function getNewsDao() {
		if (! $this->newsDao) {
			$this->newsDao = new NewsDao ();
		}
		return $this->newsDao;
	}
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
			$newss = array_slice ( $this->redis->exec (), count ( $newssDb ) * 2 );
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
		$count = $this->redis->get ( "searchCount:" . $search_val );
		if (! $count) {
			$count = $this->getUserDao ()->getSearchValCount ( $search_val );
			$this->redis->setex ( "searchCount:" . $search_val, self::$searchOutTime, $count );
		}
		return $count;
	}
	function getSearchVal($search_val, $offset, $num, $pageCount) {
		// $this->redis->pipeline();
		$page = intval ( $offset / $num ) + 1;
		$searchNewsIds = $this->redis->lrange ( "searchVal:" . $search_val . ":" . $page, 0, $num - 1 );
		if (! $searchNewsIds) {
			echo "d";
			// 该页对应的内容不在redis中，需要去数据库中取,一次取5页
			// 判断是从左边还是右边
			if ($page == $pageCount) {
				$offset = $offset - $num * 4;
			} else if ($this->redis->lindex ( "searchVal:" . $search_val . ":" . ($page + 1), 0 )) {
				$offset = $offset - $num * 4;
			}
			$news = $this->getUserDao ()->getSearchVal ( $num * 5, $offset, $search_val );
			// print_r($news);
			$count = count ( $news );
			// echo "count:".$count."<br>";
			$this->redis->pipeline ();
			for($i = 0; $i < $count; $i ++) {
				$key = "searchVal:" . $search_val . ":" . (intval ( ($offset + $i) / $num ) + 1);
				$this->redis->rPush ( $key, $news [$i] ["news_id"] );
				$this->redis->expire ( $key, self::$searchOutTime );
				if (! $this->redis->hExists ( "news:" . $news [$i] ["news_id"], "news_id" )) {
					$this->redis->hMset ( "news:" . $news [$i] ["news_id"], $news [$i] );
				}
				$this->redis->expire ( "news:" . $news [$i] ["news_id"], self::$newsOutTime );
			}
			$this->redis->exec ();
			return array_slice ( $news, 0, $num - 1 );
		} else {
			return $this->getNewsByIds ( $searchNewsIds );
		}
	}
	
	function getRecommendNews($num) {
		
		// 用户第一次加载主页
		if (! $this->userId) {
			$current_ip = getIp ();
			// writeData( "current_ip".$current_ip);
			// 判断该ip存不存在
			$userIdFromIp = $this->getNewsDao()->getUserIdByIp ( $current_ip );
			// writeData("userIdFromIp:".$userIdFromIp);
			if ($userIdFromIp) {
				$_SESSION ["userId"] = string2secret ( $userIdFromIp );
				setcookie ( "userId", string2secret ( $userIdFromIp ) );
				$this->userId = $userIdFromIp;
			} else {
				// ip对应的用户不存在，插入临时用户
				$this->userId = $this->getNewsDao()->addUser ( $current_ip );
			}
			// writeData("userId:".$this->userId);
			// 从标签表中拉取数据,无登陆的用户对应的新闻标签类是100，名字为default
			return $this->getNewsByLabel ( 100, $num, "default");
		} else {
			// 用户已经加载过主页
			// writeData("userId:".$this->userId);
			if (true||$_SESSION ["userName"]) {
				// 用户已登录从推荐表中拉取数据
				
				$markNewData = $this->redis->get ( "recomUpdate:" . $this->userId );
				// var_dump($markNewData);
				// 判断是否有新数据
				if ($markNewData) {
					$result = $this->getNewsDao()->getRecommendNews ( $this->userId);
					//print_r($result);
					$this->redis->pipeline ();
					$recommkey = "userRecomm:" . $this->userId;
					// 清空原数据
					$this->redis->del ( $recommkey );
					foreach ( $result as $newsId ) {
						$this->redis->rPush ( $recommkey, $newsId );
					}
					$this->redis->set("recomUpdate:" . $this->userId,0);
					$this->redis->expire($recommkey,self::$userRecomm);
					$this->redis->exec ();
				}
				
				$diffNewsIds = array ();
				
				while ( ! count ( $diffNewsIds ) ) {
					$result = $this->redis->lrange ( "userRecomm:" . $this->userId, 0, $num - 1 );
					if (! $result) {
						// 推荐表中无数据。。。
						//echo "nothing...";
						return 0;
					}
					// 将取出的数据转化为set与浏览表求交集，查看是否该新闻已显示
					$this->redis->pipeline ();
					$this->redis->expire("userRecomm:" . $this->userId,self::$userRecomm);
					// 先清空临时表
					$this->redis->sDiffStore ( "recommTemp", "empty", "recommTemp" );
					
					foreach ( $result as $newsId ) {
						$this->redis->sAdd ( "recommTemp", $newsId );
					}
					$this->redis->exec ();
					$diffNewsIds = $this->redis->sDiff ( "recommTemp", "scan:" . $this->userId );
					$this->redis->ltrim ( "userRecomm:" . $this->userId, $num, - 1 );
				}
				//print_r($diffNewsIds);
				// 向浏览表内写数据
				$this->redis->pipeline ();
				// 将取出的新闻id放入浏览表中
				foreach ( $diffNewsIds as $newsId ) {
					$this->redis->sAdd ( "scan:" . $this->userId, $newsId );
				}
				$this->redis->expire("scan:" . $this->userId,self::$scanOutTime);
				$this->redis->exec ();
				
				return $this->getNewsByIds ( $diffNewsIds );
			} else {
				// 用户未登录从标签表中拉取数据，无登陆的用户对应的新闻标签类是100，名字为default
				return $this->getNewsByLabel ( 100, $num, "default" );
			}
		}
	}
	
	function getNewsByLabel($labelId, $num, $labelName) {
		$offset = 0;
		$loadCount = $num;
		if ($_SESSION ["$labelName"]) {
			$offset = $_SESSION ["$labelName"];
			$_SESSION ["$labelName"] = $_SESSION ["$labelName"] + $num;
		}
		
		$markNewData = $this->redis->get ( $labelName . ":update" );
		// var_dump($markNewData);
		
		// 判断是否有新数据
		if ($markNewData) {
			// echo "have update data.<br>";
			$result = $this->getNewsDao ()->getNewsByLabel ( $labelId, $markNewData );
			$this->redis->pipeline ();
			foreach ( $result as $newsId ) {
				$this->redis->lPush ( $labelName . ":val", $newsId ["news_id"] );
			}
			$offset = 0;
			$this->redis->set ( $labelName . ":update", 0 );
			$this->redis->exec ();
		}
		$result = $this->redis->lrange ( $labelName . ":val", $offset, $offset + $loadCount - 1 );
		// print_r($result);
		// echo "<br>";
		// 将取出的数据转化为set与浏览表求交集，查看是否该新闻已显示
		$this->redis->pipeline ();
		// 先清空临时表
		$this->redis->sDiffStore ( $labelName . ":temp", "empty", $labelName . ":temp" );
		
		foreach ( $result as $newsId ) {
			$this->redis->sAdd ( $labelName . ":temp", $newsId );
		}
		$this->redis->exec ();
		$diffNewsIds = $this->redis->sDiff ( $labelName . ":temp", "scan:" . $this->userId );
		
		// echo "get newsIDs:";
		// print_r($diffNewsIds);
		$count = $num - count ( $diffNewsIds );
		while ( $count ) {
			// $this->redis->pipeline ();
			// echo "deal";
			$offset = $offset + $loadCount;
			// $count = $offset+$loadCount+$num - count ( $diffNewsIds );
			// echo "count:".$count." offset:".$offset."<br>";
			$result = $this->redis->lrange ( $labelName . ":val", $offset, $offset + $count - 1 );
			// print_r($result) ;
			
			// 如果redis中数据已加载完毕，跳出循环
			if (count ( $result ) < $count)
				break;
			$this->redis->pipeline ();
			foreach ( $result as $newsId ) {
				$this->redis->sAdd ( $labelName . ":temp", $newsId );
			}
			$this->redis->exec ();
			$diffNewsIds = $this->redis->sDiff ( $labelName . ":temp", "scan:" . $this->userId );
			// echo "diffNewsIds:";
			// print_r($diffNewsIds) ;
			$loadCount = $count;
			$count = $num - count ( $diffNewsIds );
		}
		$_SESSION ["$labelName"] = $offset;
		$this->redis->pipeline ();
		// 将取出的新闻id放入浏览表中
		foreach ( $diffNewsIds as $newsId ) {
			$this->redis->sAdd ( "scan:" . $this->userId, $newsId );
		}
		$this->redis->expire("scan:" . $this->userId,self::$scanOutTime);
		$this->redis->exec ();
		// echo "offset:".$offset."<br>";
		// print_r($diffNewsIds);
		return $this->getNewsByIds ( $diffNewsIds );
	}
	
	function getDetailNews($news_id){
		//writeData("news_id:".$news_id."  userId:".$userId);
		//获取新闻信息
		$news=$this->redis->hGetAll("news:".$news_id);
		if(!$news){
			$news = $this->getNewsDao ()->getNewsByIds ( $news_id )[0];
			$this->redis->hMset ( "news:" . $news ["news_id"], $news );
			$this->redis->expire ( "news:" . $news ["news_id"], self::$newsOutTime );
		}
		//print_r($news);
		$newsData=$this->redis->get("newsData:".$news_id);
		
		if(!$newsData){
			$newsData=file_get_contents_utf8($news["news_data"]);
			$this->redis->set("newsData:".$news_id,$newsData);
			$this->redis->expire ( "news:" . $news ["news_id"], self::$newsContentOutTime );
		}
		$news["news_data"]=$newsData;
		//echo "<br>";
		//print_r($news);
		//写入点击事件
		$this->redis->pipeline();
		$this->redis->sAdd("userClick:".$this->userId,$news_id);
		$this->redis->expire("userClick:".$this->userId,self::$userRecomm);
		$this->redis->sAdd("newsClick:".$news_id,$this->userId);
		$this->redis->expire("newsClick:".$news_id,self::$newsOutTime);
		$this->redis->exec();
		
		return $news;
	} 
	
	function recommendNews($news_id,$userId){
		//写入推荐事件
		$result=$this->getNewsDao()->recommendNews($news_id,$userId);
		//更改新闻推荐个数
		$this->getNewsDao()->addOneRecommendNum($news_id,1);
	
	}
	
	function storageNews($news_id,$userId){
		//写入收藏事件
		$this->getNewsDao()->storageNews($news_id,$userId);
	
	}
	
	function removeStorage($news_id) {
		$this->getNewsDao()->removeStorage($news_id,$userId);
	}
	
	function removeRecomm($news_id,$userId){
		$this->getNewsDao()->removeRecomm($news_id,$userId);
		//更改新闻推荐个数
		$this->getNewsDao()->addOneRecommendNum($news_id,-1);
	}
	
	function reportNews($news_id,$describe,$userId){
		echo $this->getNewsDao()->reportNews($news_id,$userId,$describe);
	}
// 	function writeScan($newsIds,$userId,$type){
// 		$this->redis->pipeline ();
// 		// 将取出的新闻id放入浏览表中
// 		foreach ( $newsIds as $newsId ) {
// 			$this->redis->sAdd ( "scan:" . $userId, $newsId );
// 		}
// 		$this->redis->expire("scan:" . $userId,self::$scanOutTime);
// 		$this->redis->exec ();
// 	}
}

?> 