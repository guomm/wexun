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
}

?>