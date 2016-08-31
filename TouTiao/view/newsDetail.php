<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="description" content="We讯分享">
<title>头条</title>
<link rel="stylesheet" type="text/css" href="http://10.198.19.176:8080/TouTiao/css/style.css" />
<link rel="stylesheet" href="http://10.198.19.176:8080/TouTiao/css/bootstrap.min.css">
<link rel="stylesheet" href="http://10.198.19.176:8080/TouTiao/css/common.css">
<script type="text/javascript" src="http://10.198.19.176:8080/TouTiao/js/jquery.min.js"></script>
<script src="http://10.198.19.176:8080/TouTiao/js/bootstrap.min.js"></script>
<script src="http://10.198.19.176:8080/TouTiao/js/main.js"></script>
<link rel="stylesheet" href="http://10.198.19.176:8080/TouTiao/css/share.min.css">
<script type="text/javascript">

<?php
require_once '../model/common.php';
require_once '../model/constant.php';
require_once '../dao/abstractDao.php';
require_once '../dao/redisDao.php';
require_once '../model/abstractModel.php';
require_once '../model/redisModel.php';
require_once '../model/abstractFactory.php';
require_once '../model/redisFactory.php';

//echo '<meta http-equiv="Access-Control-Allow-Origin" content="*.ttlsa.com">';
ini_set('session.save_handler', 'redis');
ini_set('session.save_path', 'tcp://127.0.0.1:6379');

session_start();
//ini_set('session.gc_maxlifetime', sessionTime);
$news_id=$_GET ["news_id"];
//$userId=secret2string($_COOKIE["userId"]);
$model=RedisFactory::singleton()->createModel();
$userId=secret2string($_COOKIE["userId"]);
$data=$model->getDetailNews($news_id,$userId);
//$data=json_encode($data);
//writeData($data);
//echo $data;
$origin_label_type=$_GET ["label_type"];
$news_from='';
switch ($origin_label_type){
	case 0:
		$news_from='热点';
		break;
	case 1:
		$news_from='国内';
		break;
	case 2:
		$news_from='国际';
		break;
	case 3:
		$news_from='社会';
		break;
	case 4:
		$news_from='财经';
		break;
	case 5:
		$news_from='生活';
		break;
	case 6:
		$news_from='体育';
		break;
	case 7:
		$news_from='娱乐';
		break;
	case 8:
		$news_from='科技';
		break;
	case 9:
		$news_from='推荐';
		break;
}

echo "var news_id=" . $news_id . ";\n";
//echo "var agency_name='" . $data["agency_name"] . "';\n";
echo "var news_data_url='" . $data["news_data"]. "';\n";
?>

$.ajax({
	url: 'http://10.198.19.176:8080/tfs/'+news_data_url,  
	type:'get',
	dataType:'html',
	headers:{
		Accept:"image/webp,image/*,*/*;q=0.8"
	},
	success: function(data){
	data=data.replace(/\">/g,"\"><br>");	
	console.log(data);
	//	console.log(data.replace(/\">/g,"\"><br>"));
		$(".news_content").append(data);
	},  
	error:function(data){
		console.log(data);
			//alert("加载失败，请稍候再试");
		$(".news_content").append("<span class='agency' >新闻内容加载失败...</span>");
	}
		
});
</script>
</head>
<body>
	<?php include("header.php")?>

	<div class="container">

		<div class="col-sm-10 detail-artical">
			<div class="news_content">
				 			<div class="detail_label"> 
				 				<a href="index.php">首页</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span ><?php echo $news_from;?></span>&nbsp;&nbsp;>&nbsp;&nbsp;正文 
				 				<a href="javascript:;" class="report" data-toggle="modal" 
				 					data-target="#myModal">举报</a> 
				 			</div> 
				 			<h2 ><?php echo $data["news_title"];?></h2> 
				 			<div class="agency" ><span ><?php echo$data["agency_name"];?></span>&nbsp;&nbsp;&nbsp;&nbsp;<span ><?php echo$data["news_time"];?></span></div> 


			</div>
		<!--	<div class="recom">
				<a href="javascript:;"> --!>
				<?php 
		//			if($data["isRecomm"]>0){
		//				echo "<img id='reomm-img' class='recom-but' src='http://10.198.19.176:8080/TouTiao/images/good2.png' />";
		//			}else{
		//				echo "<img id='reomm-img' class='recom-but' src='http://10.198.19.176:8080/TouTiao/images/good.png' />";
		//			}
					?>
			<!--	</a>
			</div> --!>
		<div>
				<a href="javascript:;" class="recom col-sm-offset-3" style="color: #333;text-decoration: none;">
				<?php
				if ($data ["isRecomm"] > 0) {
					echo "<img id='reomm-img'  src='http://10.198.19.176:8080/TouTiao/images/good2.png' />";
 				}else{
					echo "<img id='reomm-img'  src='http://10.198.19.176:8080/TouTiao/images/good.png' />";
				}	
 				?>
 				点赞</a>
 				
 				<a href="javascript:;" class="storage col-sm-offset-4" style="color: #333;text-decoration: none;">
				<?php
					if($data["isStorage"]>0){
						echo "<img id='storage-img' src='http://10.198.19.176:8080/TouTiao/images/storage.png' />";
					}else{
						echo "<img id='storage-img' src='http://10.198.19.176:8080/TouTiao/images/unstorage.png' />";
					}
 				?>
 				收藏</a>
			</div>
			<div>
				<p class="bounday"></p>
			</div>
			<div class="bottom">
				<div  class="col-sm-4 social-share"  data-initialized="true" ><span class="share">分享到：</span>
   					<a  target="_blank"  class="social-share-icon icon-weibo" href="#"></a>
    				<a  target="_blank" class="social-share-icon icon-qq" href="#"></a>
    				<a  target="_blank" class="social-share-icon icon-qzone" href="#"></a>
				</div>
			<!--	<span class="col-sm-2 storage">
					<a href="javascript:;">
					<?php 
				//	if($data["isStorage"]>0){
				//		echo "<img id='storage-img' src='http://10.198.19.176:8080/TouTiao/images/storage.png' /></a>";
				//	}else{
				//		echo "<img id='storage-img' src='http://10.198.19.176:8080/TouTiao/images/unstorage.png' /></a>";
				//	}
?><
/a>&nbsp;&nbsp;收�
				</span> --!>

				<!-- 				<span class="share">分享到：<img src="http://10.198.19.176:8080/TouTiao/images/weixin.png" /></span> <span -->
				<!-- 					class="storage"><a href="#0"><img id="storage-img" -->
				<!-- 						src="http://10.198.19.176:8080/TouTiao/images/unstorage.png" /></a>&nbsp;&nbsp;收藏</span> -->
			</div>
		</div>
		<div class="col-sm-1">
			<h4>广告：</h4>
			<img class="ad" src="http://10.198.19.176:8080/TouTiao/images/ad.jpg"
				alt="">
		</div>
	</div>
	<!-- 	举报框 -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog"
		aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="myModalLabel">请描述举报内容：</h4>
				</div>
				<div class="modal-body">
					<textarea class="form-control" id="message-text" rows="4"></textarea>
				</div>
				<div class="modal-footer">
					<button type="button" id="close_report" class="btn btn-default"
						data-dismiss="modal">关闭</button>
					<button type="button" id="submit_report" class="btn btn-primary">提交</button>
				</div>
			</div>
		</div>
	</div>

	<div class="bottom_line">
		<div class='text-muted'>单位名称：信安实习生二组</div>
		<div class='text-muted'>违法信息举报：12306</div>
	</div>
</body>
<script src="http://10.198.19.176:8080/TouTiao/js/share.min.js"></script>
<script type="text/javascript">

	$(".storage").bind('click', function(){
		<?php echo "var isLogin='".$_COOKIE["userName"]."';"?> 
		if(!isLogin){
			$("#loginbut").click();
			return;
		}
		var type=$("#storage-img").attr("src");
		if(type == "http://10.198.19.176:8080/TouTiao/images/storage.png"){
			$("#storage-img").attr("src","http://10.198.19.176:8080/TouTiao/images/unstorage.png");
			$.ajax({
				url: "../room.php",  
				data: {
					'type':'removeStorageNews',
					'news_id':news_id
				},
				type:'post',
				dataType:'json',
				success: function(data){
					console.log(data);
				},  
				error:function(){
						alert("加载失败，请稍候再试");
				}
			});
		}else{
			$("#storage-img").attr("src","http://10.198.19.176:8080/TouTiao/images/storage.png");
			$.ajax({
				url: "../room.php",  
				data: {
					'type':'storageNews',
					'news_id':news_id
				},
				type:'post',
				dataType:'json',
				success: function(data){
					console.log(data);
				},  
				error:function(){
						alert("加载失败，请稍候再试");
				}
			});
		}
		
	}); 
	$(".recom").bind('click', function(){ 
		<?php echo "var isLogin='".$_COOKIE["userName"]."';"?> 
		if(!isLogin){
			$("#loginbut").click();
			return;
		}
		var type=$("#reomm-img").attr("src");
		if(type == "http://10.198.19.176:8080/TouTiao/images/good2.png"){
			$("#reomm-img").attr("src","http://10.198.19.176:8080/TouTiao/images/good.png");
			$.ajax({
				url: "../room.php",  
				data: {
					'type':'removeRecommendNews',
					'news_id':news_id
				},
				type:'post',
				dataType:'json',
				success: function(data){
					console.log(data);
				},  
				error:function(){
						alert("加载失败，请稍候再试");
				}
			});
		}else{
			$("#reomm-img").attr("src","http://10.198.19.176:8080/TouTiao/images/good2.png");
			$.ajax({
				url: "../room.php",  
				data: {
					'type':'recommendNews',
					'news_id':news_id
				},
				type:'post',
				dataType:'json',
				success: function(data){
					console.log(data);
				},  
				error:function(){
						alert("加载失败，请稍候再试");
				}
			});
		}
	}); 
	$("#submit_report").bind('click', function(){ 
			$.ajax({
				url: "../room.php",  
				data: {
					'type':'reportNews',
					'news_id':news_id,
					'describe':function(){
						return $("#message-text").val();
					}
				},
				type:'post',
				dataType:'json',
				success: function(data){
					console.log(data);
					$("#close_report").click();
					$("#message-text").val('');
				},  
				error:function(){
					alert("加载失败，请稍候再试");
					$("#close_report").click();
				}
			});
	}); 
</script>

</html>
