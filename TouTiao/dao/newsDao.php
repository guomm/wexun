<?php
require_once ("dao/commonDao.php");
class NewsDao {
	private $conn;
	function __construct() {
		$this->conn = createConnection();
		if (! $this->conn) {
			echo "Could not connect: " . CommonDao::$conn->connect_error;
		}
	}
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
		$newsIds = $res->fetch_assoc () ["news_id"];
		$pos = str_n_pos ( $newsIds, $num );
		$remain = '';
		if ($pos) {
			$remain = substr ( $newsIds, $pos + 1 );
			$newsIds = substr ( $newsIds, 0, $pos );
		}
		$sql = "update newsrecommend  set news_id = '" . $remain . "' where user_id =  '" . $userId . "'";
		$delete_result = $this->conn->query ( $sql );
		
		$sql = "select news_id,agency_name,news_title,news_time,news_imgs,news_img_num,news_abstract from news where news_id in (" . $newsIds . ")";
		$newsS = $this->conn->query ( $sql );
		if ($newsS)
			return $newsS->fetch_all ( MYSQLI_ASSOC );
		return 0;
	}
	function writeScanRecord($userId, $newsIds, $clickLabel) {
		//writeData("newsIds".$newsIds."  userId:".$userId);
		$sql = "insert into userscans(user_id,news_id,scan_time,news_click_label) values";
		foreach ( $newsIds as $newsId ) {
			$sql = $sql . "('" . $userId . "','" . $newsId . "','" . date ( "Y-m-d H:i:s" ) . "','" . $clickLabel . "'),";
		}
		$sql = substr ( $sql, 0, - 1 );
		$res = $this->conn->query ( $sql );
		if ($res)
			return 1;
		return 0;
	}
	function getNewsByLabel($labelId, $offset, $num) {
		$sql = "select a.news_id,a.agency_name,a.news_title,a.news_time,a.news_imgs,a.news_img_num,a.news_abstract from news as a INNER JOIN " . "(select news_id from newslabel  where label_id='" . $labelId . "' ORDER BY add_news_time limit " . $offset . "," . $num . ") as b on a.news_id =b.news_id ";
		// writeData($sql);
		$res = $this->conn->query ( $sql );
		if ($res)
			return $res->fetch_all ( MYSQLI_ASSOC );
		return 0;
	}
	function addNewsLabel($label_id, $news_id, $time) {
		$sql = "insert into newslabel(news_id,label_id,add_news_time) values('" . $news_id . "','" . $label_id . "','" . $time . "')";
		$res = $this->conn->query ( $sql );
		if ($res&&$res->num_rows)
			return 1;
		return 0;
	}
	function getDetailNews($news_id) {
		$sql = "select news.agency_name,news.news_time,news.news_data,news.news_title,newsrecord.news_recommend_num from news,newsrecord where news.news_id = newsrecord.news_id and news.news_id='" . $news_id . "'";
		$res = $this->conn->query ( $sql );
		if ($res)
			return $res->fetch_assoc ();
		return 0;
	}
	function updateScan($news_id, $user_id, $fieldType) {
		$fieldName = '';
		if ($fieldType == 1) {
			$fieldName = "is_click";
		} else if ($fieldType == 2) {
			$fieldName = "is_recommend";
		} else if ($fieldType == 3) {
			$fieldName = "is_storage";
		} else if ($fieldType == 4) {
			$fieldName = "is_share";
		}
		$sql = "update userscans set " . $fieldName . " =1 where user_id='" . $user_id . "' and news_id='" . $news_id . "' order by scan_time desc limit 1";
		$res = $this->conn->query ( $sql );
		if ($res)
			return 1;
		return 0;
	}
	function insertbehavior($news_ids, $user_id, $behavior_type) {
		$fieldName = '';
		if ($behavior_type == 1) {
			$fieldName = "user_recommend_news_id";
		} else if ($behavior_type == 2) {
			$fieldName = "user_storage_news_id";
		} else if ($behavior_type == 3) {
			$fieldName = "user_share_news_id";
		}
		$sql = "select " . $fieldName . " from userbehavior where user_id='" . $user_id . "'";
		$res = $this->conn->query ( $sql );
		$origin_data = $res->fetch_assoc () [$fieldName];
		// writeData( "origin_data:" . $origin_data . "<br>");
		// 求并集
		$str_result = '';
		if ($origin_data) {
			$origin_data = explode ( ",", $origin_data );
			$result = array_unique ( array_merge ( $origin_data, $news_ids ) );
			foreach ( $result as $str ) {
				$str_result = $str_result . $str . ",";
			}
			$str_result = substr ( $str_result, 0, - 1 );
		} else {
			foreach ( $news_ids as $str ) {
				$str_result = $str_result . $str . ",";
			}
			$str_result = substr ( $str_result, 0, - 1 );
		}
		
		// writeData( "str_result:" . $str_result . "<br>");
		$sql = "update userbehavior set " . $fieldName . " ='" . $str_result . "' where user_id='" . $user_id . "'";
		$res = $this->conn->query ( $sql );
		if ($res)
			return 1;
		return 0;
	}
	function addOneRecommendNum($news_id) {
		$sql = "update newsrecord set news_recommend_num=news_recommend_num+1 where news_id='" . $news_id . "'";
		$res = $this->conn->query ( $sql );
		if ($res)
			return 1;
		return 0;
	}
	function removeStorageNews($news_ids, $userId) {
		// writeData( "userId:".$userId);
		$sql = "select user_storage_news_id from userbehavior where user_id='" . $userId . "' limit 1";
		$res = $this->conn->query ( $sql );
		$origin_data = $res->fetch_assoc () ["user_storage_news_id"];
		// writeData( "origin_data:" . $origin_data );
		// 求差集
		$origin_data = explode ( ",", $origin_data );
		// writeData( "origin_data:" . json_encode($origin_data) . "<br>");
		// writeData( "news_ids:" . json_encode($news_ids) . "<br>");
		$result = array_diff ( $origin_data, $news_ids );
		// writeData( "result:" . json_encode($result) . "<br>");
		$str_result = '';
		foreach ( $result as $str ) {
			$str_result = $str_result . $str . ",";
		}
		if (strlen ( $str_result ))
			$str_result = substr ( $str_result, 0, - 1 );
			// writeData( "str_result:" . $str_result . "<br>");
		$sql = "update userbehavior set user_storage_news_id ='" . $str_result . "' where user_id='" . $userId . "'";
		$res = $this->conn->query ( $sql );
		if ($res)
			return 1;
		return 0;
	}
	function reportNews($news_id, $userId, $describe) {
		$sql = "insert into newsreportrecord(user_id,news_id,report_time,report_describe) values('" . $userId . "','" . $news_id . "','" . date ( "Y-m-d H:i:s" ) . "','" . $describe . "')";
		writeData ( $sql );
		$res = $this->conn->query ( $sql );
		if ($res)
			return 1;
		return 0;
	}
	function getUserIdByIp($ip) {
		$sql = "select user_id from user where user_ip= '" . $ip . "' limit 1";
		$res = $this->conn->query ( $sql );
		if ($res)
			return $res->fetch_assoc () ["user_id"];
		return 0;
	}
	function addUser($ip) {
		$sql = "insert into user(user_ip)values('" . $ip . "')";
		//echo $sql;
		$res = $this->conn->query ( $sql );
		if ($res) {
			$userId = $this->conn->insert_id;
		//	echo "last userId:".$userId."<br>";
			return $userId;
		}
		return 0;
	}
	
	function closeConn() {
		if($this->conn)closeConnection ( $this->conn );
		//else writeData($this->conn->sqlstate);
	}
	
}
function str_n_pos($str, $n) {
	if (! $n)
		return 0;
	$length = strlen ( $str );
	$j = 0;
	for($i = 0; $i <= $length; $i ++) {
		if ($str {$i} == ',')
			$j ++;
		if ($j == $n)
			return $i;
	}
	return $length;
}
?>