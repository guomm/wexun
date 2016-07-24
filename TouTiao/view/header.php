
<header class="header">
	<div class="container">
		<div class="col-sm-2 text-center">
			<a class="logo-link" href="/"> <img class="logo"
				src="http://s3a.pstatp.com/toutiao/resource/toutiao_web/static/style/image/newindex/toutiaologo_7f2639e.png">
			</a>
		</div>
		<div class="col-sm-6 header-text">
			<form action="">
				<input type="search" class="form-control" id="exampleInputEmail1"
					placeholder="大家都在搜: 周一情侣">
			</form>
		</div>
		<div class="col-sm-4 text-center header-text">
		<?php  
		if($_COOKIE['userName']){
			echo "<a href='#0' id='userName'>".$_COOKIE['userName']."</a>";
		}else{
			echo "<button class='btn btn-primary main_nav' id='loginbut' type='button'>登录</button>";
			echo "<a href='#0' id='userName'></a>";
		}?>
			
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
					if($_COOKIE['userAccount']){
						echo "<input class='full-width has-padding has-border' id='user_name' name='user_name' type='text' value=".$_COOKIE['userAccount'].">";
					}else{
						echo "<input class='full-width has-padding has-border' id='user_name' name='user_name' type='text' value=''>";
					}
					?>
					
				</p>

				<p class="fieldset">
					<label class="image-replace cd-password" for="signin-password">密码</label>
					<?php 
					if($_COOKIE['password']){
						echo "<input class='full-width has-padding has-border' id='password' name='password' type='password' value=".$_COOKIE['password'].">";
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
