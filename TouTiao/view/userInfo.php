<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>头条</title>

<!-- <link rel="stylesheet" -->
<!-- 	href="http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css"> -->
<link rel="stylesheet"  href="../css/bootstrap.min.css" />	
<script src="../js/jquery.min.js"></script>
<script src="../js/jquery.validate.js"></script>
<script src="../js/jquery.page.js"></script>
<script src="../js/main.js"></script>
<script src="../js/bootstrap.min.js"></script>
<link rel="stylesheet" href="../css/common.css">
<link rel="stylesheet"  href="../css/style.css" />

</head>
<body>
<?php include("header.php")?>

<div class="container">
		<div class="col-sm-2 text-center">
			<ul class="list-unstyled category">
				<li><a id="user_info" href="#0">个人信息</a></li>
				<li><a href="#0" id="mystorage">我的收藏</a></li>
			</ul>
		</div>
		<div class="col-sm-9" id="user_content">
		</div>
		
	</div>

<link rel="stylesheet" href="../css/bootstrap-datetimepicker.css">
<script src="../js/moment.min.js"></script>
<script src="../js/bootstrap-datetimepicker.min.js"></script>
<script src="../js/zh-cn.js"></script>
<script >
$(document).ready(function () {
    $("#user_info").click();
});

$("#user_info").on('click', function(event){
	<?php
		echo "var account=" . secret2string($_COOKIE['userAccount']) . ";";
		echo "var isLogin='" . $_COOKIE ["userName"] . "';";
	?>
	$("#mystorage").removeClass("bg-click");
	$("#user_info").addClass("bg-click");
	if(isLogin.length==0)return;
	 $.ajax({
	    	url: "../room.php",  
	    	data: {
	    		'type':'getUserById',
	    	},
	    	type:'post',
	    	dataType:'json',
	    	success: function(data){
	    	   console.log(data);
	    	   var male=(data.user_gender==1)?"checked":"";
	    	   var female=(data.user_gender==0)?"checked":"";
	    	   var sport=((data.user_label % 10)==1)?"checked":"";
	    	   var music=((parseInt(data.user_label / 10) % 10)==1)?"checked":"";
	    	   var group=((parseInt(data.user_label / 100)% 10)==1)?"checked":"";
	    	   var money=((parseInt(data.user_label / 1000)% 10)==1)?"checked":"";
	    	   var vals='';
	    	   vals+="<form class='form-horizontal login-form' id='register_form' method='post' >"+
	    	    		"<div class='form-group'>"+
	    	    			"<label for='ff-user' class='col-md-3 control-label'>账号</label>"+
	    	    			"<div class='col-md-4'>"+
	    	    				"<input type='text' class='form-control' id='user_account' "+
	    	    					"name='user_account' value='"+account+"' readonly='readonly'>"+
	    	    			"</div>"+
	    	    			"<p class='col-md-4 form-control-static text-danger'></p>"+
	    	    		"</div>"+
	    	    		"<div class='form-group'>"+
	    	    		"<label for='ff-user' class='col-md-3 control-label'>用户名</label>"+
	    	    		"<div class='col-md-4'>"+
	    	    			"<input type='text' class='form-control' id='user_name' "+
	    	    				"name='user_name' value='"+data.user_name+"'>"+
	    	    		"</div>"+
	    	    		"<p class='col-md-4 form-control-static text-danger'></p>"+
	    	    	"</div>"+
	    	    		"<div class='form-group'>"+
	    	    			"<label for='ff-birthday' class='col-md-3 control-label'>生日</label>"+
	    	    			"<div class='col-md-4'>"+
	    	    				"<div class='input-group date' id='datetimepicker1' >"+
	    	    					"<input type='text' class='form-control' id='birthday' "+
	    	    						"name='birthday' value='"+data.user_birthday+"'/> <span class='input-group-addon'> <span "+
	    	    						"class='glyphicon glyphicon-calendar'></span>"+
	    	    					"</span>"+
	    	    				"</div>"+
	    	    			"</div>"+
	    	    			"<p class='col-md-4 form-control-static text-danger'></p>"+
	    	    		"</div>"+
	    	    		"<div class='form-group'>"+
	    	    			"<label for='ff-repsw' class='col-md-3 control-label'>性别</label> <label "+
	    	    				"class='col-md-1 radio-inline radio-type'> <input type='radio' "+
	    	    				" name='gender' value='1' "+male+"> 男 "+
	    	    			"</label> <label class='col-md-1 radio-inline '> <input type='radio' "+
	    	    			"	 name='gender' value='0' "+female+"> 女"+
	    	    			"</label>"+
	    	    			"<p class='col-md-4 form-control-static text-danger'></p>"+
	    	    	"	</div>"+
	    	    		"<div class='form-group'>"+
	    	    			"<label for='ff-repsw' class='col-md-3 control-label'>爱好</label> <label "+
	    	    				"class='checkbox-inline col-md-1 checkbox-type'> <input "+
	    	    				"type='checkbox' name='interest[]'  value='1' "+sport+"> 运动"+
	    	    			"</label> <label class='checkbox-inline col-md-1'> <input "+
	    	    			"	type='checkbox' name='interest[]'  value='10' "+music+"> 音乐"+
	    	    			"</label> <label class='checkbox-inline col-md-1'> <input "+
	    	    			"	type='checkbox' name='interest[]'  value='100' "+group+"> 军事"+
	    	    			"</label> <label class='checkbox-inline col-md-1'> <input "+
	    	    				"type='checkbox' name='interest[]'  value='1000' "+money+"> 财经"+
	    	    			"</label>"+
	    	    			"<p class='col-md-4 form-control-static text-danger'></p>"+
	    	    		"</div>"+
	    	    		"<input type='hidden' name='type' value='updateUser'>"+
	    	    		"<div class='form-group'>"+
	    	    			"<div class='col-md-offset-3 col-md-4'>"+
	    	    			"	<button id='update_user' class='btn btn-primary'>修改</button>"+
	    	    			"</div>"+
	    	    		"</div>"+
	    	    	"</form>";
	    	    		$("#user_content").html(vals);
	    	    		 $('#datetimepicker1').datetimepicker({
	    	    		        locale: 'zh-cn',
	    	    		        format: 'YYYY-MM-DD'
	    	    		    });
	    	    		 $("#update_user").on('click', function(event){
	    	     	    	//$('#register_form').submit();
	    	     	    	//alert("D");
	    	     	    	var formParam = $("#register_form").serialize();
	    	     	    	$.ajax({
	    	     	    		url: "../room.php",  
	    	     	    		data: formParam,
	    	     	    		type:'post',
	    	     	    		dataType:'text',
	    	     	    		success: function(data){
	    	     	        		if(data ==1){
	    	     	        			alert("修改成功");
	    	     	        			//$('#register_form')[0].reset();
	    	     	        		}else{
	    	     	        			alert("修改失败，请稍候再试");
	    	     	        		}
	    	     	        	},  
	    	     	        	error:function(){
	    	     	        			alert("注册失败，请稍候再试");
	    	     	        	}
	    	     	        		
	    	     	        });
	    	     	    });

	      	     	    //加载js
	    	    		 jQuery.validator
	    	 			.addMethod("verify_length",
	    	 					function(value, element) {
	    	 						return this.optional(element)
	    	 								|| ($.trim(value).length >= 3 && $.trim(value).length <= 15);
	    	 					}, "请输入3-15个字符");
	    	 	jQuery.validator
	    	 	.addMethod("birthday_verify",
	    	 			function(value, element) {
	    	 				var birth_verify= /^((19\d{2})|(20\d{2}))-((0[1-9])|(1[0-2]))-((0[1-9])|([1-2][0-9])|(3([0|1])))$/;
	    	 				return this.optional(element)
	    	 						|| (birth_verify.test(value));
	    	 			}, "请正确输入生日格式YYYY-MM-DD");
	    	 	
	    	 	$("#register_form").validate({
	    	 		errorPlacement : function(error, element) {
	    	 			var error_td = element.parent('div').parent('div');
	    	 			error_td.find('p').html(error);
	    	 		},
	    	 		submitHandler : function(form) {
	    	 		},
	    	 		rules : {
	    	 			user_name : {
	    	 				required : true,
	    	 				verify_length : true
	    	 			},
	    	 			birthday : {
	    	 				required : true,
	    	 				birthday_verify:true 
	    	 			}
	    	 		},
	    	 		messages : {
	    	 			user_name : {
	    	 				required : '用户名不能为空',
	    	 				verify_length:'用户名必须在3-15个字符之间'
	    	 			},
	    	 			birthday : {
	    	 				required : '请填写生日',
	    	 				birthday_verify : '生日格式为YYYY-MM-DD'
	    	 			}

	    	 		}
	    	 	});
	    	 	
	    	    	},  
	    	    	error:function(){
	    	    			alert("加载失败，请稍候再试");
	    	    	}
	    	    });
});

$("#mystorage").on('click', function(event){
	$("#user_info").removeClass("bg-click");
	$("#mystorage").addClass("bg-click");
	<?php echo "var isLogin='" . $_COOKIE ["userName"] . "';";?>
	if(isLogin.length==0)return;
 	$.ajax({
 		url: "../room.php",  
 		data: {
 			'type':'getStoragePageCount'
 		},
 		type:'post',
 		dataType:'text',
 		success: function(data){
 			//console.log(data);
 			var vars="<div class='content'></div><div class='tcdPageCode news-item'></div>";
 			$("#user_content").html(vars);
 			$.ajax({
					url: "../room.php",  
					data: {
						'type':'getStorageById',
						'num':10,
						'offset':0
					},
					type:'post',
					dataType:'json',
					success: function(data){
						//console.log(data);
						var vals="";
			 			for(var i=0;i<data.length;i++){
			 				//console.log(date('Y-m-d H:i:s')-data[i].news_time);
			 				//console.log(data[i].news_time);
			 				console.log(data[i]);
								if(data[i].news_imgs.length==0){
									vals+=loadDataNoPic(data[i]);
								}else {
									var len=data[i].news_imgs.split(";").length;
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
					console.log("p:"+p);
					console.log("offset:"+offset);
			    	$.ajax({
 						url: "../room.php",  
 						data: {
 							'type':'getStorageById',
 							'num':num,
 							'offset':offset
 						},
 						type:'post',
 						dataType:'json',
 						success: function(data){
 							//console.log(data);
 							var vals="";
 				 			for(var i=0;i<data.length;i++){
 				 				//console.log(date('Y-m-d H:i:s')-data[i].news_time);
 				 				console.log(data[i]);
 								if(data[i].news_imgs.length==0){
 									vals+=loadDataNoPic(data[i]);
 								}else {
 									var len=data[i].news_imgs.split(";").length;
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
</script>

</body>

</html>