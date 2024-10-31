<section class="text-center">
	<div class="container">
		<div class="row" style="text-align:left">
			<div class="col-lg-9">
				<div class="row">
					<form onsubmit="checkRegister();return false;" class="form">
						<div id="reg-title" class="reg-header h2">
							<h2 style="font-weight:bold;font-size:20px;">Register a new account</h2>
						</div>
						
						<div class="divider"></div>
						<div id="errorBox" class="hidden">
							<div class="alert alert-danger">
								<span class="errorMessage">Change a few things up and try submitting again.</span><br>
								<div class="errorBox"> </div>
							</div>
						</div>
						
						<div class="clear"> </div>
				
						<div id="successBox" class="hidden">
							<div class="alert alert-success">
								<div class="successBox"> </div>
							</div>
						</div>
						
						<div class="clear"> </div>
				
						<div class="form-group">
							<div class="row"><div id="secretcode" class="col-sm-6" style="margin: 0;">
									<label for="usernameInput">Username <span class="text-red">*</span></label>
								<input type="text" id="usernameInput" class="form-control" value="" pattern=".{3,16}" required="" title="" aria-describedby="ui-tooltip-0"></div></div>
							
						</div>
						
						<div class="clearfix"></div>
						
						<div class="form-group">
							<div class="row">
								<div id="email" class="col-sm-6">
									<label for="emailInput">Email Address <span class="text-red">*</span></label>
									<input type="email" id="emailInput" class="form-control" value="" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" required="">
								</div>
								<div id="email2" class="col-sm-6">
									<label for="confirmEmailInput">Confirm Email Address <span class="text-red">*</span></label>
									<input type="email" id="confirmEmailInput" class="form-control" value="" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" required="">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-6">
									<label for="passwordInput">Password <span class="text-red">*</span></label>
									<input type="password" id="passwordInput" class="form-control" value="" pattern=".{6,16}" required="" title="" autocomplete="off" aria-describedby="ui-tooltip-2">
								</div>
								<div class="col-sm-6">
									<label for="password2Input">Confirm Password <span class="text-red">*</span></label>
									<input type="password" id="password2Input" class="form-control" value="" pattern=".{6,16}" required="" title="" autocomplete="off" aria-describedby="ui-tooltip-1">
								</div>
							</div>
						</div>
						
						<div class="clearfix"></div>
						
						<div class="form-group">
							<div class="row">
								<div class="col-sm-6">
									<label for="captchaInput">Please verify you are a human <span class="text-red">*</span></label>
									<input type="text" id="captchaInput" class="form-control" pattern=".{1,100}" required="">
								</div>
								<div class="col-sm-6">
									<p class="imgcaptcha"><img id="captchaImage" src="./captcha/captcha.php?_CAPTCHA&amp;t=0.15195400+1520619310"></p>
									<i onclick="reloadCaptcha()" title="Refresh Captcha" class="fa fa-refresh refreshIconCaptcha"></i>
					
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
						
						<div class="clearfix"></div>
						
						<div class="form-inline margin-top-10">
							<button class="btn-primary btn pull-right" style="position:static" type="submit">Register</button>
							<div class="clearfix"></div>
						</div>
						
						<hr>

							<div class="margin-top-10">Already Signed Up? Click <a onclick="showLogin();" href="#" class="text-red">Sign In</a> to login your account.</div>
						<hr>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
<script type="text/javascript">
function checkRegister()
{
	var username1 = jQuery('#usernameInput').val();
	var password1 = jQuery('#passwordInput').val();
	var password2 = jQuery('#password2Input').val();
	var email1 = jQuery('#emailInput').val();
	var email2 = jQuery('#confirmEmailInput').val();
	var entrycaptcha1 = jQuery('#captchaInput').val();
	
	overOverLayer();
	jQuery('#submitReg div').removeClass('text-error');
	jQuery('#reg-title').removeClass('hidden');
	jQuery('#submitReg .alert .errorBox').empty();
	jQuery('#errorBox').addClass('hidden');
	jQuery('#successBox').addClass('hidden');
	jQuery.post('./api/models/checkregisterinputs.php',{username:username1 ,password:password1 ,password2:password2 ,email:email1 ,email2:email2,code:entrycaptcha1},
	function(json){
		var obj = jQuery.parseJSON( json );
		if(obj.status == "Successfully")
		{
			reloadCaptcha();
			jQuery('#reg-title').addClass('hidden');
			jQuery('#successBox .successBox').empty();
			jQuery('#successBox .successBox').append('<span class="message">'+obj.message+'</span><br/>')
			jQuery('#successBox').removeClass('hidden');
			jQuery('#usernameInput').val('');
			jQuery('#passwordInput').val('');
			jQuery('#password2Input').val('');
			jQuery('#emailInput').val('');
			jQuery('#confirmEmailInput').val('');
			jQuery('#captchaInput').val('');
			overOverLayerClose();
			return true;
		
		}
		jQuery('#errorBox .errorBox').empty();
		jQuery.each(obj.reason,function(index,value){
			jQuery('form #'+index).addClass('text-error');
			jQuery('#errorBox .errorBox').append('<span class="message">'+value+'</span><br/>')
		});
		jQuery('#errorBox').removeClass('hidden');
		
		overOverLayerClose();
		return false;
	});
	
	return false;
}
</script>