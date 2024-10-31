<?php
include_once("api/Helper/socket.class.php");
include_once("api/config/config.class.php");
include_once("api/database/mssql.class.php");
$Socket = new Socket();
$config = new Config();

function getCharID($Charname)
{
	return SQLManager::ReadSingle("SELECT CharID FROM ".Config::$shardDB.".._Char WHERE CharName16 = '$Charname'");
}
function time_elapsed_B($secs)
{
	$time = time()-$secs;
	$bit = array(
		' hour'        => $time / 3600 % 24,
		' minute'    =>	$time / 60 % 60,
		' second'    =>	$time % 60
		);
	$ret = array();
	foreach($bit as $k => $v){
		if($v > 1)$ret[] = $v . $k . 's';
		if($v == 1)$ret[] = $v . $k;
		}
	array_splice($ret, count($ret)-1, 0, 'and');
	$ret[] = 'ago.';
	return join(' ', $ret);
}

function getLastUnique()
{
	$resource = SQLManager::Resource("SELECT TOP 10 * FROM Evangelion_uniques ORDER BY TIME DESC");
	$array = [];
	
	while ($result = SQLSRV_FETCH_ARRAY($resource,SQLSRV_FETCH_ASSOC))
	{
		
		$sqlTime = strtotime(SQLManager::ReadSingle("SELECT TOP 1 CONVERT(DATETIME,Time) FROM Evangelion_uniques WHERE CharName = '".$result['CharName']."' AND MobName = '".$result['MobName']."' AND Time = '".$result['time']."'")->format("d/m h:i"));
		$TimeNow = strtotime(SQLManager::ReadSingle("SELECT GETDATE()")->format("d/m h:i"));
		
		
		$Unique = SQLManager::ReadSingle("SELECT TOP 1 UniqueName FROM ".Config::$CMS_DB.".._Web_UniqueName WHERE UniqueCodeName128 = '".$result['MobName']."'");
		
		
		$array[] = ["CharID" => getCharID($result['CharName']),"Charname" => $result['CharName'], "Unique" => $result['MobName'],"UniqueName" => $Unique,"Time" => time_elapsed_B($sqlTime)];
	}
	
	return $array;
}

$object = getLastUnique();
?>

<!--Unique History-->
<div class="widget categories">
	<div class="title" style="font-size:15px;">Unique History</div>
	<div class="divider"></div>
	<div class="row">
		<div class="col-sm-12">
			<ul class="blog_archieve">
				<div class="clearfix margin-bottom-15"></div>
				<i class="fa fa-tasks"></i>
				<strong>General <i data-toggle="collapse" data-target="#uniques" class="fa fa-plus" style="cursor:pointer;color:#103784;"></i></strong>
				<div id="uniques" class="collapse">
					<ul class="list-unstyled">
					
					<?php if (count($object) > 0) { foreach ($object as $result) { ?>
						<li>
						
							<strong>
								<a class="text-red" href="./ranking/player/<?php $result['CharID']?>">
									<?php $result['Charname']?>
								</a>
							</strong>
							has killed <strong><?php $result['UniqueName']?></strong> <?php $result['Time']?>
						</li>
					<?php } } ?>
					</ul>
				</div>
			</ul>
		</div>
	</div>                     
</div> <!--End Unique History-->

