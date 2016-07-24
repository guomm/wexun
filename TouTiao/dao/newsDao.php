<?php
require_once ("dao/commonDao.php");
class NewsDao {
	private $conn;
	function __construct() {
		if (! CommonDao::$conn) {
			CommonDao::createConn ();
		}
		$this->conn = CommonDao::$conn;
		if (! $this->conn) {
			echo "Could not connect: " . CommonDao::$conn->connect_error;
		}
	}
// 	function getRecommendNews($userId, $num) {
// 		// $sql="select news.agency_name,news.news_title,news.news_time,news.news_imgs,news.news_img_num,news.news_abstract from newsrecommend,news where news.news_id=newsrecommend.news_id and newsrecommend.user_id=".$userId." limit ".$num;
// 		// writeData($sql."\n");
// 		$sql = "select news_id from newsrecommend where user_id= '" . $userId . "'";
// 	//	echo $sql;
// 		$res = $this->conn->query ( $sql );
// 		$dd=$res->fetch_all (MYSQLI_NUM );
// 		$newsIds;
// 		foreach($dd as $d){
// 			$newsIds=$newsIds.$d[0].",";
// 		}
// 		$newsIds=substr($newsIds,0,-1);
// 		//echo "newsIds:".$newsIds;
// 		$sql = "delete from newsrecommend where user_id in  (" . $newsIds . ")";
// 		$delete_result = $this->conn->query ( $sql );
// 		$sql = "select agency_name,news_title,news_time,news_imgs,news_img_num,news_abstract from news where news_id in (" . $newsIds . ")";
// 		$newsS = $this->conn->query ( $sql );
// 		if ($newsS)
// 			return json_encode ( $newsS->fetch_all ( MYSQLI_ASSOC ) );
// 		return 0;
// 	}
// 	function addRecommendNewsdd($userId, $newsIdList) {
// 		$sql = "insert into newsrecommend(user_id,news_id) values('" . $userId . "','" . $newsIdList . "')";
// 		echo '<br>' . $sql;
// 		if(!$this->conn)echo "conn is empty.";
// 		$res = $this->conn->query ( $sql );
// 		if ($res)
// 			return 1;
// 		return 0;
// 	}
	function addRecommendNews($userId, $newsIdList) {
		$sql = "insert into newsrecommend(user_id,news_id) values('" . $userId . "','" . $newsIdList . "')";
		$res = $this->conn->query ( $sql );
		if ($res)
			return 1;
		return 0;
	}
	function getRecommendNews($userId, $num) {
		$sql = "select news_id from newsrecommend where user_id= '" . $userId . "'";
		$res = $this->conn->query ( $sql );
		$newsIds=$res->fetch_assoc()["news_id"];
		$pos=str_n_pos($newsIds,$num);
		$remain='';
		if($pos){
			$remain=substr($newsIds,$pos+1);
			$newsIds=substr($newsIds,0,$pos);
		}
		$sql = "update newsrecommend  set news_id = '".$remain."' where user_id =  '" . $userId . "'";
		$delete_result = $this->conn->query ( $sql );
		
		$sql = "select news_id,agency_name,news_title,news_time,news_imgs,news_img_num,news_abstract from news where news_id in (" . $newsIds . ")";
		$newsS = $this->conn->query ( $sql );
		if ($newsS)
			return  $newsS->fetch_all ( MYSQLI_ASSOC ) ;
		return 0;
	}
	
	function writeScanRecord($userId, $newsIds,$clickLabel){
		$sql="insert into userscans(user_id,news_id,scan_time,news_click_label) values";
		foreach($newsIds as $newsId){
			$sql=$sql."('".$userId."','".$newsId."','".date("Y-m-d H:i:s")."','".$clickLabel."'),";
		}
		$sql=substr($sql, 0,-1);
		$res = $this->conn->query ( $sql );
		if ($res)return 1;
		return 0;
	}
	
	function getNewsByLabel($labelId,$offset,$num) {
		$sql = "select a.news_id,a.agency_name,a.news_title,a.news_time,a.news_imgs,a.news_img_num,a.news_abstract from news as a INNER JOIN ".
				"(select news_id from newslabel  where label_id='".$labelId."' ORDER BY add_news_time limit ".$offset.",".$num.") as b on a.news_id =b.news_id ";
		// writeData($sql);
		$res = $this->conn->query ( $sql );
		if ($res)
			return  $res->fetch_all ( MYSQLI_ASSOC ) ;
		return 0;
	}
	
	function addNewsLabel($label_id,$news_id,$time){
		$sql="insert into newslabel(news_id,label_id,add_news_time) values('".$news_id."','".$label_id."','".$time."')";
		$res = $this->conn->query ( $sql );
		if ($res->num_rows)
			return 1;
			return 0;
	}
}

 function str_n_pos($str,$n){
 	$length=strlen($str);
 	$j=0;
	for ($i=0;$i<=$length;$i++){
		if($str{$i}==',')$j++;
		if($j==$n)return $i;
	}
	return 0;
}
?>