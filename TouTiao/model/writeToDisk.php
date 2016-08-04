<?php
$redis = new Redis ();
$redis->connect ( '127.0.0.1', 6379 );
$suffix=date("YmdHi",time());
$UscanfileName="D:/userScan".$suffix;
echo $UscanfileName;
writeUserScan($redis, $UscanfileName);

$UclickfileName="D:/userClick".$suffix;
echo $UclickfileName;
writeUserClick($redis, $UclickfileName);

$NscanfileName="D:/newsScan".$suffix;
echo $NscanfileName;
writeNewsScan($redis, $NscanfileName);

$NclickfileName="D:/newssClick".$suffix;
echo $NclickfileName;
writeNewsClick($redis, $NclickfileName);





function writeUserScan($redis,$fileName){
	writeDataToFile($redis,"us:*",$fileName);
}

function writeUserClick($redis,$fileName){
	writeDataToFile($redis,"uc:*",$fileName);
}

function writeNewsScan($redis,$fileName){
	writeDataToFile($redis,"ns:*",$fileName);
}

function writeNewsClick($redis,$fileName){
	writeDataToFile($redis,"nc:*",$fileName);
}

function writeDataToFile($redis,$key,$fileName){
	$keys=$redis->keys($key);
	print_r($keys);
	$redis->pipeline();
	foreach ($keys as $key){
		$redis->sMembers($key);
		$redis->del($key);
	}
	$result=$redis->exec();

	print_r($result);

	$data='';
	$num=count($result);
	for($i=0;$i<$num;$i+=2){
		$data=$data.substr($keys[$i/2], 3)."\t".implode(",",$result[$i])."\r\n";
	}

	$file = fopen ( $fileName, "a" );
	fwrite ( $file, $data );
	fclose ( $file );
}

?>