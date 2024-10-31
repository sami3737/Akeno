<div class="col-lg-9">

	<p class="Medium"><strong>Download Information</strong></p>
	<p class="small">- Our client is Virus Free, if you do detect a virus they will not harm you or your system in anyway.
	<br>- We spend alot of time and work in our client and we ask that you wont use it for any other purpose than playing BLG Silkroad.
	<br>- We do not Recommend to download our game-client from any other website then ours
	<br>- We Run client Check-up software so we Prefer that you dont change any files in our client to prevent system issues
	<br>- We update our client weekly at different time moments to prevent abuse</p>
	
	<br>
	<Center>
	<table style="width:70%" id="customers">
							<tbody><tr>
		<?php
			$data = SqlManager::Resource("SELECT * FROM ".Config::$CMS_DB."..Downloadinfo order by ID ASC");
			while ($result = sqlsrv_fetch_array($data,SQLSRV_FETCH_ASSOC)) {
				echo'
							<th>Info</th>
								<th>Client</th>
								<th>Update</th>
							</tr>
							<tr>
								<th>Version</th>
								<td>'.$result['Version'].'</td>
								<td>'.$result['Version2'].'</td>
							</tr>
							<tr>
								<th>Updated</th>
								<td>'.$result['Date'].'</td>
								<td>'.$result['Date2'].'</td>
							</tr>
							<tr>
								<th>File Size</th>
								<td>'.$result['Size'].'</td>
								<td>'.$result['Size2'].'</td>
							</tr>';}
		?>
						</tbody></table></center>
						<br>
	
	<div class="row">
		<?php
			$data = SqlManager::Resource("SELECT * FROM ".Config::$CMS_DB."..Downloads order by ID ASC");
			while ($result = sqlsrv_fetch_array($data,SQLSRV_FETCH_ASSOC)) {
				echo'
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<div class="entry-post">
							<div class="download-box">
								<center>
								<h3>'.$result['Title'].'<br>'.$result['Image'].'<br></h3>
								</center>
								<a href="'.$result['Downloadlnk'].'" target="_blank" class="btn btn-block btn-primary">Download</a>
							</div>
						</div>
					</div>';}
		?>
	</div>
	<br>
	<center>
	<table style="width:80%" id="customers">
							<tbody><tr>
								<th>Specs</th>
								<th>Min. Specs</th>
								<th>Required Specs</th>
							</tr>
							<tr>
								<th>CPU</th>
								<td>Pentium 4 2.4GHz or higher</td>
								<td>Intel i5 3.2GHz or higher</td>
							</tr>
							<tr>
								<th>RAM</th>
								<td>2048GB</td>
								<td>4096GB</td>
								</tr>
							<tr>
								<th>GPU</th>
								<td>Recommend GT 610 or higher</td>
								<td>Recommend GT 750 or higher</td>
								</tr>
							<tr>
								<th>AUDIO</th>
								<td>DirectX 10 Compatibility card</td>
								<td>DirectX 11 Compatibility card</td>
							</tr>
							<tr>
								<th>HDD</th>
								<td>HDD 10Gb or more</td>
								<td>SSD 10Gb or more</td>
							</tr>
							<tr>
								<th>OS</th>
								<td>Win7, Win8 & Win10</td>
								<td>We Recommend Windows 10</td>
							</tr>
							<tr>
								<th></th>
								<td><strong><a href="https://www.systemrequirementslab.com/cyri/requirements/silk-road-online/11568">Test Your System</a></strong></td>
								<td><strong><a href="https://www.systemrequirementslab.com/cyri/requirements/silk-road-online/11568">Test Your System</a></strong></td>
							</tr>
						</tbody></table></center>
						<!-- Side Ads -->
<ins class="adsbygoogle"
     style="display:inline-block;width:300px;height:600px;margin-left:-360px;margin-top:-970px"
     data-ad-client="ca-pub-2541593655735054"
     data-ad-slot="3467909944"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
</div>