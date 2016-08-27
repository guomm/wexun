<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="description" content="We讯分享">
<title>头条</title>
<link rel="stylesheet" type="text/css" href="../css/style.css" />
<link rel="stylesheet" href="../css/bootstrap.min.css">
<link rel="stylesheet" href="../css/common.css">
<script type="text/javascript" src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/main.js"></script>
<link rel="stylesheet" href="../css/share.min.css">
<script type="text/javascript" charset="utf-8">

<?php
require_once '../model/common.php';
require_once '../model/constant.php';
require_once '../dao/abstractDao.php';
require_once '../dao/commomDao.php';

if(secret2string($_COOKIE['userAccount'])!="123")return;
$report_id = $_GET ["report_id"];
// $userId=secret2string($_COOKIE["userId"]);
$dao = new CommonDao ();
// $data=$dao->getDetailNews($news_id,$userId);
$data = $dao->getReportDetailNews ( $report_id );
echo "var report_id=" . $report_id . ";\n";
echo "var news_data_url='" . $data ["news_data"] . "';\n";
// var_dump($data);
?>

// $.ajax({
// 	url: "",  
// 	type:'get',
// 	dataType:'html',
// 	success: function(data){
// 		console.log(data);
// 		//data=data.replace(/\">/g,"\"><br>");
// 		$(".news_content").append(data);
// 	},  
// 	error:function(data){
// 		console.log(data);
// 		$(".news_content").append("<span class='agency' >新闻内容加载失败...</span>");
// 	}
		
// });
</script>
</head>
<body>
	<?php include("header.php")?>

	<div class="container">

		<div class="col-sm-10 detail-artical">
			<div class="news_content">
				<div class="detail_label">
					<a href="index.php">首页</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span>举报</span>&nbsp;&nbsp;>&nbsp;&nbsp;正文
				</div>
				<h2><?php echo $data["news_title"];?></h2>
				<div class="agency">
					<span><?php echo $data["agency_name"];?></span>&nbsp;&nbsp;&nbsp;&nbsp;<span><?php echo $data["news_time"];?></span>
				</div>
			</div>

			<div>
				<p class="bounday"></p>
			</div>
			<div class="bottom">
				<textarea rows="3" class="form-control" readonly="readonly"><?php echo $data["report_describe"];?></textarea>
				<div class="mark_report"> 
					<button type="button" class="btn btn-primary col-sm-offset-3" id="wrong">标记为违法新闻</button>
					<button type="button" class="btn btn-primary col-sm-offset-3" id="right">标记为合法新闻</button>
				</div>
			</div>
		</div>
	</div>

	<div class="bottom_line">
		<div class='text-muted'>单位名称：信安实习生二组</div>
		<div class='text-muted'>违法信息举报：12306</div>
	</div>
</body>
<script src="../js/share.min.js"></script>
<script type="text/javascript">

	$("#right").bind('click', function(){
		alert("已处理");
		$.ajax({
	 	url: "reportDone.php",  
		data: {
			'report_id':report_id,
		},
		type:'get',
	 	success: function(data){
	 		console.log(data);
	 	},  
	 	error:function(data){
	 		console.log(data);
	 	}	
	});
	}); 

	$("#wrong").bind('click', function(){
		alert("已处理");
		$.ajax({
	 	url: "reportDone.php",  
		data: {
			'report_id':report_id,
		},
		type:'get',
	 	success: function(data){
	 		console.log(data);
	 	},  
	 	error:function(data){
	 		console.log(data);
	 	}	
	});
	}); 
</script>

</html>