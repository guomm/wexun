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

<script src="../js/main.js"></script>
<script type="text/javascript">
var mark = true; 
function setTure(){
	mark = true; 
} 

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
$.ajax({
	url: "../room.php",  
	data: {
		'type':'getRecommendNews',
		'num':10
	},
	type:'post',
	dataType:'json',
	success: function(data){
		//alert(data);
		var vals="";
		for(var i=0;i<data.length;i++){
			//console.log(date('Y-m-d H:i:s')-data[i].news_time);
			console.log(data[i].news_time);
			if(data[i].news_img_num==0){
				vals+=loadDataNoPic(data[i]);
			}else if(data[i].news_img_num==1){
				vals+=loadDataOnePic(data[i]);
			}else{
				vals+=loadDataThreePic(data[i]);
			}
		}
		$("#tmp").html(vals);
	},  
	error:function(){
			alert("加载失败，请稍候再试");
	}
		
});

function loadDataNoPic(data){
	return "<div class='clearfix news-item '><div><div class='title_box'><a href='newsDetail.php?news_id="+data.news_id+"&label_type=0'>"+data.news_title+"</a></div><div class='abstract'>"+
			"<a href='newsDetail.php?news_id="+data.news_id+"&label_type=0'>"+data.news_abstract+"</a></div><div class='timer small'><span  class='text-muted'>"+data.agency_name+"</span> &middot;"+
			" <span class='text-muted'>"+transferTime(data.news_time)+"</span></div></div></div>";
}

function loadDataOnePic(data){
	return "<div class='clearfix news-item '><div class='pull-left'><a href='newsDetail.php?news_id="+data.news_id+"&label_type=0'><img class='feedimg' src='"+data.news_imgs+"' alt='图片'>" +
			"</a></div><div class='title_box'><a href='newsDetail.php?news_id="+data.news_id+"&label_type=0'>"+data.news_title+"</a></div><div class='abstract'>"+
			"<a href='newsDetail.php?news_id="+data.news_id+"&label_type=0'>"+data.news_abstract+"</a></div><div class='timer small'><span  class='text-muted'>"+data.agency_name+"</span> &middot;"+
			" <span class='text-muted'>"+transferTime(data.news_time)+"</span></div></div></div>";
}
function loadDataThreePic(data){
	var imgs = data.news_imgs.split(';');
	return "<div class='clearfix news-item '><div><div class='title_box'><a href='newsDetail.php?news_id="+data.news_id+"&label_type=0'>"+data.news_title+"</a></div><div class='image-list clearfix'>"+
					"<a href='newsDetail.php?news_id="+data.news_id+"&label_type=0'>"+
						"<div class='night-image'"+
							"style='background-image: url("+imgs[0]+")'></div>"+
					"</a> <a href='newsDetail.php?news_id="+data.news_id+"&label_type=0'>"+
						"<div class='night-image'"+
							"style='background-image: url("+imgs[1]+")'></div>"+
					"</a> <a href='newsDetail.php?news_id="+data.news_id+"&label_type=0'>"+
						"<div class='night-image'"+
							"style='background-image: url("+imgs[2]+")'></div>"+
					"</a></div><div class='timer small'><span  class='text-muted'>"+data.agency_name+"</span> &middot;"+
			" <span class='text-muted'>"+transferTime(data.news_time)+"</span></div></div></div>";
}

$(window).scroll(function() {
	//$(window).scrollTop()这个方法是当前滚动条滚动的距离
	//$(window).height()获取当前窗体的高度
	//$(document).height()获取当前文档的高度
	var bot = 50; //bot是底部距离的高度
	if (mark &&( bot+$(window).scrollTop()) >= ($(document).height() - $(window).height())) {
		//当底部基本距离+滚动的高度〉=文档的高度-窗体的高度时；
		//我们需要去异步加载数据了
	$.ajax({
		url: "../room.php",  
		data: {
			'type':'getRecommendNews',
			'num':15
		},
		type:'post',
		dataType:'json',
		success: function(data){
			//alert(data);
			var vals="";
			for(var i=0;i<data.length;i++){
				console.log(data[i]);
				if(data[i].news_img_num==0){
					vals+=loadDataNoPic(data[i]);
				}else if(data[i].news_img_num==1){
					vals+=loadDataOnePic(data[i]);
				}else{
					vals+=loadDataThreePic(data[i]);
				}
			}
			$("#tmp").append(vals);
		},  
		error:function(){
			alert("加载失败，请稍候再试");
		}
	});
	setTimeout(setTure,500); mark = false; 
}

});


</script>
</head>
<body>
<?php include("header.php")?>

    <div class="container" id="con">
		<div class="col-sm-2 text-center">
			<ul class="list-unstyled category">
				<li><a class="bg-click" href="index.php"><img src="../images/recomment.svg">推荐</a></li>
				<li><a href=""><img src="../images/hot.svg">热点</a></li>
				<li><a href="science.php"><img src="../images/science.svg">科技</a></li>
				<li><a href=""><img src="../images/enjoy.svg">娱乐</a></li>
				<li><a href=""><img src="../images/money.svg">财经</a></li>
				<li><a href=""><img src="../images/physical.svg">体育</a></li>
				<li><a href=""><img src="../images/car.svg">汽车</a></li>
			</ul>
		</div>
		<div class="col-sm-10" id="tmp">
		</div>
	</div>
</body>
</html>