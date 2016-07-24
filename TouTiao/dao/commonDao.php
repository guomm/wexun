<?php
class CommonDao{
	public static $conn;
	
	static function createConnByParams($url,$userName,$password,$db){
		CommonDao::$conn= new mysqli($url,$userName,$password,$db);
	}
	
	static function  createConn(){
		$url="localhost:3307";
		$userName="root";
		$password="1234";
		$db="wexun";
		CommonDao::$conn= new mysqli($url,$userName,$password,$db);
		return CommonDao::$conn;
	}
}
?>