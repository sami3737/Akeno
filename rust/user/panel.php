<section class="text-center">
	<div class="container">
		<div class="row">
		<?php

		include_once("api/config/config.class.php");
		include_once("api/database/mssql.class.php");
		$username = $_SESSION['username'];
		$config = new Config();

		if(isset($_SESSION['username']))
		{
			$ArrayFetching = SqlManager::Resource("SELECT * FROM SilkroadCMS.dbo._Donate_PackagePrice");
			?>
			<div class="col-lg-12">
				<div class="row">
					<div class="col-sm-12">
						<div class="post-header">
							<div class="post-title">
								<h2><img style="margin: 0 auto;" src="./img/1paypal.png" width="300" height="300" class="img-responsive"/></h2>
							</div>
						</div>
						<div class="divider"></div>
					</div>
					<div class="col-sm-12">
						<!-- change the form with generating a new one on paypal website -->
						<form action="https://www.paypal.com/cgi-bin/webscr" target="_blank" method="post">
						
							<input type="hidden" name="cmd" value="_xclick" />
							<input type="hidden" name="business" value="<?php echo $config->paypal_email; ?>"> 
							<input type="hidden" name="item_name" value="Silk">
							<input type="hidden" name="currency_code" value="USD" />
							<input type="hidden" id="input" name="custom" value="<?=$username?>" />	
							<div class="form-group">
								<label for="amount">Select package <span class="color-red">*</span></label>
								<select name="amount" class="form-control" id="amount">
								<?php
								while($result = sqlsrv_fetch_array($ArrayFetching,SQLSRV_FETCH_ASSOC))
								{
									$price = $result['PackageUSD_Price'];
									$roundprice = round($price,2);
									echo '<option value="'.$roundprice.'" name="'.$result['Package_Name'].'">'.$result['Package_Name'].'</option>';
								}
								?>
								</select>
							</div>			
							<input type="hidden" name="return" value="<?php 
								$return_add = $config->site_http."panel/donate";
								echo $return_add; 
							?>">
							<input type="hidden" name="notify_url" value="<?php 
								$return_add = $config->site_http."ipn";
								echo $return_add; 
							?>">
							<center>
								<input type="image" name="submit"
									src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif"
									alt="PayPal - The safer, easier way to pay online">
								<img alt="" width="1" height="1"
									src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >
							</center>
						
						</form>
						<!-- end of paypal form-->
					</div>
				</div>
			</div>
		
			<div class="col-lg-12">
				<div class="row">
					<div class="col-sm-12">
						<div class="post-header">
							<div class="post-title">
								<h2 style="font-weight:bold;font-size:20px;">Account Overview</h2>
							</div>
						</div>
						<div class="divider"></div>
					</div>
					<div class="col-sm-12">
						<div class="headline"><h3>Change Password</h3></div>
						<p>Here you can change your account password.</p>
						<form onsubmit="checkPasswordBox('ingame');return false;">
							<div class="form-group">
								<label class="control-label" for="idIngamePassword">Password <span class="text-red">*</span></label>
								<input type="password" id="idIngamePassword" placeholder="Password" class="form-control">
							</div>
							<div class="form-group">
								<label class="control-label" for="idIngamePassword2">Repeat <span class="text-red">*</span></label>
								<input type="password" id="idIngamePassword2" placeholder="Password repeat" class="form-control">
							</div>
							<div class="form-group form-inline margin-top-10">
								<button class="btn btn-primary pull-right" type="submit">Submit</button>
								<div class="clearfix"></div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<script>
			jQuery(document).ready(function(){
				jQuery('.timepicker').datetimepicker({firstDay:1,minDate:new Date(),changeMonth:true,changeYear:true});
			});
			</script>
			<script type="text/javascript">
			var type;
			var hh;
			var mm;
			var ss;

				function holidayCheck(){
					jQuery.post('/panel/account/set-holiday/',{ hour:hh, minute:mm, second:ss, plain:jQuery('#idPWPlain').val()}).success(function(json){
						var dataArray = jQuery.parseJSON(json);
						overOverLayerClose();		
						if(dataArray.status == 'Succeed'){
								window.location='/';
								return true;
							}
							jQuery('#modalStatus h3').text('Holiday Settings');
							jQuery('#modalStatus .text').empty();
							aAusgabe=dataArray['reason'];
							jQuery('#modalStatus .text').html(aAusgabe);
							jQuery('#modalStatus').modal('toggle');}).error(function(){window.location='/';});
							return false;	
				}
					
				function ingamePasswordCheck(){
					jQuery.post('./api/account/changepassword.php',{password:jQuery('#idIngamePassword').val(),password2:jQuery('#idIngamePassword2').val(),curpw:jQuery('#idPWPlain').val()},
					function(json){
						var obj = jQuery.parseJSON( json );
						overOverLayerClose();
						if(obj.status == "Successfully"){
							jQuery('#idIngamePassword, #idIngamePassword2').val('');
							window.location = 'api/auth/login/auth.php?action=logout';
							return true;
						}
						jQuery('#modalStatus h3').text('Change IngamePassword');
						jQuery('#modalStatus .text').empty();
						aAusgabe=obj.reason;
						jQuery.each(obj.reason,function(index,value){

							jQuery('#modalStatus .text').append('<span class="message">'+value+'</span><br/>')
						});

						jQuery('#modalStatus').modal('toggle');
						});
						return false;
				}
													
				function checkPasswordBox(sType){
					type=sType;
					jQuery('#modalStatus h3').text('Check Password');
					jQuery('#modalStatus .text').html
					(
						'<div>'+'<p>Please verify your current password.</p>'+
						'<div class="controls">'+'<label for="idPWPlain">Current Password <span class="color-red">*</span></label>'+
						'<input type="password" id="idPWPlain" placeholder="Password" class="form-control"  AUTOCOMPLETE="off" />'+
						'</div>'+
						'<div class="controls form-inline margin-top10">'+
						'<button class="btn btn-primary pull-right" onclick="confirm();">Submit</button>'+
						'<div class="clearfix"></div>'+
						'</div>'+
						'</div>'
					);
					jQuery('#modalStatus').modal('toggle');
					jQuery('#idPWPlain').focus();
				}
				
				function confirm(){
					if(type=='holiday'){
						setTimeout(function(){holidayCheck();},1000);
					}
					else if(type=='web'){
						setTimeout(function(){ingameWebCheck();},1000);
					}
					else if(type=='ingame'){
						setTimeout(function(){ingamePasswordCheck();},1000);
					}
					overOverLayer();
					jQuery('#modalStatus .close').trigger('click');
					
					return false;
				}
				
				function HolidayConfirm()
				{
					type = 'holiday'
					hh = jQuery('#idHolidayHour').val();
					mm = jQuery('#idHolidayMinute').val();
					ss = jQuery('#idHolidaySecond').val();
					jQuery('#modalStatus h3').text('Holiday Check');
					jQuery('#modalStatus .text').html('<span class="infoHoverText">Yes, I really want to ban my account for <br> {'+hh+' hour : '+mm+' minute : '+ss+' second}</span>'+
					'<div>'+'<br><p>So, Please verify your current password.</p>'+
					'<div class="controls">'+'<label for="idPWPlain">WebPassword <span class="color-red">*</span></label>'+
					'<input type="password" id="idPWPlain" placeholder="Password" class="form-control" />'+
					'<br><div class="form-group form-inline margin-top10">'+
					'<button class="btn btn-green pull-right" onclick="confirm();">Confirm</button>'+
					'<div class="clearfix"></div>'+'</div></div></div>');
					jQuery('#modalStatus').modal('toggle');
				}
				

				
			</script>
		<?php
		}
		?>
		</div>
	</div>
</section>
