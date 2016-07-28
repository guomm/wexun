<?php
require_once ("dao/newsDao.php");
class NewsModel {
	private $newsDao;
	private $userId;
	function __construct() {
		$this->newsDao = new NewsDao();
		if($_SESSION ["userId"])$this->userId = secret2string ( $_SESSION ["userId"] );
	}
	
	function getRecommendNews($num){
		
		// 用户第一次加载主页
		if (! $this->userId) {
			$current_ip=getIp();
			//writeData( "current_ip".$current_ip);
			//判断该ip存不存在
			$userIdFromIp=$this->newsDao->getUserIdByIp($current_ip);
			//writeData("userIdFromIp:".$userIdFromIp);
			if($userIdFromIp){
				$_SESSION ["userId"] = string2secret($userIdFromIp);
				setcookie ( "userId", string2secret($userIdFromIp) );
				$this->userId=$userIdFromIp;
			}else{
				//ip对应的用户不存在，插入临时用户
				$this->userId=$this->newsDao->addUser($current_ip);
			}
			//writeData("userId:".$this->userId);
			//从标签表中拉取数据,无登陆的用户对应的新闻标签类是100，名字为default
			$this->getNewsByLabel(100,$num,"default");
			
		}else{
			//用户已经加载过主页
			//writeData("userId:".$this->userId);
			if($_SESSION ["userName"]){
				//用户已登录从推荐表中拉取数据
				$result=$this->newsDao->getRecommendNews($this->userId, $num);
				//向浏览表内写数据
				if($result){
					$this->writeScanRecord($this->userId,$result,0);
					echo json_encode($result);
				}else{
					echo 0;
				}
			}else{
				//用户未登录从标签表中拉取数据，无登陆的用户对应的新闻标签类是100，名字为default
				$this->getNewsByLabel(100,$num,"default");
			}
			
		}
		//echo "userId:".$this->userId."<br>";
		
		$this->newsDao->closeConn();
	}
	
	function writeScanRecord($userId,$result,$newSClickLabel){
	
		$newsIds=array();
		foreach ($result as $temp){
			$newsIds[]=$temp["news_id"];
		}
		$this->newsDao->writeScanRecord($userId, $newsIds,$newSClickLabel);
	}
	
	function getNewsByLabel($labelId,$num,$labelName){
		$offset=0;
		if($_SESSION["$labelName"]){
			$offset=$_SESSION["$labelName"];
			$_SESSION["$labelName"]=$_SESSION["$labelName"]+$num;
		}else{
			$_SESSION["$labelName"]=$num;
		}
		$result=$this->newsDao->getNewsByLabel($labelId,$offset,$num);
		//向浏览表内写数据
		//writeData("result:".$result);
		//writeData("userID:".$this->userId);
		$this->writeScanRecord($this->userId,$result,$labelId);
		echo json_encode($result);
		
		$this->newsDao->closeConn();
	}
	
	function getDetailNews($news_id){
		//writeData("news_id:".$news_id."  userId:".$userId);
		//获取新闻信息
		$result=$this->newsDao->getDetailNews($news_id);
		//writeData($result["news_data"]."<br>");
		//从文件中读新闻数据
		$result["news_data"]=file_get_contents_utf8($result["news_data"]);
		//writeData($result["news_data"]."<br>");
		//写入点击事件
		$this->newsDao->updateScan($news_id,$this->userId,1);
		//writeData(json_encode($result));
		echo json_encode($result);
		
		$this->newsDao->closeConn();
	}
	
	function recommendNews($news_id){
		//写入推荐事件
		$result=$this->newsDao->updateScan($news_id,$this->userId,2);
		//更改新闻推荐个数
		$this->newsDao->addOneRecommendNum($news_id);
		echo $result;
		
		$this->newsDao->closeConn();
	}
	
	function storageNews($news_id){
		//写入收藏事件
		$this->newsDao->updateScan($news_id,$this->userId,3);
		//写入收藏表
		echo $this->newsDao->insertbehavior(array($news_id), $this->userId,2);
		
		$this->newsDao->closeConn();
	}
	
	function removeStorageNews($news_id){
		//写入收藏表
		echo $this->newsDao->removeStorageNews(array($news_id), $this->userId);
		
		$this->newsDao->closeConn();
	}

	
	function reportNews($news_id,$describe){
		echo $this->newsDao->reportNews($news_id,$this->userId,$describe);
		
		$this->newsDao->closeConn();
	}
	function store($news_ids){
		//写入收藏表
		echo $this->newsDao->insertbehavior($news_ids, $this->userId,2);
	}
	
	function remove($news_ids){
		//删除收藏表
		echo $this->newsDao->removeStorageNews($news_ids, $this->userId);
	}
}
?>