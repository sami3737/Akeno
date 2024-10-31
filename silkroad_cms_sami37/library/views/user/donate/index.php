<?php 
	global $config;
	$username = $_SESSION['username'];
?>

<div class="col-lg-9">
	<div class="row">
		
		<div class="col-sm-12">
			<div class="post-header">
				<div class="post-title">
					<img style="text-align: center; margin: 0 0 0 275;" src="/images/1paypal.png" width="300" height="300" class="img-responsive"></a>
					<div class="divider"></div>
					<br>
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
					$ArrayFetching = SqlManager::Resource("SELECT * FROM SILKROAD_CMS.._Donate_PackagePrice");
					while($result = sqlsrv_fetch_array($ArrayFetching,SQLSRV_FETCH_ASSOC))
					{
						$price = $result['PackageUSD_Price'];
						$roundprice = round($price,2);
						ECHO'
						<option value="'.$roundprice.'" name="'.$result['Package_Name'].'">'.$result['Package_Name'].'</option>
						';
					}
					?>
					</select>
				</div>			
				<input type="hidden" name="return" value="<?php 
					$return_add = $config->site_http."panel/donate";
					echo $return_add; 
				?>">
				<input type="hidden" name="notify_url" value="<?php 
					$return_add = $config->site_http."paypalipn";
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
				</div>
			</div>
		</div>
		
	</div>
</div>