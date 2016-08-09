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

</head>
<body>
<?php include("header.php")?>

    <div class="container" id="con">
		<div class="col-sm-2 text-center">
			<ul class="list-unstyled category">
				<li><a  href="#0" id="recomm"><img src="../images/recomment.svg">推荐</a></li>
				<li><a  href="#0" id="hot"><img src="../images/hot.svg">热点</a></li>
				<li><a  href="#0" id="science"><img src="../images/science.svg">科技</a></li>
				<li><a  href="#0" id="enjoy"><img src="../images/enjoy.svg">娱乐</a></li>
				<li><a  href="#0" id="money"><img src="../images/money.svg">财经</a></li>
				<li><a  href="#0" id="physical"><img src="../images/physical.svg">体育</a></li>
				<li><a  href="#0" id="car"><img src="../images/car.svg">汽车</a></li>
			</ul>
		</div>
		<div class="col-sm-10" id="tmp"></div>
		<input type="hidden" id="history_back" />
	</div>

    <button class="btn btn-primary goto-top" title="返回顶部" id="js-goto-top">
        <i class="glyphicon glyphicon-arrow-up"></i>
    </button>

</body>

<script type="text/javascript">

$(function(){
    $("#js-goto-top").on("click", function (e) {
        $("html,body").animate({scrollTop:0}, 300);
    });
});

var mark = true; 
function setTure(){
	mark = true; 
} 

var currentType=0;

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

function loadData(type,labelId,labelName,num,isAdd){
	currentType=labelId;
	$.ajax({
		url: "../room.php",  
		data: {
			'type':type,
			'num':num,
			'labelId':labelId,
			'labelName':labelName
		},
		type:'post',
		dataType:'json',
		success: function(data){
			console.log(data);
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
			if(!isAdd)$("#tmp").html(vals);
			else $("#tmp").append(vals);
		},  
		error:function(data){
			console.log(data);
				alert("加载失败，请稍候再试");
		}
			
	});
}



$("#recomm").on('click', function(event){
	//alert("df");
	$("#recomm").addClass("bg-click");
	$("#hot").removeClass("bg-click");
	$("#science").removeClass("bg-click");
	$("#enjoy").removeClass("bg-click");
	$("#money").removeClass("bg-click");
	$("#car").removeClass("bg-click");
	$("#physical").removeClass("bg-click");

	loadData("getRecommendNews",0,"recom",15,0);
});

$("#science").on('click', function(event){
	$("#science").addClass("bg-click");
	$("#hot").removeClass("bg-click");
	$("#recomm").removeClass("bg-click");
	$("#enjoy").removeClass("bg-click");
	$("#money").removeClass("bg-click");
	$("#car").removeClass("bg-click");
	$("#physical").removeClass("bg-click");

	loadData("getNewsByLabel",1,"sc",15,0);
});


$("#hot").on('click', function(event){
	$("#hot").addClass("bg-click");
	$("#recomm").removeClass("bg-click");
	$("#science").removeClass("bg-click");
	$("#enjoy").removeClass("bg-click");
	$("#money").removeClass("bg-click");
	$("#car").removeClass("bg-click");
	$("#physical").removeClass("bg-click");

	loadData("getNewsByLabel",2,"hot",15,0);
});

$("#enjoy").on('click', function(event){
	$("#enjoy").addClass("bg-click");
	$("#hot").removeClass("bg-click");
	$("#science").removeClass("bg-click");
	$("#recomm").removeClass("bg-click");
	$("#money").removeClass("bg-click");
	$("#car").removeClass("bg-click");
	$("#physical").removeClass("bg-click");

	loadData("getNewsByLabel",3,"enjoy",15,0);
});

$("#money").on('click', function(event){
	$("#money").addClass("bg-click");
	$("#hot").removeClass("bg-click");
	$("#science").removeClass("bg-click");
	$("#enjoy").removeClass("bg-click");
	$("#recomm").removeClass("bg-click");
	$("#car").removeClass("bg-click");
	$("#physical").removeClass("bg-click");

	loadData("getNewsByLabel",4,"money",15,0);
});

$("#car").on('click', function(event){
	$("#car").addClass("bg-click");
	$("#hot").removeClass("bg-click");
	$("#science").removeClass("bg-click");
	$("#enjoy").removeClass("bg-click");
	$("#money").removeClass("bg-click");
	$("#recomm").removeClass("bg-click");
	$("#physical").removeClass("bg-click");

	loadData("getNewsByLabel",5,"car",15,0);
});

$("#physical").on('click', function(event){
	$("#physical").addClass("bg-click");
	$("#hot").removeClass("bg-click");
	$("#science").removeClass("bg-click");
	$("#enjoy").removeClass("bg-click");
	$("#money").removeClass("bg-click");
	$("#car").removeClass("bg-click");
	$("#recomm").removeClass("bg-click");

	loadData("getNewsByLabel",6,"phy",15,0);
});


function loadDataNoPic(data){
	return "<div class='clearfix news-item '><div><div class='title_box'><a target='_blank' href='newsDetail.php?news_id="+data.news_id+"&label_type=0'>"+data.news_title+"</a></div><div class='abstract'>"+
			"<a target='_blank' href='newsDetail.php?news_id="+data.news_id+"&label_type=0'>"+data.news_abstract+"</a></div><div class='timer small'><span  class='text-muted'>"+data.agency_name+"</span> &middot;"+
			" <span class='text-muted'>"+transferTime(data.news_time)+"</span></div></div></div>";
}

function loadDataOnePic(data){
	return "<div class='clearfix news-item '><div class='pull-left'><a target='_blank' href='newsDetail.php?news_id="+data.news_id+"&label_type=0'><img class='feedimg' src='"+data.news_imgs+"' alt='图片'>" +
			"</a></div><div class='title_box'><a target='_blank' href='newsDetail.php?news_id="+data.news_id+"&label_type=0'>"+data.news_title+"</a></div><div class='abstract'>"+
			"<a target='_blank' href='newsDetail.php?news_id="+data.news_id+"&label_type=0'>"+data.news_abstract+"</a></div><div class='timer small'><span  class='text-muted'>"+data.agency_name+"</span> &middot;"+
			" <span class='text-muted'>"+transferTime(data.news_time)+"</span></div></div></div>";
}
function loadDataThreePic(data){
	var imgs = data.news_imgs.split(';');
	return "<div class='clearfix news-item '><div><div class='title_box'><a target='_blank' href='newsDetail.php?news_id="+data.news_id+"&label_type=0'>"+data.news_title+"</a></div><div class='image-list clearfix'>"+
					"<a target='_blank' href='newsDetail.php?news_id="+data.news_id+"&label_type=0'>"+
						"<div class='night-image'"+
							"style='background-image: url("+imgs[0]+")'></div>"+
					"</a> <a target='_blank' href='newsDetail.php?news_id="+data.news_id+"&label_type=0'>"+
						"<div class='night-image'"+
							"style='background-image: url("+imgs[1]+")'></div>"+
					"</a> <a target='_blank' href='newsDetail.php?news_id="+data.news_id+"&label_type=0'>"+
						"<div class='night-image'"+
							"style='background-image: url("+imgs[2]+")'></div>"+
					"</a></div><div class='timer small'><span  class='text-muted'>"+data.agency_name+"</span> &middot;"+
			" <span class='text-muted'>"+transferTime(data.news_time)+"</span></div></div></div>";
}

$(document).ready(function () {
	$("#recomm").click();
	console.log(document.referrer);
// 	 var val = $("#history_back").val();
// 	 if (!val) {
// 		$("#history_back").val("history");
//      	$("#recomm").click();
//      }
});

$(window).scroll(function() {
	//$(window).scrollTop()这个方法是当前滚动条滚动的距离
	//$(window).height()获取当前窗体的高度
	//$(document).height()获取当前文档的高度
	
       var top = document.documentElement.scrollTop || document.body.scrollTop;
       $(".category").css({"margin-top": top + "px"});
	
	var bot = 50; //bot是底部距离的高度
	if (mark &&( bot+$(window).scrollTop()) >= ($(document).height() - $(window).height())) {
		//当底部基本距离+滚动的高度〉=文档的高度-窗体的高度时；
		//我们需要去异步加载数据了
		//alert(currentType);
		switch(currentType){
		case 0:
			loadData("getRecommendNews",0,"recom",10,1);
			break;
		case 1:
			loadData("getNewsByLabel",1,"science",10,1);
			break;
		case 2:
			loadData("getNewsByLabel",2,"hot",10,1);
			break;
		case 3:
			loadData("getNewsByLabel",3,"enjoy",10,1);
			break;
		case 4:
			loadData("getNewsByLabel",4,"money",10,1);
			break;
		case 5:
			loadData("getNewsByLabel",5,"car",10,1);
			break;
		case 6:
			loadData("getNewsByLabel",6,"physical",10,1);
			break;	
		}

	setTimeout(setTure,500); mark = false; 
}

});


</script>
</html>