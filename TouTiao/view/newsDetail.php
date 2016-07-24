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

</head>
<body>
	<?php include("header.php")?>

	<div class="container">

		<div class="col-sm-10">
			<div class="detail_label">
				<a href="#0">首页</a>&nbsp;&nbsp;>&nbsp;&nbsp;社会&nbsp;&nbsp;>&nbsp;&nbsp;正文
				<a href="#0" class="report" data-toggle="modal" data-target="#myModal">举报</a>
			</div>
			<h2>世界上最缺男人的国家：十女配一夫</h2>
			<div class="agency">光明日报&nbsp;&nbsp;&nbsp;&nbsp;2016-07-21 07:15</div>
			<p>波罗的海沿岸的拉脱维亚，是一个美丽而又浪漫的国度，虽然该国还未对中国免签证，不过还是吸引了不少中国人想办法去那里旅游。而这个美丽的国度除了美景外，最吸引人的地方是这里也是公认的美女最多的国家之一，每年都吸引了不少外国游客前去拉脱维亚猎艳。</p>

			<p>除此之外，这里的男女比例非常悬殊，使该国成为了世界上最缺男人的国度。大街上能经常碰到天使脸蛋魔鬼身材的单身美女，她们吸引着来自世界各国的男游客。很多人都以为世界上最缺少男人的国家是俄罗斯，其实不然，它是俄罗斯的邻国拉脱维亚。</p>

			<p>
				<img src="http://p3.pstatp.com/large/a670005d875a2b8f7f3" alt="">
			</p>
			<p>据拉脱维亚中央统计局统计，拉脱维亚男女比例相差18%，差别居世界第一。这个波罗的海沿岸的小国的水土和气候可能更适合于女性胎儿和婴儿的存活和成长。
			</p>

			<p>拉脱维亚和俄罗斯一样，拉脱维亚男女比率失调的原因也是残酷的二次大战，由于波罗的海沿岸是前苏联范围内德军入侵最早、撤退最晚的战区，所以拉脱维亚的男女比率比其它前苏联加盟共和国都要悬殊。拉脱维亚女多男少造成了这里的年轻女子最大的烦恼就是婚姻大事。</p>

			<div class="recom">
				<a href="#0"><img id="reomm-img" class="recom-but"
					src="../images/good.png" /></a>
			</div>
			<div>
				<p class="bounday"></p>
			</div>
			<div class="bottom">
				<span class="share">分享到：<img src="../images/weixin.png" /></span> <span
					class="storage"><a href="#0"><img id="storage-img"
						src="../images/unstorage.png" /></a>&nbsp;&nbsp;收藏</span>
			</div>
		</div>
		<div class="col-sm-1">
			<h4>广告：</h4>
			<img class="ad" src="http://p3.pstatp.com/large/a670005d875a2b8f7f3"
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
					<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
					<button type="button" class="btn btn-primary">提交</button>
				</div>
			</div>
		</div>
	</div>

</body>
<script type="text/javascript">
	$("#storage-img").bind('click', function(){ 
		var type=$("#storage-img").attr("src");
		if(type == "../images/storage.png"){
			$("#storage-img").attr("src","../images/unstorage.png");
		}else{
			$("#storage-img").attr("src","../images/storage.png");
		}
		
	}); 
	$("#reomm-img").bind('click', function(){ 
		var type=$("#reomm-img").attr("src");
		if(type == "../images/good2.png"){
			$("#reomm-img").attr("src","../images/good.png");
		}else{
			$("#reomm-img").attr("src","../images/good2.png");
		}
	}); 
</script>
</html>