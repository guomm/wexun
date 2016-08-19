<?php
class RedisDao extends AbstractDao {
	function getStorageByUserId($userId, $num, $offset) {
		$res = $this->getConn()->query ( "select user_storage_news from userbehavior where user_id='" . $userId . "' limit 1 " );
		if ($res->num_rows) {
			$storage = $res->fetch_assoc () ["user_storage_news"];
			return $storage;
		}
		return 0;
	}
	
	function getRecommendNews($userId,$offset,$num) {
		$sql = "select news_id from newsrecommend where user_id= '" . $userId . "' limit 1";
		$res = $this->getConn()->query ( $sql );
		if ($res->num_rows) {
			$newsIds = $res->fetch_assoc () ["news_id"];
			return explode ( ",", $newsIds );
		}
		return array ();
	}
	
	function getNewsByLabel($labelId,$offset, $num) {
		$sql = "select news_id from newslabel where label_id='" . $labelId . "' order by add_news_time limit " . $num;
		//echo $sql;
		// writeData($sql);
		$res = $this->getConn()->query ( $sql );
		if ($res && $res->num_rows)
			return $res->fetch_all ( MYSQLI_ASSOC );
		return 0;
	}
	function getUserLikeNews($userId, $num, $offset){
		$res = $this->getConn()->query ( "select user_reocmmend_news from userbehavior where user_id='" . $userId . "' limit 1 " );
		if ($res->num_rows) {
			$storage = $res->fetch_assoc () ["user_reocmmend_news"];
			return $storage;
		}
		return 0;
	}
}

?> 