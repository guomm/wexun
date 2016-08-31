<?php
require_once '/usr/local/webData/TouTiao/model/common.php';
require_once '/usr/local/webData/TouTiao/model/constant.php';
$redis = new Redis ();
$redis->connect (  redisIP, redisPort );
// $suffix=date("YmdHi",time());
// $UscanfileName="D:/userScan".$suffix;
// echo $UscanfileName;
// writeUserScan($redis, $UscanfileName);

// $UclickfileName="D:/userClick".$suffix;
// echo $UclickfileName;
// writeUserClick($redis, $UclickfileName);

// $NscanfileName="D:/newsScan".$suffix;
// echo $NscanfileName;
// writeNewsScan($redis, $NscanfileName);

// $NclickfileName="D:/newssClick".$suffix;
// echo $NclickfileName;
// writeNewsClick($redis, $NclickfileName);


$conn= new mysqli(mysqlUrl, mysqlUserName, mysqlPassword, mysqlDBName);
/*if (!$conn)
{
	echo "Could not connect: ";
}else{
	echo "connect successfully.<br>";
}*/
echo "current time is ".date("Y-m-d H:i:s")." \n";
wirteUserBehaviorToDB($redis,$conn);
wirteNewsBehaviorToDB($redis,$conn);
echo "write data to db over..\n";

$conn->close();
$redis->close();
echo "start to recommend...\n";
//do python script
$time=microtime(true);
exec("/usr/local/bin/python2.7 /home/ljg/UpdateUserLR.py");
//exec("/usr/local/bin/python2.7 /usr/local/webData/TouTiao/myscripts/UpdateNews.py");
//exec("/usr/local/bin/python2.7 /usr/local/webData/TouTiao/myscripts/UpdateCurrent.py");
exec("/usr/local/bin/python2.7 /home/ljg/UpdatePush.py");
echo "end. time is ".(microtime(true)-$time)."\n";
//$res=$conn->query("select * from user");


// function writeUserScan($redis,$fileName){
// 	writeDataToFile($redis,"us:*",$fileName);
// }

// function writeUserClick($redis,$fileName){
// 	writeDataToFile($redis,"uc:*",$fileName);
// }

// function writeNewsScan($redis,$fileName){
// 	writeDataToFile($redis,"ns:*",$fileName);
// }

// function writeNewsClick($redis,$fileName){
// 	writeDataToFile($redis,"nc:*",$fileName);
// }

function wirteUserBehaviorToDB($redis,$mysql){
	$keys=$redis->keys("us:*");
	$redis->pipeline();
	
	foreach ($keys as $key){
		$redis->sMembers($key);
		$redis->sMembers("uc:".substr($key, 3));
		$redis->del($key);
		$redis->del("uc:".substr($key, 3));
	}
	$result=$redis->exec();
	$num=count($result);
	$sql="insert into rec_behav(user_id,skim_data,read_data,update_time) values(?,?,?,?)";
	$stmt=$mysql->prepare($sql);
	$stmt->bind_param("ssss",$user_id,$skim_data,$read_data,$time_temp);
	$time_temp=time();
	for($i=0;$i<$num;$i+=4){
		$skim_data='['.implode(",",$result[$i])."]";
		$read_data='['.implode(",",$result[$i+1])."]";
		//$sqlTemp=$sql.$data.substr($keys[$i/2], 3).",".$skim_data.",".$read_data.",".time().")";
		//echo $sqlTemp;
		//$stmt->bind_param("ssss",$data.substr($keys[$i/2], 3),$skim_data,$read_data,$timeTemp);
		//$mysql->query($sqlTemp);
		$user_id=substr($keys[$i/2], 3);
		$stmt->execute();
	}
	$stmt->close();
}

function wirteNewsBehaviorToDB($redis,$mysql){
	$keys=$redis->keys("ns:*");
	$redis->pipeline();

	foreach ($keys as $key){
		$redis->sMembers($key);
		$redis->sMembers("nc:".substr($key, 3));
		$redis->del($key);
		$redis->del("nc:".substr($key, 3));
	}
	$result=$redis->exec();
	$num=count($result);
	$sql="insert into rec_news_behav(news_id,skim_user,read_user,update_time) values(?,?,?,?)";
	$stmt=$mysql->prepare($sql);
	$stmt->bind_param("ssss",$news_id,$skim_user,$read_user,$time_temp);
	$time_temp=time();
	for($i=0;$i<$num;$i+=4){
		$skim_user='['.implode(",",$result[$i])."]";
		$read_user='['.implode(",",$result[$i+1])."]";
		//$sqlTemp=$sql.$data.substr($keys[$i/2], 3).",".$skim_data.",".$read_data.",".time().")";
		//echo $sqlTemp;
		//$stmt->bind_param("ssss",$data.substr($keys[$i/2], 3),$skim_data,$read_data,$timeTemp);
		//$mysql->query($sqlTemp);
		$news_id=substr($keys[$i/2], 3);
		$stmt->execute();
	}
	$stmt->close();
}

// function writeDataToFile($redis,$key,$fileName){
// 	$keys=$redis->keys($key);
// 	print_r($keys);
// 	$redis->pipeline();
// 	foreach ($keys as $key){
// 		$redis->sMembers($key);
// 		$redis->sMembers($key);
// 		$redis->del($key);
// 	}
// 	$result=$redis->exec();

// 	print_r($result);

// 	$data='';
// 	$num=count($result);
// 	for($i=0;$i<$num;$i+=2){
		
// 		$data=$data.substr($keys[$i/2], 3)."\t".implode(",",$result[$i])."\r\n";
// 	}

// 	$file = fopen ( $fileName, "a" );
// 	fwrite ( $file, $data );
// 	fclose ( $file );
// }

?>
