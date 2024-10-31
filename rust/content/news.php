<?php
$object = ['object'=>getlimitedNews($config->news_totalRecords,1),'totalPage'=>getNewsCount(),'page'=>1,'totalRecords'=>$config->news_totalRecords];

function getNewsCount()
{
	return SQLManager::NumRows("SELECT * FROM ".Config::$CMS_DB.".._webNews");
}

function getlimitedNews(int $limit = 1,int $offset = 0)
{
	
	$offset = $offset * $limit - $limit;
		
	$totalRecords = getNewsCount();
	
	if($offset == 1)
		$offset = 0;
	
	$resource = SQLManager::Resource
	("
	SELECT TOP $limit News.*
	FROM
	(
		SELECT Subject,
				Article,
				EditDate,
				ROW_NUMBER() OVER (ORDER BY ID ASC) AS RowNumber 
		FROM ".Config::$CMS_DB.".._webNews
		
	) 
	AS News
	WHERE News.RowNumber BETWEEN $offset+1 AND $totalRecords
	");
	$array = [];
	
	while($result = SQLSRV_FETCH_ARRAY($resource,SQLSRV_FETCH_ASSOC))
	{
		
		$array[] = ["Subject" => $result['Subject'], "Article" => $result['Article'], "Datetime" => $result['EditDate']->format("d/m/y H:i")];
	}
	
	return $array;
}

foreach($object['object'] as $news)
{
?>
	<div class="process__item">
		<h4><?php echo $news['Subject']; ?><br></h4>
		<p><?php echo $news['Article']; ?></p>
		<li><i class="fa fa-calendar margin-right-5"></i> <?php echo $news['Datetime']?> </li>
	</div>
<?php } ?>
<?php echo Paginator::getPagination(getNewsCount(),1,$config->news_totalRecords,"news")?>
		
		<!-- Side Ads -->
<ins class="adsbygoogle"
     style="display:inline-block;width:300px;height:600px;margin-left:-340px;margin-top:-900px"
     data-ad-client="ca-pub-2541593655735054"
     data-ad-slot="3467909944"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
