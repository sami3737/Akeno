<div id="result" class="active" style="display: block;">
	<div class="col-lg-9">

		<?php
		foreach($object as $news)
		{
		?>
	
	
		<div class="post">
			<div class="post-header">
				<div class="post-title">
					<h2 style="font-weight:bold;font-size:20px;"><?php echo $news['Subject']?></h2>
				</div>
			</div>
			
			<h2 style="font-size:10px;"><?php echo $news['Article']?></h2>
			
			<ul class="post-meta">
				<li><i class="fa fa-calendar margin-right-5"></i> <?php echo $news['Datetime']?> </li>
			</ul>
		
			<div class="divider"></div>
		</div>
		
		
		<!-- /.blog-post -->
		<?php } ?>
		<?php echo Paginator::getPagination($totalRecords,$page,$totalPage,"news")?>
		
		<!-- Side Ads -->
<ins class="adsbygoogle"
     style="display:inline-block;width:300px;height:600px;margin-left:-340px;margin-top:-900px"
     data-ad-client="ca-pub-2541593655735054"
     data-ad-slot="3467909944"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
	</div>
</div>