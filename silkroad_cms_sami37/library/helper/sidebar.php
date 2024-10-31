<?php
class SideBar
{
	private static function getCharID($Charname)
	{
		return SQLManager::ReadSingle("SELECT CharID FROM ".Config::$shardDB.".._Char WHERE CharName16 = '$Charname'");
	}
	
	
	private static function time_elapsed_B($secs)
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
	
	private static function getTotalJobbers()
	{
		return SQLManager::NumRows("SELECT * FROM ".Config::$shardDB.".._CharTrijob WHERE JobType > 0"); 
	}


	public static function getTraderPercentage()
	{
		$TotalTraders = SQLManager::NumRows("SELECT * FROM ".Config::$shardDB.".._CharTrijob WHERE JobType = 1");
		$tradersCount = self::getTotalJobbers() > 0 ? self::getTotalJobbers() : 1;


		return Ceil($TotalTraders * 100 / $tradersCount);
		
	}	
	public static function getHunterPercentage()
	{
		$TotalHunter = SQLManager::NumRows("SELECT * FROM ".Config::$shardDB.".._CharTrijob WHERE JobType = 3");
		$huntersCount = self::getTotalJobbers() > 0 ? self::getTotalJobbers() : 1;
		
		return Ceil($TotalHunter * 100 / $huntersCount);
	}
	
	public static function getThiefPercentage()
	{
		$TotalThieves = SQLManager::NumRows("SELECT * FROM ".Config::$shardDB.".._CharTrijob WHERE JobType = 2");
		$thiefsCount = self::getTotalJobbers() > 0 ? self::getTotalJobbers() : 1;

		return Ceil($TotalThieves * 100 / $thiefsCount);
	}

	
	public function getLastUnique()
	{
		$resource = SQLManager::Resource("SELECT TOP 10 * FROM Evangelion_uniques ORDER BY TIME DESC");
		$array = [];
		
		while ($result = SQLSRV_FETCH_ARRAY($resource,SQLSRV_FETCH_ASSOC))
		{
			
			$sqlTime = strtotime(SQLManager::ReadSingle("SELECT TOP 1 CONVERT(DATETIME,Time) FROM Evangelion_uniques WHERE CharName = '".$result['CharName']."' AND MobName = '".$result['MobName']."' AND Time = '".$result['time']."'")->format("d/m h:i"));
			$TimeNow = strtotime(SQLManager::ReadSingle("SELECT GETDATE()")->format("d/m h:i"));
			
			
			$Unique = SQLManager::ReadSingle("SELECT TOP 1 UniqueName FROM ".Config::$CMS_DB.".._Web_UniqueName WHERE UniqueCodeName128 = '".$result['MobName']."'");
			
			
			$array[] = ["CharID" => self::getCharID($result['CharName']),"Charname" => $result['CharName'], "Unique" => $result['MobName'],"UniqueName" => $Unique,"Time" => self::time_elapsed_B($sqlTime)];
		}
		
		return $array;
	}
	private function getFortressHotan()
	{
		
	}
	public function getFortres($type)
	{
		if($type == "hotan")
		{
			
		}
			
	}
	public function ServerStats()
	{
		
	}
	public function getOnlinePlayers()
	{
		
	}
}