<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>头条</title>
<!--<link rel="stylesheet" href="css/bootstrap.min.css">-->
<link rel="stylesheet"
	href="../css/bootstrap.min.css">
<script src="../js/jquery.min.js"></script>
<script src="../js/jquery.validate.js"></script>
<script src="../js/register.js"></script>
<link rel="stylesheet" href="../css/common.css">
<!-- <link href="../css/index.css" rel="stylesheet"> -->
</head>
<body>
	<div class="header-reg">
		<div class="col-sm-2 header-text-reg">
			<h1 class="logo">We讯</h1>
		</div>
		<div class="col-md-6 header-text-reg">
			<label>注册界面</label>
		</div>
		<div class="col-md-2 header-text-reg">
			<a href="index.php">首页</a>
		</div>
	</div>

	<div class="container">

		<form class="form-horizontal login-form" id="register_form"
			method="post" action="../room.php">
			<div class="form-group">
				<label for="ff-user" class="col-md-3 control-label">账号</label>
				<div class="col-md-4">
					<input type="text" class="form-control" id="user_account"
						name="user_account" placeholder="请输入3-15位的帐号，只能为数字和字母">
				</div>
				<p class="col-md-4 form-control-static text-danger"></p>
			</div>

			<div class="form-group">
				<label for="ff-user" class="col-md-3 control-label">用户名</label>
				<div class="col-md-4">
					<input type="text" class="form-control" id="user_name"
						name="user_name" placeholder="请输入3-15位的用户名">
				</div>
				<p class="col-md-4 form-control-static text-danger"></p>
			</div>

			<div class="form-group">
				<label for="ff-psw" class="col-md-3 control-label">密码</label>
				<div class="col-md-4">
					<input type="password" class="form-control" id="password"
						placeholder="请输入3-15位的密码" name="password">
				</div>
				<p class="col-md-4 form-control-static text-danger"></p>
			</div>

			<div class="form-group">
				<label for="ff-repsw" class="col-md-3 control-label">再次输入密码</label>
				<div class="col-md-4">
					<input type="password" class="form-control" id="password_confirm"
						name="password_confirm">
				</div>
				<p class="col-md-4 form-control-static text-danger"></p>
			</div>

			<div class="form-group">
				<label for="ff-birthday" class="col-md-3 control-label">生日</label>
				<div class="col-md-4">
					<!--<input type="text" class="form-control" id="ff-birthday" name="birthday" placeholder="">-->

					<div class='input-group date' id='datetimepicker1'>
						<input type='text' class="form-control" id="birthday"
							name="birthday" /> <span class="input-group-addon"> <span
							class="glyphicon glyphicon-calendar"></span>
						</span>
					</div>

				</div>
				<p class="col-md-4 form-control-static text-danger"></p>
			</div>

			<div class="form-group">
				<label for="ff-repsw" class="col-md-3 control-label">性别</label> <label
					class="col-md-1 radio-inline radio-type"> <input type="radio"
					value="1" name="gender" checked> 男
				</label> <label class="col-md-1 radio-inline "> <input type="radio"
					name="gender" value="0"> 女
				</label>
				<p class="col-md-4 form-control-static text-danger"></p>
			</div>


			<div class="form-group">
				<label for="ff-repsw" class="col-md-3 control-label">爱好</label> <label
					class="checkbox-inline col-md-1 checkbox-type"> <input
					type="checkbox" name="interest[]" value="1"> 运动
				</label> <label class="checkbox-inline col-md-1"> <input
					type="checkbox" name="interest[]" value="10"> 音乐
				</label> <label class="checkbox-inline col-md-1"> <input
					type="checkbox" name="interest[]" value="100"> 军事
				</label> <label class="checkbox-inline col-md-1"> <input
					type="checkbox" name="interest[]" value="1000"> 财经
				</label>
				<p class="col-md-4 form-control-static text-danger"></p>
			</div>
			<input type="hidden" name="type" value="register">

			<div class="form-group">
				<div class="col-md-offset-3 col-md-4">
					<button class="btn btn-primary" id="register">注册</button>

				</div>
			</div>
		</form>
	</div>


	<link rel="stylesheet" href="../css/bootstrap-datetimepicker.css">
	<script src="../js/moment.min.js"></script>
	<script src="../js/bootstrap-datetimepicker.min.js"></script>
	<script src="../js/zh-cn.js"></script>
	<script>
    $(document).ready(function () {
        $('#datetimepicker1').datetimepicker({
            locale: 'zh-cn',
            format: 'YYYY-MM-DD'
        });

        $('#register').on('click', function(event){
        	//$('#register_form').submit();
        	var formParam = $("#register_form").serialize();
        	$.ajax({
        		url: "../room.php",  
        		data: formParam,
        		type:'post',
        		dataType:'text',
        		success: function(data){
            		if(data ==1){
            			alert("注册成功");
            			$('#register_form')[0].reset();
            		}else{
            			alert("注册失败，请稍候再试");
            		}
            	},  
            	error:function(){
            			alert("注册失败，请稍候再试");
            	}
            		
            });
        });
        
    });
    
</script>


</body>
</html>