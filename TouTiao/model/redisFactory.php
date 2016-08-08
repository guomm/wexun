<?php
require_once "abstractFactory.php";
require_once "redisModel.php";
class RedisFactory implements AbstractFactory {
	// // 保存例实例在此属性中
	private static $_instance;
	
	// // 构造函数声明为private,防止直接创建对象
	private function __construct() {
		// echo 'I am Construceted';
	}
	
	// 单例方法
	public static function singleton() {
		if (! isset ( self::$_instance )) {
			$c = __CLASS__;
			self::$_instance = new $c ();
		}
		return self::$_instance;
	}
	
	// 阻止用户复制对象实例
	public function __clone() {
		trigger_error ( 'Clone is not allow', E_USER_ERROR );
	}
	
	function createModel() {
		// redis
		$redisDao = self::$_instance->createDao ();
		$model = new RedisModel ( $redisDao, redisIP, redisPort );
		return $model;
	}
	
	function createDao() {
		return new RedisDao ();
	}
}
?>