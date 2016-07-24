jQuery(document).ready(function($){
	var $form_modal = $('.cd-user-modal'),
		$form_login = $form_modal.find('#cd-login'),
		$main_nav = $('.main_nav');
	
	//弹出窗口
	$main_nav.on('click', function(event){

//		if( $(event.target).is($main_nav) ) {
//			// on mobile open the submenu
//			$(this).children('ul').toggleClass('is-visible');
//		} else {
//			// on mobile close submenu
//			$main_nav.children('ul').removeClass('is-visible');
//			//show modal layer
//			
//		}
		$form_modal.addClass('is-visible');	
		//show the selected form
		login_selected();
	});

	//关闭弹出窗口
	$('.cd-user-modal').on('click', function(event){
		if( $(event.target).is($form_modal) || $(event.target).is('.cd-close-form') ) {
			$form_modal.removeClass('is-visible');
		}	
	});
	//使用Esc键关闭弹出窗口
	$(document).keyup(function(event){
    	if(event.which=='27'){
    		$form_modal.removeClass('is-visible');
	    }
    });
	
	
	
	function login_selected(){
		$form_login.addClass('is-selected');
		//$form_forgot_password.removeClass('is-selected');
		//$tab_login.addClass('selected');
	};

	$('#login').on('click',function(event){
		//alert("s");
		var formParam = $('#login_form').serialize();
		//alert(formParam);
    	$.ajax({
    		url: '../room.php',  
    		data: formParam,
    		type:'post',
    		dataType:'json',
    		success: function(data){
        			//alert(data.user_id);
        			//alert(data.user_name);
    			
        			$form_modal.removeClass('is-visible');
        			$("#loginbut").hide();
        			$("#userName").html(data.user_name);
        			$("#userName").show();
        	},  
        	error:function(){
        			alert("用户名或密码错误");
        	}
        		
        });
	});
});


//credits http://css-tricks.com/snippets/jquery/move-cursor-to-end-of-textarea-or-input/
jQuery.fn.putCursorAtEnd = function() {
	return this.each(function() {
    	// If this function exists...
    	if (this.setSelectionRange) {
      		// ... then use it (Doesn't work in IE)
      		// Double the length because Opera is inconsistent about whether a carriage return is one character or two. Sigh.
      		var len = $(this).val().length * 2;
      		this.setSelectionRange(len, len);
    	} else {
    		// ... otherwise replace the contents with itself
    		// (Doesn't work in Google Chrome)
      		$(this).val($(this).val());
    	}
	});
};