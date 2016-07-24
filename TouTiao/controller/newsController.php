<?php
require_once ("model/newsModel.php");
class NewsController {
	private $newsModel;
	function __construct() {
		$this->newsModel = new NewsModel ();
	}
	function getRecommendNews($num) {
		$userId=$_SESSION["userId"];
		echo $this->newsModel->getRecommendNews ( secret2string($userId), $num);
	
	}
	function getNewsByLabel($labelId,$num,$labelName){
		$userId=$_SESSION["userId"];
		echo $this->newsModel->getNewsByLabel ( secret2string($userId),$labelId, $num,$labelName);
	}
	
}

?>