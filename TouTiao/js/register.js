$(function() {
	jQuery.validator
			.addMethod("verify_length",
					function(value, element) {
						return this.optional(element)
								|| ($.trim(value).length >= 3 && $.trim(value).length <= 15);
					}, "请输入3-15个字符");
	
	jQuery.validator
	.addMethod("letter_digital",
			function(value, element) {
				var birth_verify=/^[A-Za-z0-9]+$/;
				return this.optional(element)
						|| (birth_verify.test(value));
			}, "只能为数字或字母");
	
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
			//error_td.append(error);
		},
		submitHandler : function(form) {
			//ajaxpost('register_form', '', '', 'onerror')
			//form.submit();
		},
		rules : {
			user_account : {
				required : true,
				verify_length : true,
				letter_digital:true,
				remote :{
					url : '../room.php',
					type : 'post',
					data : {
						'userAccount' : function() {
							return $('#user_account').val();
						},
						'type':'checkAccount'
					}
				}
			},
			user_name : {
				required : true,
				verify_length : true
			},
			password : {
				required : true,
				verify_length : true
			},
			password_confirm : {
				required : true,
				equalTo : '#password'
			},
			birthday : {
				required : true,
				birthday_verify:true 
			}
		},
		messages : {
			user_account : {
				required : '帐号不能为空',
				verify_length:'帐号必须在3-15个字符之间',
				letter_digital:'帐号必须是数字或者字母',
				remote : '该帐号已经存在'
			},
			user_name : {
				required : '用户名不能为空',
				verify_length:'用户名必须在3-15个字符之间'
			},
			password : {
				required : '密码不能为空',
				verify_length : '密码必须在3-15个字符之间'
			},
			password_confirm : {
				required : '请再次输入您的密码',
				equalTo : '两次输入的密码不一致'
			},
			birthday : {
				required : '请填写生日',
				birthday_verify : '生日格式为YYYY-MM-DD'
			}
//			captcha : {
//				required : '请输入验证码',
//				remote : '验证码不正确'
//			},
//			agree : {
//				required : '请阅读并同意该协议'
//			}
		}
	});
});