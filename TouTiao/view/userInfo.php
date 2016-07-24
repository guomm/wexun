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
</head>
<body>
<?php include("header.php")?>

<div class="container">
		<div class="col-sm-2 text-center">
			<ul class="list-unstyled category">
				<li><a class="bg-click" href="userInfo.php">个人信息</a></li>
				<li><a href="myStorage.php">我的收藏</a></li>
			</ul>
		</div>
		<div class="col-sm-9">
			<form class="form-horizontal login-form" id="register_form">
				<div class="form-group">
					<label for="ff-user" class="col-md-3 control-label">账号</label>
					<div class="col-md-4">
						<input type="text" class="form-control" id="user_name"
							name="user_name" value="ggg">
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
						id="inlineRadio1" name="gender" value="1" checked> 男 
					</label> <label class="col-md-1 radio-inline "> <input type="radio"
						id="inlineRadio2" name="gender" value="0"> 女
					</label>
					<p class="col-md-4 form-control-static text-danger"></p>
				</div>

				<div class="form-group">
					<label for="ff-repsw" class="col-md-3 control-label">爱好</label> <label
						class="checkbox-inline col-md-1 checkbox-type"> <input
						type="checkbox" name="favor" id="inlineCheckbox1" value="1"> 运动
					</label> <label class="checkbox-inline col-md-1"> <input
						type="checkbox" name="favor" id="inlineCheckbox2" value="2"> 音乐
					</label> <label class="checkbox-inline col-md-1"> <input
						type="checkbox" name="favor" id="inlineCheckbox3" value="3"> 军事
					</label> <label class="checkbox-inline col-md-1"> <input
						type="checkbox" name="favor" id="inlineCheckbox4" value="4"> 财经
					</label>
					<p class="col-md-4 form-control-static text-danger"></p>
				</div>

				<div class="form-group">
					<div class="col-md-offset-3 col-md-4">
						<button class="btn btn-primary">修改</button>
					</div>
				</div>
			</form>
		</div>
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
    });
</script>
</body>
</html>