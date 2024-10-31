<?php
include_once("api/Helper/socket.class.php");
include_once("api/config/config.class.php");
include_once("api/database/mssql.class.php");
$Socket = new Socket();
$config = new Config();

function getTotalJobbers()
{
	return SQLManager::NumRows("SELECT * FROM ".Config::$shardDB.".._CharTrijob WHERE JobType > 0"); 
}


function getTraderPercentage()
{
	$TotalTraders = SQLManager::NumRows("SELECT * FROM ".Config::$shardDB.".._CharTrijob WHERE JobType = 1");
	$tradersCount = getTotalJobbers() > 0 ? getTotalJobbers() : 1;


	return Ceil($TotalTraders * 100 / $tradersCount);
	
}	
function getHunterPercentage()
{
	$TotalHunter = SQLManager::NumRows("SELECT * FROM ".Config::$shardDB.".._CharTrijob WHERE JobType = 3");
	$huntersCount = getTotalJobbers() > 0 ? getTotalJobbers() : 1;
	
	return Ceil($TotalHunter * 100 / $huntersCount);
}

function getThiefPercentage()
{
	$TotalThieves = SQLManager::NumRows("SELECT * FROM ".Config::$shardDB.".._CharTrijob WHERE JobType = 2");
	$thiefsCount = getTotalJobbers() > 0 ? getTotalJobbers() : 1;

	return Ceil($TotalThieves * 100 / $thiefsCount);
}

?>
<div class="title" style="font-size:15px;">JOB RANK</div>
<div class="divider"></div>
<div class="row">
	<div class="col-sm-12">
		<ul class="blog_archieve">
		<div class="clearfix margin-bottom-15"></div>
			<div class="progress">
				<div class="progress-bar progress-bar-trader" style="width:<?php echo getTraderPercentage();?>%;"> Traders </div>
				<div class="progress-bar progress-bar-hunter" style="width:<?php echo getHunterPercentage();?>%;"> Hunters </div>
				<div class="progress-bar progress-bar-thief"  style="width:<?php echo getThiefPercentage();?>%;"> Thieves </div>
			</div>
			<ul class="joblegend list-unstyled">
				<li><span class="trader"></span> <?php echo getTraderPercentage();?>% Traders&nbsp;<img src="img/job/trader-icon.png"/></li>
				<li><span class="hunter"></span> <?php echo getHunterPercentage();?>% Hunters&nbsp;<img src="img/job/hunter-icon.png"/></li>
				<li><span class="thief"></span> <?php echo getThiefPercentage();?>% Thieves&nbsp;<img src="img/job/thief-icon.png"/></li>
			</ul>
		</ul>
	</div>
</div>                     
