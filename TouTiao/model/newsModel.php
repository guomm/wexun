<?php
require_once ("dao/newsDao.php");
class NewsModel {
	private $newsDao;
	function __construct() {
		$this->newsDao = new NewsDao();
	}
	
	function getRecommendNews($userId,$num){
		$result=$this->newsDao->getRecommendNews($userId, $num);
		//向浏览表内写数据
		$this->writeScanRecord($userId,$result,0);
		return json_encode($result);
	}
	
	function writeScanRecord($userId,$result,$newSClickLabel){
	
		$newsIds=array();
		foreach ($result as $temp){
			$newsIds[]=$temp["news_id"];
		}
		$this->newsDao->writeScanRecord($userId, $newsIds,$newSClickLabel);
	}
	
	function getNewsByLabel($userId,$labelId,$num,$labelName){
		$offset=0;
		if($_SESSION["$labelName"]){
			$offset=$_SESSION["$labelName"];
			$_SESSION["$labelName"]=$_SESSION["$labelName"]+$num;
		}else{
			$_SESSION["$labelName"]=$num;
		}
		$result=$this->newsDao->getNewsByLabel($labelId,$offset,$num);
		//向浏览表内写数据
		$this->writeScanRecord($userId,$result,$labelId);
		return json_encode($result);
	}
	
	function getDetailNews($news_id,$userId){
		//writeData("news_id:".$news_id."  userId:".$userId);
		//获取新闻信息
		$result=$this->newsDao->getDetailNews($news_id);
		//writeData($result["news_data"]."<br>");
		//从文件中读新闻数据
		$result["news_data"]=file_get_contents_utf8($result["news_data"]);
		//writeData($result["news_data"]."<br>");
		//写入点击事件
		$this->newsDao->updateScan($news_id,$userId,1);
		//writeData(json_encode($result));
		return json_encode($result);
	}
	
	function recommendNews($news_id,$userId){
		//写入推荐事件
		$result=$this->newsDao->updateScan($news_id,$userId,2);
		//更改新闻推荐个数
		$this->newsDao->addOneRecommendNum($news_id);
		return $result;
	}
	
	function storageNews($news_id,$userId){
		//写入收藏事件
		$this->newsDao->updateScan($news_id,$userId,3);
		//写入收藏表
		$result=$this->newsDao->insertbehavior(array($news_id), $userId,2);
		return $result;
	}
	
	function removeStorageNews($news_id,$userId){
		//写入收藏表
		$result=$this->newsDao->removeStorageNews(array($news_id), $userId);
		return $result;
	}
	
	function shareNews($news_id,$userId){
		//写入收藏事件
		return $this->newsDao->updateScan($news_id,$userId,4);
		
	}
	
	function reportNews($news_id,$userId,$describe){
		return $this->newsDao->reportNews($news_id,$userId,$describe);
	}
	function store($news_ids){
		//写入收藏表
		$result=$this->newsDao->insertbehavior($news_ids, 1,2);
	}
	
	function remove($news_ids){
		//删除收藏表
		$result=$this->newsDao->removeStorageNews($news_ids, 1);
	}
}
function file_get_contents_utf8($fn) {
	$content = file_get_contents($fn);
	return mb_convert_encoding($content, 'UTF-8',
			mb_detect_encoding($content,  array("ASCII","UTF-8","GB2312","GBK","BIG5")));
}
?>