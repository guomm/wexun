<?php
abstract class AbstractDao {
	protected $conn;
	function __construct() {
// 		$this->conn = new mysqli ( mysqlUrl, mysqlUserName, mysqlPassword, mysqlDBName );
// 		if (! $this->conn) {
// 			echo "Could not connect: " . CommonDao::$conn->connect_error;
// 		}
	}
	
	protected function getConn(){
		if(!$this->conn ){
			$this->conn = new mysqli ( mysqlUrl, mysqlUserName, mysqlPassword, mysqlDBName );
		}
		return $this->conn;
	}
	function login($userAccount, $password) {
		$sql = "select user_id,user_name from user where account='$userAccount' and password='$password' limit 1 ";
		// writeData($sql."\n");
		$res = $this->getConn()->query ( $sql );
		if ($res->num_rows)
			return json_encode ( $res->fetch_assoc () );
		return 0;
	}
	function registerUser($user) {
		$sql = "insert into user(user_name,user_birthday,user_gender,user_label,account,password) values ('" . $user->name . "','" . $user->birthday . "'," . $user->gender . "," . $user->interest . ",'" . $user->account . "','" . $user->password . "') ";
		// writeData($sql."\n");
		$res = $this->getConn()->query ( $sql );
		if ($res)
			return 1;
		return 0;
	}
	function checkUserAccount($userAccount) {
		$sql = "select user_id from user where account= '$userAccount' limit 1 ";
		// writeData($sql);
		$res = $this->getConn()->query ( $sql );
		if ($res->num_rows)
			return 1;
		return 0;
	}
	function updateUser($user, $userId) {
		$sql = "update user set user_gender='" . $user->gender . "',user_name='" . $user->name . "',user_birthday='" . $user->birthday . "',user_label='" . $user->interest . "' where user_id='" . $userId . "'";
		$res = $this->getConn()->query ( $sql );
		if ($res)
			return 1;
		return 0;
	}
	function getUserInfo($userId) {
		$res = $this->getConn()->query ( "select user_name,user_birthday,user_gender,user_label from user where user_id='" . $userId . "' limit 1 " );
		if ($res)
			return $res->fetch_assoc ();
		return 0;
	}
	
	// 将推荐数+1或者-1
	function addOneRecommendNum($news_id, $val) {
		$sql = "update newsrecord set news_recommend_num=news_recommend_num+" . $val . " where news_id='" . $news_id . "'";
		$res = $this->getConn()->query ( $sql );
		if ($res)
			return 1;
		return 0;
	}
	function reportNews($news_id, $userId, $describe) {
		$sql = "insert into newsreportrecord(user_id,news_id,report_time,report_describe) values('" . $userId . "','" . $news_id . "','" . date ( "Y-m-d H:i:s" ) . "','" . $describe . "')";
		writeData ( $sql );
		$res = $this->getConn()->query ( $sql );
		if ($res)
			return 1;
		return 0;
	}
// 	function getUserIdByCookie($cookie) {
// 		$sql = "select user_id from user where user_cookie= '" . $cookie . "' limit 1";
// 		$res = $this->conn->query ( $sql );
// 		if ($res->num_rows)
// 			return $res->fetch_assoc () ["user_id"];
// 		return 0;
// 	}
	function addUser($cookie) {
		$sql = "insert into user(user_cookie)values('" . $cookie . "')";
		// echo $sql;
		$res = $this->getConn()->query ( $sql );
		if ($res) {
			$userId = $this->conn->insert_id;
			// echo "last userId:".$userId."<br>";
			return $userId;
		}
		return 0;
	}
	function getNewsByIds($newsIds) {
		$sql = "select news_id,agency_name,news_title,news_time,news_abstract,news_data,news_imgs from news where news_id in (" . $newsIds . ")";
		//writeData($sql);
		$newsS = $this->getConn()->query ( $sql );
		if ($newsS)
			return $newsS->fetch_all ( MYSQLI_ASSOC );
		return 0;
	}
	function recommendNews($news_id, $userId) {
		$sql = "update  userbehavior  set user_reocmmend_news=concat(user_reocmmend_news,'," . $news_id . "') where user_id='" . $userId . "' ";
		$newsS = $this->getConn()->query ( $sql );
		if ($newsS)
			return 1;
		return 0;
	}
	function storageNews($news_id, $userId) {
		$sql = "update  userbehavior  set user_storage_news=concat(user_storage_news,';" . time () . "," . $news_id . "') where user_id='" . $userId . "' ";
		$newsS = $this->getConn()->query ( $sql );
		if ($newsS)
			return 1;
		return 0;
	}
	function removeRecomm($news_id, $userId) {
		$sql = "select user_reocmmend_news from userbehavior where user_id='" . $userId . "'";
		$newsS = $this->getConn()->query ( $sql );
		if ($newsS->num_rows) {
			$newsIds = $newsS->fetch_assoc () ["user_reocmmend_news"];
			$newsIds = explode ( ",", $newsIds );
			$result = '';
			foreach ( $newsIds as $temp ) {
				if ($temp != $news_id)
					$result = $result . $temp . ",";
			}
			if (strlen ( $result ))
				$result = substr ( $result, 0, - 1 );
			$sql = "update  userbehavior  set user_reocmmend_news='" . $result . "' where user_id='" . $userId . "' ";
			$newsS = $this->conn->query ( $sql );
			if ($newsS)
				return 1;
			return 0;
		}
		return 0;
	}
	function removeStorage($news_id, $userId) {
		$sql = "select user_storage_news from userbehavior where user_id='" . $userId . "'";
		$newsS = $this->getConn()->query ( $sql );
		if ($newsS->num_rows) {
			$newsIds = $newsS->fetch_assoc () ["user_storage_news"];
			$newsIds = explode ( ";", $newsIds );
			$result = '';
			foreach ( $newsIds as $temp ) {
				if (explode ( ",", $temp ) [1] != $news_id)
					$result = $result . $temp . ";";
			}
			if (strlen ( $result ))
				$result = substr ( $result, 0, - 1 );
			$sql = "update  userbehavior  set user_storage_news='" . $result . "' where user_id='" . $userId . "' ";
			$newsS = $this->getConn()->query ( $sql );
			if ($newsS)
				return 1;
			return 0;
		}
		return 0;
	}
	function getStoragePageCount($userId) {
		$res = $this->getConn()->query ( "select user_storage_news from userbehavior where user_id='" . $userId . "' limit 1 " );
		if ($res->num_rows) {
			$storage = $res->fetch_assoc () ["user_storage_news"];
			$count = mb_substr_count ( $storage, ";" );
			$pageCount = ceil ( $count / 10 );
			return $pageCount;
		}
		return 0;
	}
	function getSearchValCount($search_val) {
		$res = $this->getConn()->query ( "select count(*) as count from news where news_title like '%" . $search_val . "%'" );
		if ($res) {
			$storage = $res->fetch_assoc () ["count"];
			$pageCount = ceil ( $storage / 10 );
			return $pageCount;
		}
		return 0;
	}
	function getSearchVal($num, $offset, $search_val) {
		$sql = "select news_id,agency_name,news_title,news_time,news_imgs,news_abstract from news where news_title like '%" . $search_val . "%' order by news_time limit " . $offset . "," . $num;
		$res = $this->getConn()->query ( $sql );
		if ($res->num_rows) {
			return $res->fetch_all ( MYSQLI_ASSOC );
		}
		return 0;
	}
	function getDetailNews($news_id) {
		$sql = "select agency_name,news_time,news_title from news where news_id='" . $news_id . "' limit 1";
		$res = $this->getConn()->query ( $sql );
		if ($res->num_rows)
			return $res->fetch_assoc ();
		return 0;
	}
	
	function getReportNewsTitle($offset,$num){
		$sql = "select newsreportrecord.report_id,news.news_title,newsreportrecord.report_time from news,newsreportrecord where news.news_id=newsreportrecord.news_id and newsreportrecord.is_deal=0 order by report_time desc limit $offset,$num";
		$res = $this->getConn()->query ( $sql );
		if ($res->num_rows)
			return $res->fetch_all ( MYSQLI_ASSOC );
			return 0;
	}
	
	function getReportNewsCount(){
		$sql = "select count(*) as count from newsreportrecord  where is_deal=0";
		$res = $this->getConn()->query ( $sql );
		if ($res->num_rows)
			return $res->fetch_assoc ()["count"];
			return 0;
	}
	
	function getReportDetailNews($report_id){
		$sql = "select agency_name,news_time,news_title,report_describe,news_data from news,newsreportrecord where newsreportrecord.report_id=$report_id and newsreportrecord.news_id=news.news_id limit 1";
		$res = $this->getConn()->query ( $sql );
		if ($res->num_rows)
			return $res->fetch_assoc ();
			return 0;
	}
	
	function dealReport ( $report_id ){
		$res = $this->getConn()->query ( "update newsreportrecord set is_deal=1 where report_id=$report_id" );
		if ($res) {
			return 1;
		}
		return 0;
	}
	function __destruct() {
		if($this->conn)
		$this->conn->close ();
	}
	
	
	
	abstract function getUserLikeNews($userId, $num, $offset);
	abstract function getStorageByUserId($userId, $num, $offset);
	abstract function getRecommendNews($userId, $offset, $num);
	abstract function getNewsByLabel($labelId, $offset, $num);
}
?>