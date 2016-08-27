<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>头条</title>
<link rel="stylesheet" type="text/css" href="../css/style.css" />
<link rel="stylesheet" href="../css/bootstrap.min.css">
<link rel="stylesheet" href="../css/common.css">
<script type="text/javascript" src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/jquery.page.js"></script>
<script src="../js/main.js"></script>
</head>
<body>
<?php 
	require_once '../model/common.php';
	if(secret2string($_COOKIE['userAccount'])!="123")return;
	 include("header.php")?>

    <div class="container" id="con">
		<div class="col-sm-offset-2 col-sm-10" id="tmp">
			<div class='content'></div>
			<div class='tcdPageCode news-item'></div>
		</div>
	</div>
</body>
<script type="text/javascript">

function transferTime(time){
	var returnVal;
	var timeSpan=((new Date().getTime())-(new Date(time).getTime()))/1000;
	if(timeSpan<=60){
		returnVal="刚刚";
	}else if(timeSpan<=3600){
		returnVal=parseInt(timeSpan/60)+"分钟前";
	}else if(timeSpan<=21600){
		returnVal=parseInt(timeSpan/3600)+"小时前";
	}else{
		returnVal=time;
	}
	return returnVal;
}
<?php 
require_once '../model/common.php';
require_once '../model/constant.php';
require_once '../dao/abstractDao.php';
require_once '../dao/commomDao.php';
$dao=new CommonDao();

$pageCount=ceil($dao->getReportNewsCount()/10);
$num=10;
$news=$dao->getReportNewsTitle(0, $num);
echo "var num=10;";
echo "var pageCount=$pageCount;";
echo "var initNews=".json_encode($news).";";
?>		

var vals='';
for(var i=0;i<initNews.length;i++){
	vals+=loadDataNoPic(initNews[i]);
}
$(".content").html(vals);
 	$(".tcdPageCode").createPage({
			    pageCount:pageCount,
			    current:1,
			    backFn:function(p){
			        //单机回调方法
					var num=10;
					var offset=(p-1)*num;
					$(window).scrollTop(0);
					$.ajax({
 						url: "reportDeal.php",  
 						data: {
 							'num':num,
 							'offset':offset
 						},
 						type:'get',
 						dataType:'json',
 						success: function(data){
 							console.log(data);
 							if(data==null || data==0)return;
 							var vals='';
 					    	for(var i=0;i<data.length;i++){
 					    		vals+=loadDataNoPic(data[i]);
 					    	}
 		 				 	$(".content").html(vals);
 						},
 				 		error:function(){
 			 				alert("加载失败，请稍候再试");
 			 		}
			    	});
					
 				}
 			 		
			  });

function loadDataNoPic(data){
	return "<div class='clearfix news-item '><div><div class='title_box'><a target='_blank' href='newsReportDetail.php?report_id="+data.report_id+"'>"+data.news_title+"</a></div>"+
			"<div class='timer small report_item'><span  class='text-muted'>举报时间</span> &middot;"+
			" <span class='text-muted'>"+transferTime(data.report_time)+"</span></div></div></div>";
}

</script>
</html>