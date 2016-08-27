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

<script type="text/javascript">
<?php 
echo "var search_val='".$_GET["search_val"]."';";
?>
//$("#search_input").val(search_val);

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
		'type':'searchValCount',
		'search_val':search_val
	},
	type:'post',
	dataType:'text',
	success: function(data){
		//alert(data);
		var vars="<div class='content'></div><div class='tcdPageCode news-item'></div>";
 			$("#tmp").html(vars);
 			if(data>0)$.ajax({
				url: "../room.php",  
				data: {
					'type':'searchVal',
					'num':10,
					'offset':0,
					'search_val':search_val,
					'pageCount':data
				},
				type:'post',
				dataType:'json',
				success: function(data){
					//console.log(data);
					var vals="";
		 			for(var i=0;i<data.length;i++){
		 				//console.log(date('Y-m-d H:i:s')-data[i].news_time);
		 				console.log(data[i]);
						if(data[i].news_imgs==''||data[i].news_imgs.length==0){
							vals+=loadDataNoPic(data[i]);
						}else {
							var len=data[i].news_imgs.split(",").length;
							if(len==1){
								vals+=loadDataOnePic(data[i]);
							}else{
								vals+=loadDataThreePic(data[i]);
							}	
						}
		 			}
		 			$(".content").html(vals);
		 		//	$("#tmp").html(vals);
				},
		 		error:function(){
	 				alert("加载失败，请稍候再试");
	 		}
    	});
 			
 			$(".tcdPageCode").createPage({
			    pageCount:data,
			    current:1,
			    backFn:function(p){
			        //单机回调方法
					var num=10;
					var offset=(p-1)*num;
					//console.log("p:"+p);
					//console.log("offset:"+offset);
					$(window).scrollTop(0);
			    	$.ajax({
 						url: "../room.php",  
 						data: {
 							'type':'searchVal',
 							'num':num,
 							'offset':offset,
 							'search_val':search_val,
 							'pageCount':data
 						},
 						type:'post',
 						dataType:'json',
 						success: function(data){
 							console.log(data);
 							if(data==null)return;
 							var vals="";
 				 			for(var i=0;i<data.length;i++){
 				 				//console.log(date('Y-m-d H:i:s')-data[i].news_time);
 				 				if(data[i].news_imgs==''||data[i].news_imgs.length==0){
 									vals+=loadDataNoPic(data[i]);
 								}else {
 									var len=data[i].news_imgs.split(",").length;
 									if(len==1){
 										vals+=loadDataOnePic(data[i]);
 									}else{
 										vals+=loadDataThreePic(data[i]);
 									}	
 								}
 				 			}
 				 			$(".content").html(vals);
 				 		//	$("#tmp").html(vals);
 						},
 				 		error:function(){
 			 				alert("加载失败，请稍候再试");
 			 		}
			    	});
			    }
			});
 			
 		},  
 		error:function(){
 				alert("加载失败，请稍候再试");
 		}
 			
 	});

function loadDataNoPic(data){
	return "<div class='clearfix news-item '><div><div class='title_box'><a target='_blank' href='newsDetail.php?news_id="+data.news_id+"&label_type=0'>"+data.news_title+"</a></div><div class='abstract'>"+
			"<a target='_blank' target='_blank' href='newsDetail.php?news_id="+data.news_id+"&label_type=0'>"+data.news_abstract+"</a></div><div class='timer small'><span  class='text-muted'>"+data.agency_name+"</span> &middot;"+
			" <span class='text-muted'>"+transferTime(data.news_time)+"</span></div></div></div>";
}

function loadDataOnePic(data){
    return "<div class='clearfix news-item '><div class='pull-left'><a target='_blank' target='_blank' target='_blank' href='newsDetail.php?news_id="+data.news_id+"&label_type=0'><img class='feedimg' src='htpp://10.198.19.176:8080/v1/    tfs/"+data.news_imgs+"' alt='图片'>" +279             "</a></div><div class='title_box'><a target='_blank' href='newsDetail.php?news_id="+data.news_id+"&label_type=0'>"+data.news_title+"</a></div><div class='abstract'>"+
            "<a target='_blank' href='newsDetail.php?news_id="+data.news_id+"&label_type=0'>"+data.news_abstract+"</a></div><div class='timer small'><span  class='text-muted'>"+data.agency_name+"</    span> &middot;"+281             " <span class='text-muted'>"+transferTime(data.news_time)+"</span></div></div></div>";
}
function loadDataThreePic(data){
    var imgs = data.news_imgs.split(';');
    return "<div class='clearfix news-item '><div><div class='title_box'><a target='_blank' href='newsDetail.php?news_id="+data.news_id+"&label_type=0'>"+data.news_title+"</a></div><div class='imag    e-list clearfix'>"+
                    "<a target='_blank' href='newsDetail.php?news_id="+data.news_id+"&label_type=0'>"+
                        "<div class='night-image'"+
                            "style='background-image: url(htpp://10.198.19.176:8080/v1/tfs/"+imgs[0]+")'></div>"+
                    "</a> <a target='_blank' href='newsDetail.php?news_id="+data.news_id+"&label_type=0'>"+
                        "<div class='night-image'"+
                            "style='background-image: url(htpp://10.198.19.176:8080/v1/tfs/"+imgs[1]+")'></div>"+
                    "</a> <a target='_blank' href='newsDetail.php?news_id="+data.news_id+"&label_type=0'>"+
                        "<div class='night-image'"+                             
                        "style='background-image: url(htpp://10.198.19.176:8080/v1/tfs/"+imgs[2]+")'></div>"+
                    "</a></div><div class='timer small'><span  class='text-muted'>"+data.agency_name+"</span> &middot;"+
            " <span class='text-muted'>"+transferTime(data.news_time)+"</span></div></div></div>";
}

</script>
</head>
<body>
<?php include("header.php")?>

    <div class="container" id="con">
		<div class="col-sm-offset-2 col-sm-10" id="tmp">
		</div>
	</div>
</body>
<script type="text/javascript">
$("#search_input").val(search_val);
</script>
</html>