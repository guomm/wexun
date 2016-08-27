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
require_once '../dao/redisDao.php';
require_once '../model/abstractModel.php';
require_once '../model/redisModel.php';
require_once '../model/abstractFactory.php';
require_once '../model/redisFactory.php';

//echo '<meta http-equiv="Access-Control-Allow-Origin" content="*.ttlsa.com">';
session_start();
ini_set('session.gc_maxlifetime', sessionTime);
$news_id=$_GET ["news_id"];
$userId=secret2string($_COOKIE["userId"]);
$model=RedisFactory::singleton()->createModel();
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
//echo "var origin_label_type=" . $origin_label_type . ";";
//echo "var news_title='" . $data["news_title"] . "';\n";
//echo "var news_time='" . $data["news_time"] . "';\n";
//echo "var agency_name='" . $data["agency_name"] . "';\n";
echo "var news_data_url='" . $data["news_data"]. "';\n";
//$url="
//echo "var news_from=getLabelByType(origin_label_type);";
//echo "\$('#news_title').html(news_title);\n";
//echo "\$('#agency_name').html(agency_name);\n";
//echo "\$('#news_time').html(news_time);\n";
//echo "\$('#news_from').html(news_from);\n";
// if($data["isStorage"]>0){
// 	echo "\$('#storage-img').attr('src','..\/images\/storage.png');\n";
// }
// if($data["isRecomm"]>0){
// 	echo "\$('#reomm-img').attr('src','..\/images\/good2.png');\n";
//}

//$url= 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
//$url='http://10.198.19.176:8080/v1/tfs/T16RdTByJT1RCvBVdK';
//writeData($url);
?>
// var easyUTF8 = function(gbk){  
//     if(!gbk){return '';}  
//     var utf8 = [];  
//     for(var i=0;i<gbk.length;i++){  
//         var s_str = gbk.charAt(i);  
//         if(!(/^%u/i.test(escape(s_str)))){utf8.push(s_str);continue;}  
//         var s_char = gbk.charCodeAt(i);  
//         var b_char = s_char.toString(2).split('');  
//         var c_char = (b_char.length==15)?[0].concat(b_char):b_char;  
//         var a_b =[];  
//         a_b[0] = '1110'+c_char.splice(0,4).join('');  
//         a_b[1] = '10'+c_char.splice(0,6).join('');  
//         a_b[2] = '10'+c_char.splice(0,6).join('');  
//         for(var n=0;n<a_b.length;n++){  
//             utf8.push('%'+parseInt(a_b[n],2).toString(16).toUpperCase());  
//         }  
//     }  
//     return utf8.join('');  
// };  
$.ajax({
	url: news_data_url,  
	//url:'http://10.198.19.176:8080/v1/tfs/T1AaETBKdQ1RCvBVdK'
	type:'get',
	dataType:'html',
// 	headers:{
// 		Accept: "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8"
// 	},
	//contentType: "application/x-www-form-urlencoded; charset=utf-8", 
	success: function(data){
		console.log(data);
		data=data.replace(/\">/g,"\"><br>");
		//console.log("/\"\>/g","/><br>");
		//console.log(encodeURI(data));
// 		var vals="<div class='detail_label'><a href='index.php'>首页</a>&nbsp;&nbsp;>&nbsp;&nbsp;"+getLabelByType(origin_label_type)+"&nbsp;&nbsp;>&nbsp;&nbsp;正文"+
// 					"<a href='#0' class='report' data-toggle='modal'data-target='#myModal'>举报</a></div>";
// 		vals+="<h2>"+data.news_title+"</h2>";
// 		vals+="<div class='agency'>"+data.agency_name+"&nbsp;&nbsp;&nbsp;&nbsp;"+data.news_time+"</div>";
// 		vals+=data.news_data;
		$(".news_content").append(data);
	},  
	error:function(data){
		console.log(data);
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
				 				<a href="#0" class="report" data-toggle="modal" 
				 					data-target="#myModal">举报</a> 
				 			</div> 
				 			<h2 ><?php echo $data["news_title"];?></h2> 
				 			<div class="agency" ><span ><?php echo$data["agency_name"];?></span>&nbsp;&nbsp;&nbsp;&nbsp;<span ><?php echo$data["news_time"];?></span></div> 
				<!-- 			<p>波罗的海沿岸的拉脱维亚，是一个美丽而又浪漫的国度，虽然该国还未对中国免签证，不过还是吸引了不少中国人想办法去那里旅游。而这个美丽的国度除了美景外，最吸引人的地方是这里也是公认的美女最多的国家之一，每年都吸引了不少外国游客前去拉脱维亚猎艳。</p> -->

				<!-- 			<p>除此之外，这里的男女比例非常悬殊，使该国成为了世界上最缺男人的国度。大街上能经常碰到天使脸蛋魔鬼身材的单身美女，她们吸引着来自世界各国的男游客。很多人都以为世界上最缺少男人的国家是俄罗斯，其实不然，它是俄罗斯的邻国拉脱维亚。</p> -->

				<!-- 			<p> -->
				<!-- 				<img src="http://p3.pstatp.com/large/a670005d875a2b8f7f3" alt=""> -->
				<!-- 			</p> -->
				<!-- 			<p>据拉脱维亚中央统计局统计，拉脱维亚男女比例相差18%，差别居世界第一。这个波罗的海沿岸的小国的水土和气候可能更适合于女性胎儿和婴儿的存活和成长。 -->
				<!-- 			</p> -->

				<!-- 			<p>拉脱维亚和俄罗斯一样，拉脱维亚男女比率失调的原因也是残酷的二次大战，由于波罗的海沿岸是前苏联范围内德军入侵最早、撤退最晚的战区，所以拉脱维亚的男女比率比其它前苏联加盟共和国都要悬殊。拉脱维亚女多男少造成了这里的年轻女子最大的烦恼就是婚姻大事。</p> -->
			</div>
			<div class="recom">
				<a href="#0">
				<?php 
					if($data["isRecomm"]>0){
						echo "<img id='reomm-img' class='recom-but' src='../images/good2.png' />";
					}else{
						echo "<img id='reomm-img' class='recom-but' src='../images/good.png' />";
					}
					?>
				</a>
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
				<span class="col-sm-2 storage">
					<a href="#0">
					<?php 
					if($data["isStorage"]>0){
						echo "<img id='storage-img' src='../images/storage.png' /></a>";
					}else{
						echo "<img id='storage-img' src='../images/unstorage.png' /></a>";
					}
					?></a>&nbsp;&nbsp;收藏
				</span>
				<!-- 				<span class="share">分享到：<img src="../images/weixin.png" /></span> <span -->
				<!-- 					class="storage"><a href="#0"><img id="storage-img" -->
				<!-- 						src="../images/unstorage.png" /></a>&nbsp;&nbsp;收藏</span> -->
			</div>
		</div>
		<div class="col-sm-1">
			<h4>广告：</h4>
			<img class="ad" src="../images/ad.jpg"
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
<script src="../js/share.min.js"></script>
<script type="text/javascript">

	$("#storage-img").bind('click', function(){
		<?php echo "var isLogin='".$_COOKIE["userName"]."';"?> 
		if(!isLogin){
			$("#loginbut").click();
			return;
		}
		var type=$("#storage-img").attr("src");
		if(type == "../images/storage.png"){
			$("#storage-img").attr("src","../images/unstorage.png");
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
			$("#storage-img").attr("src","../images/storage.png");
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
	$("#reomm-img").bind('click', function(){ 
		<?php echo "var isLogin='".$_COOKIE["userName"]."';"?> 
		if(!isLogin){
			$("#loginbut").click();
			return;
		}
		var type=$("#reomm-img").attr("src");
		if(type == "../images/good2.png"){
			$("#reomm-img").attr("src","../images/good.png");
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
			$("#reomm-img").attr("src","../images/good2.png");
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