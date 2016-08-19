<?php
class CommonDao extends AbstractDao {
	function getStorageById($userId, $num, $offset) {
		$res = $this->conn->query ( "select user_storage_news from userbehavior where user_id='" . $userId . "' limit 1 " );
		if ($res) {
			$storage = $res->fetch_assoc () ["user_storage_news"];
			$pos1 = str_n_pos ( $storage, ";", $offset );
			$pos2 = str_n_pos ( $storage, ";", $offset + $num );
			$storage = substr ( $storage, $pos1, $pos2 - $pos1 );
			if ($storage) {
				if ($storage {0} == ",")
					$storage = substr ( $storage, 1 );
			}
			// writeData("storage:".$storage);
			if ($storage) {
				$sql = "select news_id,agency_name,news_title,news_time,news_imgs,news_img_num,news_abstract from news where news_id in (" . $storage . ") ";
				$res = $this->conn->query ( $sql );
				if ($res) {
					return json_encode ( $res->fetch_all ( MYSQLI_ASSOC ) );
				} else {
					return 0;
				}
			} else {
				// nothing
				return 1;
			}
		}
		return 0;
	}
	function getRecommendNews($userId, $offset, $num) {
		$sql = "select news_id from newsrecommend where user_id= '" . $userId . "' limit 1";
		$res = $this->conn->query ( $sql );
		if ($res->num_rows) {
			$newsIds = $res->fetch_assoc () ["news_id"];
			$pos1 = str_n_pos ( $newsIds, ",", $offset );
			$pos2 = str_n_pos ( $newsIds, ",", $offset + $num );
			$newsIds = substr ( $newsIds, $pos1, $pos2 - $pos1 );
			if ($newsIds) {
				if ($newsIds {0} == ",")
					$newsIds = substr ( $newsIds, 1 );
			}
			if ($newsIds) {
				$sql = "select news_id,agency_name,news_title,news_time,news_imgs,news_img_num,news_abstract from news where news_id in (" . $newsIds . ") ";
				$res = $this->conn->query ( $sql );
				if ($res) {
					return json_encode ( $res->fetch_all ( MYSQLI_ASSOC ) );
				} else {
					return 0;
				}
			}
		}
		return array ();
	}
	function getNewsByLabel($labelId,$offset, $num) {
		$sql = "select news_id from newslabel where label_id='" . $labelId . "' order by add_news_time limit " . $num;
		echo $sql;
		// writeData($sql);
		$res = $this->conn->query ( $sql );
		if ($res && $res->num_rows)
			return $res->fetch_all ( MYSQLI_ASSOC );
		return 0;
	}
	
	function getUserLikeNews($userId, $num, $offset){}
}

?> 