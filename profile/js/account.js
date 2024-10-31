function showLogin(){
	jQuery('#modalStatus').modal('toggle');
}
$(document).ready(function()
{
	jQuery('#modalStatus h3').text('Log in to our website');
	jQuery('#modalStatus .text').html(
	'<div class="col-xs-4 col-xs-offset-4 margin-bottom-10"><img src="./img/logo.png" class="img-responsive" style="margin-left: -100px;"></div>'+
	'<div class="clearfix"></div>'+
	'<form onsubmit="loginAction(true);return false;"  method="post">'+
		'<div class="hidden errorBox">'+
			'<div class="alert alert-danger">'+
				'<span class="message"></span>'+
			'</div>'+
		'</div>'+
		'<div class="input-group margin-bottom-20">'+
			'<span class="input-group-addon">'+
				'<i class="fa fa-user"></i>'+
			'</span>'+
			'<input type="text" class="form-control usernameInputLogin" name="username" placeholder="Username" />'+
		'</div>'+
		'<div class="input-group margin-bottom-20">'+
			'<span class="input-group-addon">'+
				'<i class="fa fa-lock"></i>'+
			'</span>'+
			'<input type="password" class="form-control passwordInputLogin" name="password" placeholder="*****"  AUTOCOMPLETE="off" />'+
		'</div>'+
		'<div class="controls form-inline">'+
			'<div class="row">'+
				'<div class="col-sm-9 col-xs-7">'+
					'<ul class="list-unstyled">'+
						'<li>You dont have an Account? <a href="?page=register">Register</a></li>'+
						'<li>Forget your Password, <a href="?page=lostpassword">click here</a> to reset your password.</li>'+
					'</ul>'+
				'</div>'+
				'<div class="col-sm-3 col-xs-5 pull-right">'+
					'<button class="btn-block btn btn-warning">Login</button>'+
				'</div>'+
			'</div>'+
		'</div>'+
		'<div class="clearfix"></div>'+
	'</form>'+
	'<div class="login-popup">'+
		'<div class="log-left">'+
		'<form id="login_form" method="POST">'+
		'</form>'+
		'</div>'+
	'</div>'
	);
	$('#login_form').load('./api/login.php');
});

function loginAction(bLoginBox){
	overOverLayer();
	sBefore = '.box ';
	if(bLoginBox){
		sBefore = '#modalStatus ';
	}
	jQuery(sBefore+'.errorBox').addClass('hidden');
	jQuery(sBefore+'.errorBox span.message').text('');
	sUsername = jQuery(sBefore+'.usernameInputLogin').val();
	sPassword = jQuery(sBefore+'.passwordInputLogin').val();
	jQuery.post("./content/login.php", {action: 'login', user: sUsername, pass: sPassword}).done(function(json) {
		var dataArray = jQuery.parseJSON(json);
		console.log(dataArray.status);
		if(dataArray.status == 'Successfully'){
			window.location = './?page=profile';
			return true;
		}else{
			jQuery(sBefore+'.errorBox span.message').text(dataArray.reason);
		}
		jQuery(sBefore+'.errorBox').removeClass('hidden');
		overOverLayerClose();
	});
}