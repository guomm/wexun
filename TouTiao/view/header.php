
<header class="header">
	<div class="container">
		<div class="col-sm-2 text-center">
			<a class="logo-link" href="/"> <img class="logo"
				src="http://s3a.pstatp.com/toutiao/resource/toutiao_web/static/style/image/newindex/toutiaologo_7f2639e.png">
			</a>
		</div>
		
		<div class="col-sm-6 header-text">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="请输入关键词" id="search_input">
                        <span class="input-group-btn">
                            <button class="btn btn-default" id="searchBut" ><i class="glyphicon glyphicon-search"></i></button>
                        </span>
                    </div>

            </div>
            <div class="col-sm-4 text-right header-text">
                <a class="btn btn-link" type="button" href="index.php"><i class="glyphicon glyphicon-home"></i> 首页</a>

                <a class="btn btn-link main_nav" type="button"  id='loginbut'>登录</a>

                <div class="dropdown" style="display: inline-block;" id="userInfo">
                    <a class="btn btn-link" id="dLabel" data-target="#"  data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <span id="userName"></span>
                        <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu" aria-labelledby="dLabel">
                        <li><a type="button" href="userInfo.php">个人中心</a></li>
                        <li><a type="button" href="" id='logout'>退出</a></li>
                    </ul>
                </div>
            </div>
		
<!-- 		<div class="col-sm-6 header-text"> -->
<!-- 				<input type="search" class="form-control" id="search_input" -->
<!-- 					placeholder="大家都在搜: 周一情侣"> -->
<!-- 					<button id="searchBut" class="searchBut"></button> -->
<!-- 		</div> -->
<!-- 		<div class="col-sm-4 text-center header-text"> -->
<!-- 		<a href="index.php">首页&nbsp;&nbsp;&nbsp;</a> -->
<!-- 		<a href='userInfo.php' id='userName'></a> -->
<!-- 		&nbsp;&nbsp;&nbsp;<a href='#0' id='logout'>退出</a> -->
<!-- 		<button class='btn btn-primary main_nav' id='loginbut' type='button'>登录</button> -->
		</div>
	</div>
</header>
<div class="cd-user-modal">
	<div class="cd-user-modal-container">
		<div class="cd-title">
			<label>用户登录</label>
		</div>
		<div id="cd-login">
			<!-- 登录表单 -->
			<form class="cd-form" method="post" id="login_form" >
				<p class="fieldset">
					<label class="image-replace cd-username" for="signin-username">用户名</label>
					<?php 
					require_once '../model/common.php';
					if($_COOKIE['userAccount']){
						echo "<input class='full-width has-padding has-border' id='user_name' name='user_name' type='text' value=".secret2string($_COOKIE['userAccount']).">";
					}else{
						echo "<input class='full-width has-padding has-border' id='user_name' name='user_name' type='text' value=''>";
					}
					?>
					
				</p>

				<p class="fieldset">
					<label class="image-replace cd-password" for="signin-password">密码</label>
					<?php 
					if($_COOKIE['password']){
						echo "<input class='full-width has-padding has-border' id='password' name='password' type='password' value=".secret2string($_COOKIE['password']).">";
					}else{
						echo "<input class='full-width has-padding has-border' id='password' name='password' type='password' value=''>";
					}
					?>
				</p>

				<p class="fieldset">
					<input type="checkbox"  name ="remember_me" value="1" checked> <label
						for="remember-me">记住登录状态</label> <a href="register.php">注册新用户</a>
				</p>
				<input type="hidden" name="type" value="login">
				<p class="fieldset">
					<input class="full-width2" id="login" type="button" value="登 录">
				</p>
			</form>
		</div>

	</div>
</div>

<script>
<?php 
echo "var userName='".$_COOKIE["userName"]."';";
?>

if(userName.length>0){
	$("#loginbut").hide();
	$("#userInfo").show();
	//$("#logout").show();
	$("#userName").html(userName);
}else{
	$("#userInfo").hide();
	$("#loginbut").show();
}

$("#logout").on("click",function(event){
	//alert("D");
	//window.location.href="search.php?search_val="+$("#search_input").val();  
	$.ajax({
    		url: '../room.php',  
    		data: {
				'type':'logout'
        	},
    		type:'post',
    		dataType:'json',
    		success: function(data){
    			$("#userName").hide();
    			$("#logout").hide();
    			$("#loginbut").show();
    			location.reload();
        	},  
        	error:function(data){
            	console.log(data);
        	}
        		
        });
	location.reload();
});
</script>