function showLogin(){
	jQuery('#modalStatus h3').text('Log in to our website');
	jQuery('#modalStatus .text').html(
	'<div class="col-xs-4 col-xs-offset-4 margin-bottom-10"><img src="/images/logo.png" class="img-responsive"></div>'+
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
						'<li>You dont have an Account? <a href="/user/register/">Register</a></li>'+
						'<li>Forget your Password, <a href="/user/lost-password/">click here</a> to reset your password.</li>'+
					'</ul>'+
				'</div>'+
				'<div class="col-sm-3 col-xs-5 pull-right">'+
					'<button class="btn-block btn btn-warning">Login</button>'+
				'</div>'+
			'</div>'+
		'</div>'+
		'<div class="clearfix"></div>'+
	'</form>'
	);
	jQuery('#modalStatus').modal('toggle');
}


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
	jQuery.post("/auth/login/"+sUsername+"/"+sPassword).done(function(json) {
		var dataArray = jQuery.parseJSON(json);
		if(dataArray.status == 'Successful'){
			window.location = '/panel/account';
			return true;
		}else{
			jQuery(sBefore+'.errorBox span.message').text(dataArray.reason);
		}
		jQuery(sBefore+'.errorBox').removeClass('hidden');
		overOverLayerClose();
	});
}

function loginForm()
{
	overOverLayer();
	jQuery('#loginSubmit div').removeClass('text-error');
	jQuery('#loginSubmit .alert .errorBox').empty();
	jQuery('#errorBox').addClass('hidden');
	jQuery.post("/auth/login/"+jQuery('#username').val()+"/"+jQuery('#password').val()).done(function(json) {
		var dataArray = jQuery.parseJSON(json);
		if(dataArray.status == 'Successful'){
			window.location = '/panel/account';
			return true;
		}
		else
		{
			jQuery('#errorBox .errorBox').empty();
			jQuery('#errorBox .errorBox').append('<span class="message">'+dataArray.reason+'</span><br/>')
			jQuery('#errorBox').removeClass('hidden');
		}
		overOverLayerClose();
	});
	return false;
}
