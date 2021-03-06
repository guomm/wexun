<?php
class RedisModel extends AbstractModel{
	private $redis;
	
	function __construct($dao,$ip,$port) {
		parent::__construct($dao);
		$this->redis = new Redis ();
		$this->redis->connect ( redisIP, redisPort );
		
		//writeData("$newsContentOutTime $scanOutTime $userRecomm $tempOutTime $searchOutTime $newsOutTime $userStorageOutTime $userInfoOutTime");
	}
	
	function __destruct(){
		$this->redis->close();
	}
// 	function pingRedis(){
// 		if("+PONG"==$this->redis->ping()) return 1;
// 		return 0;
// 	}
	
	function getUserById($userId) {
		writeData("userID:".$userId);
		$result = $this->redis->hGetAll ( "user:" . $userId );
		if (! $result) {
			// redis不存在个人信息，从数据库中加载
			$result = $this->dao->getUserInfo ( $userId );
			$this->redis->hMset ( "user:" . $userId, $result );
			$this->redis->expire ( "user:" . $userId, userInfoOutTime );
		}
		return json_encode ( $result );
	}
	
	function updateUser($user, $userId) {
		$result = $this->dao->updateUser ( $user, $userId );
		$userChanges = array (
				"user_gender" => $user->gender,
				"user_name" => $user->name,
				"user_birthday" => $user->birthday,
				"user_label" => $user->interest 
		);
		if ($result) {
			$this->redis->hMset ( "user:" . $userId, $userChanges );
			$this->redis->expire ( "user:" . $userId, userInfoOutTime );
		}
		return $result;
	}
	
	function getStorageById($userId, $num, $offset) {
		$userStorageIds = $this->redis->zRange ( "ustore:" . $userId, $offset, $offset + $num - 1 );
		if ($userStorageIds) {
			writeData("userStorageIds:$userStorageIds");
			// echo "have userStorageIds";
			// 从redis中取出了收藏的新闻id，根据新闻Id获取新闻
			return $this->getNewsByIds ( $userStorageIds );
		} else {
			// echo "no userStorageIds" . $userId;
			// print_r($userStorageIds);
			// redis中不存在用户的收藏，从本地数据库中取出
			$userStorage = $this->dao->getStorageByUserId ( $userId,$num, $offset );
			// echo "dbustore:" . $userStorage . "<br>";
			if(strlen($userStorage)==4)return 0;
			else{
				$find1 = ";";
				$replace = ",";
				// 将取出的数据放入redis,数据库中的数据存储格式为 storagetime,newsId;storagetime,newsId
				// $userStorage
				//去掉开始的；
				$userStorage=substr($userStorage, 5);
				if(!$userStorage)return 0;
				$userStorage = str_replace ( $find1, $replace, $userStorage );
				// echo "dbustore:".$userStorage."<br>";
				$this->redis->pipeline ();
				$redisVal = "\$this->redis->zAdd('ustore:" . $userId . "'," . $userStorage . ");";
				// echo $redisVal;
				eval ( $redisVal );
				$this->redis->expire ( "ustore:" . $userId, userStorageOutTime );
				$this->redis->zRange ( "ustore:" . $userId, $offset, $offset + $num - 1 );
				$userStorageIds = $this->redis->exec () [2];
				// print_r ( $userStorageIds );
				return $this->getNewsByIds ( $userStorageIds );
			}
			return 0;
		}
	}

	function getNewsByIds($newsIds) {
		writeData("getNewsByIds:");
		writeData($newsIds);
		$unexistNewsIds = '';
		$this->redis->pipeline ();
		foreach ( $newsIds as $newsId ) {
			$this->redis->hGetAll ( "news:" . $newsId );
		}
		$newss = $this->redis->exec ();
		//writeData($newss);
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
			//writeData("unexistNewsIds:");
			//writeData($unexistNewsIds);
			$newssDb = $this->dao->getNewsByIds ( $unexistNewsIds );
			//writeData($newssDb);
			//writeData($newssDb);
			// print_r($newssDb);
			// echo "<br>";
			// 将从数据库取出的新闻信息放入redis
			$this->redis->pipeline ();
			foreach ( $newssDb as $tempNews ) {
				// echo $tempNews["news_id"]." a ";
				$this->redis->hMset ( "news:" . $tempNews ["news_id"], $tempNews );
				$this->redis->expire ( "news:" . $tempNews ["news_id"], newsOutTime );
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
			//writeData($newss);
		}
		return $newss;
	}
	
	function getStoragePageCount($userId) {
		$count = $this->redis->zCard ( "ustore:" . $userId );
		// echo " a ".$count;
		if (! $count) {
			// redis中不存在用户的收藏，从本地数据库中取出,后面两个参数无用
			$userStorage = $this->dao->getStorageByUserId ( $userId, 0, 0 );
			if(strlen($userStorage)==4)return 0;
			else{
				$find1 = ";";
				$replace = ",";
				//echo userStorageOutTime . " dd";
				//writeData($userStorage);
				//去掉开始的；
				$userStorage=substr($userStorage, 5);
				//if(!$userStorage)return 0;
				// 将取出的数据放入redis,数据库中的数据存储格式为 storagetime,newsId;storagetime,newsId
				$userStorage = str_replace ( $find1, $replace, $userStorage );
				
				//echo "dbustore:" . $userStorage . "<br>";
				$this->redis->pipeline ();
				$redisVal = "\$this->redis->zAdd('ustore:" . $userId . "'," . $userStorage . ");";
				// echo $redisVal;
				//writeData("     ".$redisVal);
				eval($redisVal);
				$this->redis->expire ( "ustore:" . $userId, $userStorageOutTime );
				$this->redis->zCard ( "ustore:" . $userId );
				$count = $this->redis->exec ()[2];
				//writeData($count);
				//$count=$count[2];
				// print_r($count);
				// $count=$count[2];
			}
		}
		return ceil ( $count / 10 );
	}
	
	function getSearchValPageCount($search_val) {
		$count = $this->redis->get ( "sc:" . $search_val );
		if (! $count) {
			$count = $this->dao->getSearchValCount ( $search_val );
			$this->redis->setex ( "sc:" . $search_val, searchOutTime, $count );
		}
		return $count;
	}
	
	function getSearchVal($search_val, $offset, $num, $pageCount) {
		// $this->redis->pipeline();
		$page = intval ( $offset / $num ) + 1;
		$searchNewsIds = $this->redis->lrange ( "sv:" . $search_val . ":" . $page, 0, $num - 1 );
		if (! $searchNewsIds) {
			//echo "d";
			// 该页对应的内容不在redis中，需要去数据库中取,一次取5页
			// 判断是从左边还是右边
			if ($page == $pageCount) {
				$offset = $offset - $num * 4;
			} else if ($this->redis->lindex ( "sv:" . $search_val . ":" . ($page + 1), 0 )) {
				$offset = $offset - $num * 4;
			}
			if($offset<0)$offset=0;
			$news = $this->dao->getSearchVal ( $num * 5, $offset, $search_val );
			// print_r($news);
			$count = count ( $news );
			// echo "count:".$count."<br>";
			writeData("offset:".$offset."count:".$count);
			$this->redis->pipeline ();
			for($i = 0; $i < $count; $i ++) {
				$key = "sv:" . $search_val . ":" . (intval ( ($offset + $i) / $num ) + 1);
				$this->redis->rPush ( $key, $news [$i] ["news_id"] );
				$this->redis->expire ( $key, searchOutTime );
				//if (! $this->redis->hExists ( "news:" . $news [$i] ["news_id"], "news_id" )) {
				$this->redis->hMset ( "news:" . $news [$i] ["news_id"], $news [$i] );
				//}
				$this->redis->expire ( "news:" . $news [$i] ["news_id"], newsOutTime );
			}
			$this->redis->exec ();
			if($count<=$num)return $news;
			return array_slice ( $news, 0, $num - 1 );
		} else {
			return $this->getNewsByIds ( $searchNewsIds );
		}
	}
	
	function getRecommendNews($num,$userId) {
		writeData("num:".$num." userID:".$userId);
		// 用户第一次加载主页
		if (!$userId) {
			//$current_ip = getIp ();
			$current_cookie = session_id();
			 //writeData( "current_cookie".$current_cookie);
			// 判断该cookie存不存在
			//$userIdFromIp = $this->dao->getUserIdByCookie ( $current_cookie );
			// writeData("userIdFromIp:".$userIdFromIp);
// 			if ($userIdFromIp) {
// 				//$_SESSION ["userId"] = string2secret ( $userIdFromIp );
// 				setcookie ( "userId", string2secret ( $userIdFromIp ),time()+cookieTime );
// 				$userId = $userIdFromIp;
// 				return $this->getRecommByUserId($userId,$num);
// 			} else {
				// ip对应的用户不存在，插入临时用户
			$userId = $this->dao->addUser ( $current_cookie );
			writeData("  ".$userId."   ");
				$_SESSION ["userId"] = $userId;
				$_SESSION ["login"] = 0;
				//setcookie("PHPSESSID",$current_cookie,time()+cookieTime);
				writeData($_SESSION ["userId"]);
				setcookie ( "userId", string2secret ( $userId ) ,time()+cookieTime);
				return $this->getNewsByLabel ( 0, $num, "hot",$userId);
			//}
			// writeData("userId:".$userId);
			// 从标签表中拉取数据,无登陆的用户对应的新闻标签类是100，名字为default
			
		} else {
			// 用户已经加载过主页
			return $this->getRecommByUserId($userId,$num);
// 			 writeData("hava load the index page:");
// 			if ($_SESSION ["userName"]) {
// 				// 用户已登录从推荐表中拉取数据
// 				writeData(" load from recomm ");
				
				
// 			} else {
// 				// 用户未登录从标签表中拉取数据，无登陆的用户对应的新闻标签类是100，名字为default
// 				writeData(" load from label ");
// 				return $this->getNewsByLabel ( 100, $num, "default",$userId );
// 			}
		}
	}
	
	function getRecommByUserId($userId,$num){
		writeData("recommbyuserID: $userId   $num");
		$markNewData = $this->redis->get ( "recu:" . $userId );
		// var_dump($markNewData);
		writeData("markNewData:".$markNewData);
		// 判断是否有新数据
		if ($markNewData) {
			//后两个参数无用
			$result = $this->dao->getRecommendNews ( $userId,0,0);
			writeData("have recommend data.");
			//writeData($result);
			//print_r($result);
			$this->redis->pipeline ();
			$recommkey = "urec:" . $userId;
			// 清空原数据
			$this->redis->del ( $recommkey );
			foreach ( $result as $newsId ) {
				$this->redis->rPush ( $recommkey, $newsId );
			}
			$this->redis->set("recu:" . $userId,0);
			$this->redis->expire($recommkey,userRecomm);
			$this->redis->exec ();
		}
		
		$diffNewsIds = array ();
		
		while ( ! count ( $diffNewsIds ) ) {
			$result = $this->redis->lrange ( "urec:" . $userId, 0, $num - 1 );
			writeData($result);
			if (! $result) {
				// 推荐表中无数据。。。从标签表中拉取
				//writeData ("nothing...");
				return $this->getNewsByLabel ( 0, $num, "hot",$userId);
			//	return 0;
			}
			// 将取出的数据转化为set与浏览表求交集，查看是否该新闻已显示
			$this->redis->pipeline ();
			//$this->redis->expire("urec:" . $userId,userRecomm);
			// 先清空临时表
			$this->redis->sDiffStore ( "recT", "empty", "recT" );
				
			foreach ( $result as $newsId ) {
				$this->redis->sAdd ( "recT", $newsId );
			}
			$this->redis->exec ();
			$diffNewsIds = $this->redis->sDiff ( "recT", "us:" . $userId );
			$this->redis->ltrim ( "urec:" . $userId, $num, - 1 );
		}
		//print_r($diffNewsIds);
		// 向浏览表内写数据
		$this->redis->pipeline ();
		// 将取出的新闻id放入浏览表中
		foreach ( $diffNewsIds as $newsId ) {
			$this->redis->sAdd ( "us:" . $userId, $newsId );
			$this->redis->sAdd ( "ns:" . $newsId, $userId );
		}
		//$this->redis->expire("scan:" . $userId,scanOutTime);
		$this->redis->exec ();
		
		return $this->getNewsByIds ( $diffNewsIds );
	}
	
	function getNewsByLabel($labelId, $num, $labelName,$userId) {
		$offset = 0;
		$loadCount = $num;
		if ($_SESSION ["$labelName"]) {
			$offset = $_SESSION ["$labelName"];
			$_SESSION ["$labelName"] = $_SESSION ["$labelName"] + $num;
		}
		//writeData($labelName);
// 		if(!$this->redis->exists($labelName . $userId.":u")){
			
// 		}
		$markNewData = $this->redis->get ( $labelName . $userId.":u" );
		// var_dump($markNewData);
		writeData("nologinmarkNewData:".$markNewData."  ");
			writeData($labelName . ":v");
		// 判断是否有新数据 或者是一个新申请的帐号
		if ($markNewData || !$this->redis->exists($labelName . $userId.":u")) {
			//writeData(" have new data..");
			// echo "have update data.<br>";
			//第二个参数无用
 			$result = $this->dao->getNewsByLabel ( $labelId, 0,$markNewData );
 			$this->redis->pipeline ();
 			foreach ( $result as $newsId ) {
 				$this->redis->lPush ( $labelName . ":v", $newsId ["news_id"] );
 			}
			$offset = 0;
			$this->redis->set ( $labelName .$userId. ":u", 0 );
			$this->redis->exec ();
		}
		
		writeData("offset:".$offset." loadCount:".$loadCount." \n");
		$result = $this->redis->lrange ( $labelName . ":v", $offset, $offset + $loadCount - 1 );
	//````````````````````````````````````````````````````````````````````````````````````````````````
	/*	$_SESSION ["$labelName"] = $offset+$loadCount - 1;
		if(!$result) return 0;
		$this->redis->pipeline ();
		foreach ( $result as $newsId ) {
				 $this->redis->sAdd ( "us:" . $userId, $newsId );
	            $this->redis->sAdd ( "ns:" . $newsId, $userId );
		}
		$this->redis->exec ();
        return $this->getNewsByIds ( $result );

	 */
		//``````````````````````````````````````````````````````````````````````````````````````````
		// print_r($result);
		// echo "<br>";
		//writeData(var);
		// 将取出的数据转化为set与浏览表求交集，查看是否该新闻已显示
		$this->redis->pipeline ();
		// 先清空临时表
		$this->redis->sDiffStore ( $labelName . ":T", "empty", $labelName . ":T" );
		
		foreach ( $result as $newsId ) {
			$this->redis->sAdd ( $labelName . ":T", $newsId );
		}
		$this->redis->exec ();
		$diffNewsIds = $this->redis->sDiff ( $labelName . ":T", "us:" . $userId );
		
		// echo "get newsIDs:";
		// print_r($diffNewsIds);
		//writeData("NewsIds:".(print_r($diffNewsIds))."  ".count($diffNewsIds)." ");
		$count = $num - count ( $diffNewsIds );
		while ( $count ) {
			// $this->redis->pipeline ();
			// echo "deal";
			$offset = $offset + $loadCount;
			// $count = $offset+$loadCount+$num - count ( $diffNewsIds );
			// echo "count:".$count." offset:".$offset."<br>";
			$result = $this->redis->lrange ( $labelName . ":v", $offset, $offset + $count - 1 );
			// print_r($result) ;
			
			// 如果redis中数据已加载完毕，跳出循环
			if (count ( $result ) < $count)
				break;
			$this->redis->pipeline ();
			foreach ( $result as $newsId ) {
				$this->redis->sAdd ( $labelName . ":T", $newsId );
			}
			$this->redis->exec ();
			$diffNewsIds = $this->redis->sDiff ( $labelName . ":T", "us:" . $userId );
			// echo "diffNewsIds:";
			// print_r($diffNewsIds) ;
			$loadCount = $count;
			$count = $num - count ( $diffNewsIds );
		}
		$_SESSION ["$labelName"] = $offset+$loadCount;
		if(!$diffNewsIds) return 0;
		$this->redis->pipeline ();
		// 将取出的新闻id放入浏览表中
		foreach ( $diffNewsIds as $newsId ) {
			$this->redis->sAdd ( "us:" . $userId, $newsId );
			$this->redis->sAdd ( "ns:" . $newsId, $userId );
		}
		
		//$this->redis->expire("scan:" . $userId,scanOutTime);
		$this->redis->exec ();
		// echo "offset:".$offset."<br>";
		// print_r($diffNewsIds);
		//writeData($diffNewsIds);
		return $this->getNewsByIds ( $diffNewsIds );
	}
	
	function getDetailNews($news_id,$userId){
		//writeData("news_id:".$news_id."  userId:".$userId);
		//获取新闻信息
 		$news=$this->redis->hGetAll("news:".$news_id);
 		if(!$news){
 			$news = $this->dao->getDetailNews ( $news_id );
 			$this->redis->hMset ( "news:" . $news ["news_id"], $news );
 			$this->redis->expire ( "news:" . $news ["news_id"], newsOutTime );
 		}
		//print_r($news);
		//$newsData=$this->redis->get("nd:".$news_id);
		
// 		if(!$newsData){
// 			$newsData=file_get_contents_utf8($news["news_data"]);
// 			$this->redis->set("nd:".$news_id,$newsData);
// 			$this->redis->expire ( "nd:" . $news ["news_id"], newsContentOutTime );
// 		}
// 		$news["news_data"]=$newsData;
		//echo "<br>";
		//print_r($news);
		
		//判断用户是否收藏，是否点赞
		//$news=array();
		if($_SESSION["login"]){
			if(!$this->redis->exists("ustore:" . $userId)){
				$userStorage = $this->dao->getStorageByUserId ( $userId,0, 0 );
				$redisVal='';
				if ($userStorage) {
					$find1 = ";";
					$replace = ",";
					$userStorage=substr($userStorage, 1);
					$userStorage = str_replace ( $find1, $replace, $userStorage );
					$redisVal = "\$this->redis->zAdd('ustore:" . $userId . "'," . $userStorage . ");";
				}
				
				$userLikeNews=$this->dao->getUserLikeNews ( $userId,0,0);
				if($userLikeNews){
					$userLikeNews=substr($userLikeNews, 1);
					if(strlen($redisVal)){
						$redisVal =$redisVal. ";\$this->redis->sAdd('userr:" . $userId . "'," . $userLikeNews . ");";
					}else{
						$redisVal ="\$this->redis->sAdd('userr:" . $userId . "'," . $userLikeNews . ");";
					}
					
				}
				$this->redis->pipeline ();
				eval ( $redisVal );
				$this->redis->expire ( "ustore:" . $userId, userStorageOutTime );
				$this->redis->expire ( "userr:" . $userId, userStorageOutTime );
				$this->redis->exec();
			}
			if($this->redis->zRank("ustore:" . $userId,$news_id)==''){
				$news["isStorage"]=0;
			}else{
				$news["isStorage"]=1;
			}
			$news["isRecomm"]=$this->redis->sismember("userr:" . $userId,$news_id);
			
		}else{
			$news["isStorage"]=0;
			$news["isRecomm"]=0;
		}
		
		//写入点击事件
		$this->redis->pipeline();
		$this->redis->sAdd("uc:".$userId,$news_id);
		//$this->redis->expire("uc:".$userId,userRecomm);
		$this->redis->sAdd("nc:".$news_id,$userId);
		//$this->redis->expire("nc:".$news_id,newsOutTime);
		$this->redis->exec();
		
		return $news;
	} 

	function recommendNews($news_id, $userId) {
		// 写入推荐事件
		$result = $this->dao->recommendNews ( $news_id, $userId );
		$this->redis->sAdd("userr:" . $userId,$news_id);
		// 更改新闻推荐个数
		//$this->dao->addOneRecommendNum ( $news_id, 1 );
		return $result;
	}
	function storageNews($news_id, $userId) {
		// 写入收藏事件
		writeData("newsId:".$news_id);
		writeData("userId:".$userId);
		$this->redis->zAdd("ustore:" . $userId,time(),$news_id);
		return $this->dao->storageNews ( $news_id, $userId );
	}
	function removeStorage($news_id,$userId) {
		$this->redis->zRem("ustore:" . $userId,$news_id);
		return $this->dao->removeStorage ( $news_id, $userId );
	}
	function removeRecomm($news_id, $userId) {
		$result = $this->dao->removeRecomm ( $news_id, $userId );
		$this->redis->srem("userr:" . $userId,$news_id);
		// 更改新闻推荐个数
		//$this->dao->addOneRecommendNum ( $news_id, - 1 );
		return $result;
	}
}

?> 
