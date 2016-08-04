<?php
class User{
	public $id;
	public $name;
	public $gender;
	public $account;
	public $birthday;
	public $interest;
	public $password;
	
	public function __construct($name,$gender,$account,$birthday,$interest,$password){
		$this->name=$name;
		$this->gender=$gender;
		$this->account=$account;
		$this->birthday=$birthday;
		
		$this->password=$password;
		
		$num = count ( $interest );
		$val = 0;
		for($i = 0; $i < $num; ++ $i) {
			$val += $interest [$i];
		}
		$this->interest = $val;
		writeData("num:".$num."  interest:".$this->interest);
	}
	
	function printUser(){
		echo "name is ".$this->name."<br>";
		echo "gender is ".$this->gender."<br>";
		echo "account is ".$this->account."<br>";
		echo "birthday is ".$this->birthday."<br>";
		echo "password is ".$this->password."<br>";
		echo "interest is ".$this->interest."<br>";
		foreach($this->interest as $temp){
			echo $temp;
		}
	}
}
?>
