<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>头条</title>
<link rel="stylesheet" type="text/css" href="../css/style.css" />
<link rel="stylesheet"
	href="http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="../css/common.css">
<script src="../js/bootstrap.min.js"></script>
<script type="text/javascript" src="../js/jquery.min.js"></script>
<script src="../js/jquery.page.js"></script>
<style>

a {
	text-decoration: none;
}

a:hover {
	text-decoration: none;
}

.tcdPageCode {
	padding: 15px 20px;
	text-align: left;
	color: #ccc;
}

.tcdPageCode a {
	display: inline-block;
	color: #428bca;
	display: inline-block;
	height: 25px;
	line-height: 25px;
	padding: 0 10px;
	border: 1px solid #ddd;
	margin: 0 2px;
	border-radius: 4px;
	vertical-align: middle;
	color:#000000;
}

.tcdPageCode a:hover {
	text-decoration: none;
	border: 1px solid #428bca;
}

.tcdPageCode span.current {
	display: inline-block;
	height: 25px;
	line-height: 25px;
	padding: 0 10px;
	margin: 0 2px;
	color: #fff;
	background-color: #428bca;
	border: 1px solid #428bca;
	border-radius: 4px;
	vertical-align: middle;
}

.tcdPageCode span.disabled {
	display: inline-block;
	height: 25px;
	line-height: 25px;
	padding: 0 10px;
	margin: 0 2px;
	color: #bfbfbf;
	background: #f2f2f2;
	border: 1px solid #bfbfbf;
	border-radius: 4px;
	vertical-align: middle;
}
</style>

</head>
<body>
<?php include("header.php")?>

<div class="container">
		<div class="col-sm-2 text-center">
			<ul class="list-unstyled category">
				<li><a href="userInfo.php">个人信息</a></li>
				<li><a class="bg-click" href="myStorage.php">我的收藏</a></li>
			</ul>
		</div>
		<div class="col-sm-10">
			<!-- 左侧一张图 -->
			<div class="clearfix news-item">
				<div class="pull-left">
					<a href=""><img class="feedimg"
						src="http://p1.pstatp.com/list/97e000a81c6742abc11" alt="图片"></a>
				</div>
				<div>
					<div class="title_box">
						<a href="">田晓菲：《金瓶梅》让我们懂得理解和慈悲</a>
					</div>
					<div class="abstract">
						<a href="">文/田晓菲八岁那一年，我第一次读《红楼梦》。后来，儿乎每隔一两年就会重读一遍，每一遍都发现一些新的东西。十九岁那年，由于个人生活经历与阅读之间某种奇妙的接轨，我成为彻底的“红迷”。在这期间，我曾经尝试了数次，却始终没有耐心阅读《金瓶梅》。</a>
					</div>
					<div class="timer small">
						<a href="" class="assert_link">庖丁技术</a> &middot; <a href=""
							class="assert_link">123评论</a> &middot; <span class="text-muted">半个小时前</span>
					</div>
				</div>
			</div>


			<!-- 无图 -->
			<div class="clearfix news-item">
				<div>
					<div class="title_box">
						<a href="">田晓菲：《金瓶梅》让我们懂得理解和慈悲</a>
					</div>
					<div class="abstract">
						<a href="">文/田晓菲八岁那一年，我第一次读《红楼梦》。后来，儿乎每隔一两年就会重读一遍，每一遍都发现一些新的东西。十九岁那年，由于个人生活经历与阅读之间某种奇妙的接轨，我成为彻底的“红迷”。在这期间，我曾经尝试了数次，却始终没有耐心阅读《金瓶梅》。</a>
					</div>
					<div class="timer small">
						<a href="" class="assert_link">庖丁技术</a> &middot; <a href=""
							class="assert_link">123评论</a> &middot; <span class="text-muted">半个小时前</span>
					</div>
				</div>
			</div>

			<!-- 三张图片 -->
			<div class="clearfix news-item">
				<div>
					<div class="title_box">
						<a href="">这个老革命不简单：跟印度干仗 还扳倒了个省级高官！</a>
					</div>
					<div class="image-list clearfix">
						<a href="">
							<div class="night-image"
								style="background-image: url(http://p3.pstatp.com/list/194x108/97d00106e2ff6e990b1)"></div>
						</a> <a href="">
							<div class="night-image"
								style="background-image: url(http://p3.pstatp.com/list/194x108/97e0010736e335da689)"></div>
						</a> <a href="">
							<div class="night-image"
								style="background-image: url(http://p3.pstatp.com/list/194x108/78f0013f25911588dc6)"></div>
						</a>
					</div>
					<div class="timer small">
						<a href="" class="assert_link">庖丁技术</a> &middot; <a href=""
							class="assert_link">123评论</a> &middot; <span class="text-muted">半个小时前</span>
					</div>
				</div>
			</div>

			<div class="tcdPageCode news-item"></div>
		</div>
	</div>

</body>
<script type="text/javascript">
$(".tcdPageCode").createPage({
    pageCount:60,
    current:1,
    backFn:function(p){
        //单机回调方法
    	console.log(p);
    }
});
</script>
</html>