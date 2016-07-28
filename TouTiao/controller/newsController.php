<?php
require_once ("model/newsModel.php");
class NewsController {
	private $newsModel;
	private $userId;
	function __construct() {
		$this->newsModel = new NewsModel ();
		$this->userId = secret2string ( $_SESSION ["userId"] );
	}
	function getRecommendNews($num) {
		// writeData("userId:".$userId);
		// 当前无登陆
		if (! $this->userId) {
			$current_ip=getIp();
			echo $current_ip;
			//判断该ip存不存在
			
		}
		echo $this->newsModel->getRecommendNews ( $this->userId, $num );
	}
	function getNewsByLabel($labelId, $num, $labelName) {
		echo $this->newsModel->getNewsByLabel ( $this->userId, $labelId, $num, $labelName );
	}
	function getDetailNews($news_id) {
		echo $this->newsModel->getDetailNews ( $news_id, $this->userId );
	}
	function recommendNews($news_id) {
		echo $this->newsModel->recommendNews ( $news_id, $this->userId );
	}
	function storageNews($news_id) {
		echo $this->newsModel->storageNews ( $news_id, $this->userId );
	}
	function shareNews($news_id) {
		echo $this->newsModel->shareNews ( $news_id, $this->userId );
	}
	function removeStorageNews($news_id) {
		echo $this->newsModel->removeStorageNews ( $news_id, $this->userId );
	}
	function reportNews($news_id, $describe) {
		echo $this->newsModel->reportNews ( $news_id, $this->userId, $describe );
	}
	function store($news_ids) {
		// 写入收藏表
		$result = $this->newsModel->store ( $news_ids, 1, 2 );
	}
	function remove($news_ids) {
		// 删除收藏表
		$result = $this->newsModel->remove ( $news_ids, 1 );
	}
}

function getIp() {
	if (getenv ( "HTTP_CLIENT_IP" ) && strcasecmp ( getenv ( "HTTP_CLIENT_IP" ), "unknown" ))
		$ip = getenv ( "HTTP_CLIENT_IP" );
	else if (getenv ( "HTTP_X_FORWARDED_FOR" ) && strcasecmp ( getenv ( "HTTP_X_FORWARDED_FOR" ), "unknown" ))
		$ip = getenv ( "HTTP_X_FORWARDED_FOR" );
	else if (getenv ( "REMOTE_ADDR" ) && strcasecmp ( getenv ( "REMOTE_ADDR" ), "unknown" ))
		$ip = getenv ( "REMOTE_ADDR" );
	else if (isset ( $_SERVER ['REMOTE_ADDR'] ) && $_SERVER ['REMOTE_ADDR'] && strcasecmp ( $_SERVER ['REMOTE_ADDR'], "unknown" ))
		$ip = $_SERVER ['REMOTE_ADDR'];
	else
		$ip = "unknown";
	return ($ip);
}

?>