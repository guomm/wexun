<?php
require_once ("bean/user.php");
require_once ("dao/commonDao.php");
class UserDao {
	private $conn;
	function __construct() {
		$this->conn = createConnection ();
		if (! $this->conn) {
			echo "Could not connect: " . CommonDao::$conn->connect_error;
		}
	}
	function login($userAccount, $password) {
		$sql = "select user_id,user_name from user where account='$userAccount' and password='$password' limit 1 ";
		// writeData($sql."\n");
		$res = $this->conn->query ( $sql );
		if ($res->num_rows)
			return json_encode ( $res->fetch_assoc () );
		return 0;
	}
	function getUserInfo($userId) {
		$res = $this->conn->query ( "select user_name,user_birthday,user_gender,user_label from user where user_id='" . $userId . "' limit 1 " );
		if ($res)
			return  json_encode($res->fetch_assoc ()) ;
		return 0;
	}
	function registerUser($user) {
		$sql = "insert into user(user_name,user_birthday,user_gender,user_label,account,password) values ('" . $user->name . "','" . $user->birthday . "'," . $user->gender . "," . $user->interest . ",'" . $user->account . "','" . $user->password . "') ";
		// writeData($sql."\n");
		$res = $this->conn->query ( $sql );
		if ($res)
			return 1;
		return 0;
	}
	function checkUserAccount($userAccount) {
		$sql = "select user_id from user where account= '$userAccount' limit 1 ";
		// writeData($sql);
		$res = $this->conn->query ( $sql );
		if ($res->num_rows)
			return 1;
		return 0;
	}
	function updateUser($user, $userId) {
		$sql = "update user set user_gender='" . $user->gender . "',user_name='" . $user->name . "',user_birthday='" . $user->birthday . "',user_label='" . $user->interest . "' where user_id='" . $userId . "'";
		$res = $this->conn->query ( $sql );
		if ($res)
			return 1;
		return 0;
	}
	// get user's stoage and recommend news
	// storage:type=1
	// recommend:type=2
	function getStorageById($userId, $num, $offset) {
		$res = $this->conn->query ( "select user_storage_news_id from userbehavior where user_id='" . $userId . "' limit 1 " );
		if ($res) {
			$storage = $res->fetch_assoc () ["user_storage_news_id"];
			// writeData("offset:".$offset);
			// writeData("num:".$num);
			$pos1 = str_n_pos ( $storage, $offset );
			$pos2 = str_n_pos ( $storage, $offset + $num );
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
	function getStoragePageCount($userId) {
		$res = $this->conn->query ( "select user_storage_news_id from userbehavior where user_id='" . $userId . "' limit 1 " );
		if ($res) {
			$storage = $res->fetch_assoc () ["user_storage_news_id"];
			$count = mb_substr_count ( $storage, "," ) + 1;
			$pageCount = ceil ( $count / 10 );
			return $pageCount;
		}
		return 0;
	}
	function getSearchValCount($search_val) {
		$res = $this->conn->query ( "select count(*) as count from news where news_title like '%" . $search_val . "%'" );
		if ($res) {
			$storage = $res->fetch_assoc () ["count"];
			$pageCount = ceil ( $storage / 10 );
			return $pageCount;
		}
		return 0;
	}
	function getSearchVal($num, $offset) {
		$sql = "select news_id,agency_name,news_title,news_time,news_imgs,news_img_num,news_abstract from news where news_title like '%" . $search_val . "%' order by news_time limit " . $offset . "," . $num;
		$res = $this->conn->query ( $sql );
		if ($res) {
			return json_encode ( $res->fetch_all ( MYSQLI_ASSOC ) );
		}
		return 0;
	}
	function closeConn() {
		closeConnection ( $this->conn );
	}
}
?>