<?php
require_once ("bean/user.php");
require_once ("dao/commonDao.php");
class UserDao {
	private $conn;
	
	function __construct() {
		if (! CommonDao::$conn) {
			$this->conn=CommonDao::createConn ();
		}
		if (! CommonDao::$conn) {
			echo "Could not connect: " . CommonDao::$conn->connect_error;
		}
	}
	
	function login($userAccount, $password) {
		$sql="select user_id,user_name from user where account='$userAccount' and password='$password' limit 1 ";
		//writeData($sql."\n");
		$res = $this->conn->query ($sql);
		if ($res)
			return json_encode ( $res->fetch_assoc () );
		return 0;
	}
	function getUserInfo($userId) {
		$res = $this->conn->query ( "select user_name,user_img,user_birthday,user_gender,user_label from user where userid=$userId limit 1 " );
		if ($res)
			return json_encode ( $res->fetch_assoc () );
		return 0;
	}
	
	function registerUser($user) {
		
		$sql="insert into user(user_name,user_birthday,user_gender,user_label,account,password) values ('" . $user->name . "','" . $user->birthday . "'," . $user->gender . "," . $user->interest . ",'" . $user->account . "','" . $user->password . "') ";
		//writeData($sql."\n");
		$res = $this->conn->query ($sql);
		if ($res)
			return 1;
		return 0;
	}
	
	function checkUserAccount($userAccount){
		$sql="select user_id from user where account= '$userAccount' limit 1 ";
		//writeData($sql);
		$res = $this->conn->query ($sql);
		if ($res->num_rows)
			return 1;
		return 0;
	}
	// get user's stoage and recommend news
	// storage:type=1
	// recommend:type=2
	function getUserStorage($userId, $offset, $num, $type) {
		$table_name;
		if ($type == 1) {
			$table_name = "user_storage";
		} else if ($type == 2) {
			$table_name = "user_recommend";
		}
		$res = $this->conn->query ( "select $table_name from user where userid=$userId limit 1 " );
		if ($res) {
			$storage = json_decode ( $res->fetch_assoc () [0] );
			$output = array_slice ( $storage, $offset, $num );
			if ($output) {
				$res = $this->conn->query ( "select news_id,news_title,news_abstract,agency_name,news_time,news_imgs from news where news_id in $output " );
				if ($res) {
					return json_encode ( $res->fetch_all ( MYSQLI_ASSOC ) );
				} else {
					return "load news error";
				}
			} else {
				// nothing
				return "have loaded all";
			}
		}
		return "load $table_name error";
	}
}
?>