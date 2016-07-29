<?php    
require 'userDao.php';
class RedisDao{
	private $redis;
	private $userDao;
	function __construct(){
		$this->redis= new Redis();
		$this->redis->connect('127.0.0.1', 6379);
	}
	
	function getUserDao(){
		if(!$this->userDao){
			$this->userDao=new UserDao();
			//echo "create userDao";
		}
		return $this->userDao;
	}
	
// 	function test(){
		
// 	}
	
	function getUserById($userId){
		$result=$this->redis->hGetAll("user:".$userId);
		if(!$result){
			//redis不存在个人信息，从数据库中加载
			$result=$this->getUserDao()->getUserInfo($userId);
			$this->redis->hMset("user:".$userId,$result);
		}
		return json_encode($result);
	}
	
	function updateUser($user, $userId){
		$result=$this->getUserDao()->updateUser($user, $userId);
		$userChanges=array("user_gender"=>$user->gender,"user_name"=>$user->name,"user_birthday"=>$user->birthday ,"user_label"=>  $user->interest);
		if($result)$this->redis->hMset("user:".$userId,$userChanges);
		return $result;
	}
	
	function getUserStorage($userId, $num, $offset){
		//redis取出用户的收藏
		$redis_sort_option=array(
				'LIMIT'=>array($offset,$num),
				'SORT'=>'DESC',
				'BY'=>'userStorage:*->storage_time',
		);
		$userStorage=$this->redis->sort("userStorage:".$userId,$redis_sort_option);
		$this->redis->sort();
	}
}


// function addUser
// //$redis->set('name','guo');
// for($i=0;$i<100;$i++){
// 	//$redis->hMset('user:'.$i,array('name'=>('guo'.$i),'age'=>$i));
// 	 $redis->hIncrBy("user:".$i,"age",1);
// }
// //$name=$redis->get('guo');
// //echo $name;
// //echo $redis->hGetAll('user1');
// //phpinfo();
?> 