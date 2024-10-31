<?php
class rankingModel extends model
{
	
	public function getPlayersCount()
	{
		return SqlManager::NumRows("SELECT TOP 50 CharID FROM ".Config::$shardDB.".._Char WHERE (CharName16 NOT LIKE '%GM%') AND (CharName16 != 'd')");
	}
	
	public function getGuildsCount()
	{
		return SqlManager::NumRows("SELECT TOP 50 ID FROM ".Config::$shardDB.".._Guild WHERE (Name NOT LIKE 'dummy')");
	}
	#region Search (Function)
	public function search($type = "player",$searchValue)
	{
		global $Security;
		$searchValue = $Security->secure($searchValue);
		
		$type = strtolower($type);
		if($type == "player")
		{
			$resource = SqlManager::Resource
			("
			SELECT CharInfo.* 
			FROM
			(
				SELECT _Char.CharID,
				(CASE WHEN _Guild.Name != 'dummy' THEN _Char.CharName16 + ' / ' + _Guild.Name WHEN _Guild.Name = 'dummy' THEN _Char.CharName16 END) as Charname, 
				(CASE WHEN RefObjID BETWEEN '1907' AND '1932' Then 'Chinese' When RefObjID BETWEEN '14875' AND '14900' Then 'Europe' Else 'None' End) As Race,
				_Char.CurLevel, 
				(CASE WHEN JobType = '1' Then 'Trader' When JobType = '2' Then 'Thief' When JobType = '3' Then 'Hunter' Else 'None' End) As JobType,
				(SUM(OptLevel)+SUM(_RefObjItem.ItemClass) * 10) AS Point,
				(ROW_NUMBER() Over (Order By _Char.CurLevel Desc , (SUM(OptLevel)+SUM(RefItemID)/100) Desc)) As RowNumber
				FROM ".Config::$shardDB.".._Char As _Char
				INNER JOIN ".Config::$shardDB.".._Guild As _Guild On _Char.GuildID = _Guild.ID
				INNER JOIN ".Config::$shardDB.".._CharTrijob As _CharTrijob On _Char.CharID = _CharTrijob.CharID
				INNER JOIN ".Config::$shardDB.".._Inventory As _Inventory On _Char.CharID = _Inventory.CharID
				INNER JOIN ".Config::$shardDB.".._Items As _Items On _Inventory.ItemID = _Items.ID64
				INNER JOIN ".Config::$shardDB.".._RefObjCommon AS Common ON Common.ID = _Items.RefItemID
				INNER JOIN ".Config::$shardDB.".._RefObjItem AS _RefObjItem on Common.Link = _RefObjItem.ID
				WHERE (CharName16 like '%".$searchValue."%') AND (CharName16 NOT LIKE '%GM%') AND (_Inventory.Slot in (0,1,2,3,4,5,6,9,10,11,12) )
				GROUP BY _Char.CharID, _Char.CharName16, _Char.CurLevel, _Guild.Name, _CharTrijob.JobType, _Char.RefObjID
			) 
			As CharInfo
			");
			
			$array = [];
			
			WHILE($result = SQLSRV_FETCH_ARRAY($resource,SQLSRV_FETCH_ASSOC))
			{
				$array[] = ["CharID" => $result['CharID'], "ID" => $result['RowNumber'], "Charname" => $result['Charname'], "Race" => $result['Race'], "Level" => $result['CurLevel'], "Job" => $result['JobType'], "Points" => $result['Point']];

			}
			
			return $array;
		}

		else if($type == "guild")
		{
			$resource = SqlManager::Resource
			("
			SELECT GuildInfo.*
			FROM 
			(
				SELECT GU.ID,
				GU.Name,
				(SELECT TOP 1 CharName FROM ".Config::$shardDB.".._GuildMember WHERE GuildID = GU.ID AND SiegeAuthority = 1) AS Master,
				(SELECT COUNT(CharID) FROM ".Config::$shardDB.".._GuildMember WHERE GuildID = GU.ID) AS Member,
				(SELECT TOP 1 CharID FROM ".Config::$shardDB.".._GuildMember WHERE GuildID = GU.ID AND SiegeAuthority = 1) AS MasterCharID,
				GU.Lvl,
				(SUM(_Items.OptLevel)+SUM(_RefObjITem.ItemClass) * 10) AS Point,
				ROW_NUMBER() OVER(ORDER BY SUM(IT.OptLevel) desc,Lvl desc,(SELECT COUNT(CharID) FROM ".Config::$shardDB.".._GuildMember WHERE GuildID = GU.ID) desc) AS rowNumber  
				FROM ".Config::$shardDB.".._Items AS IT
				INNER JOIN ".Config::$shardDB.".._Inventory AS INV ON INV.ItemID = IT.ID64
				INNER JOIN ".Config::$shardDB.".._Items As _Items On INV.ItemID = _Items.ID64
				INNER JOIN ".Config::$shardDB.".._RefObjCommon AS Common ON Common.ID = _Items.RefItemID
				INNER JOIN ".Config::$shardDB.".._RefObjItem AS _RefObjItem on Common.Link = _RefObjItem.ID
				INNER JOIN ".Config::$shardDB.".._GuildMember AS GM ON GM.CharID = INV.CharID
				INNER JOIN ".Config::$shardDB.".._Guild AS GU ON GU.ID = GM.GuildID
				WHERE GU.Name like '%".$searchValue."%'  AND (INV.Slot in (0,1,2,3,4,5,6,9,10,11,12) )
				GROUP BY GU.Name,GU.Lvl,GU.ID  
			)
			AS GuildInfo
			");
			
			$array = [];
			
			WHILE($result = SQLSRV_FETCH_ARRAY($resource,SQLSRV_FETCH_ASSOC))
			{
				$array[] = ["GuildID" => $result['ID'], "ID" => $result['rowNumber'], "Guildname" => $result['Name'], "Master" => $result['Master'], "MasterCharID" => $result['MasterCharID'], "Memeber" => $result['Member'], "Level" => $result['Lvl'], "Points" => $result['Point']];
			}
			
			return $array;
		}
	}
	
	#endregion
	
	
	#region Top Players (Function)
	public function getTopPlayers(int $limit = 1,int $offset = 0)
	{
		$offset = $offset * $limit - $limit;
		
		$totalRecords = $this->getPlayersCount();

		
		if($offset == 1)
			$offset = 0;

		$resource = SqlManager::Resource
		("
		SELECT Top $limit CharInfo.* 
		FROM
		(
			SELECT TOP 50 _Char.CharID,
				(CASE WHEN _Guild.Name != 'dummy' THEN _Char.CharName16 + ' / ' + _Guild.Name WHEN _Guild.Name = 'dummy' THEN _Char.CharName16 END) as Charname, 
				(CASE WHEN RefObjID BETWEEN '1907' AND '1932' Then 'Chinese' When RefObjID BETWEEN '14875' AND '14900' Then 'Europe' Else 'None' End) As Race,
				_Char.CurLevel, 
				(CASE WHEN JobType = '1' Then 'Trader' When JobType = '2' Then 'Thief' When JobType = '3' Then 'Hunter' Else 'None' End) As JobType,
				(SUM(OptLevel)+SUM(_RefObjItem.ItemClass) * 10) AS Point,
				(ROW_NUMBER() Over (Order By _Char.CurLevel Desc ,(SUM(OptLevel)+SUM(RefItemID)/100) Desc)) As RowNumber
			FROM ".Config::$shardDB.".._Char As _Char
				INNER JOIN ".Config::$shardDB.".._Guild As _Guild On _Char.GuildID = _Guild.ID
				INNER JOIN ".Config::$shardDB.".._CharTrijob As _CharTrijob On _Char.CharID = _CharTrijob.CharID
				INNER JOIN ".Config::$shardDB.".._Inventory As _Inventory On _Char.CharID = _Inventory.CharID
				INNER JOIN ".Config::$shardDB.".._Items As _Items On _Inventory.ItemID = _Items.ID64
				INNER JOIN ".Config::$shardDB.".._RefObjCommon AS Common ON Common.ID = _Items.RefItemID
				INNER JOIN ".Config::$shardDB.".._RefObjItem AS _RefObjItem on Common.Link = _RefObjItem.ID
			WHERE (_Char.CharName16 NOT LIKE '%GM%') AND (_Inventory.Slot in (0,1,2,3,4,5,6,9,10,11,12))
			GROUP BY _Char.CharID, _Char.CharName16, _Char.CurLevel, _Guild.Name, _CharTrijob.JobType, _Char.RefObjID

		)
		As CharInfo
		WHERE CharInfo.RowNumber BETWEEN $offset+1 AND $totalRecords
		");
		
		$array = [];
		
		WHILE($result = SQLSRV_FETCH_ARRAY($resource,SQLSRV_FETCH_ASSOC))
		{
			$array[] = ["CharID" => $result['CharID'], "ID" => $result['RowNumber'], "Charname" => $result['Charname'], "Race" => $result['Race'], "Level" => $result['CurLevel'], "Job" => $result['JobType'], "Points" => $result['Point']];

		}
		
		return $array;		
	}
	#endregion
	
	#region Top Guilds (Function)
	public function getTopGuilds(int $limit = 1,int $offset = 0)
	{
		$offset = $offset * $limit - $limit;
		
		$totalRecords = $this->getGuildsCount();

		
		if($offset == 1)
			$offset = 0;

		$resource = SqlManager::Resource
		("
		SELECT Top $limit GuildInfo.* 
		FROM
		(
				SELECT TOP 50 GU.ID,
				GU.Name,
				(SELECT TOP 1 CharName FROM ".Config::$shardDB.".._GuildMember WHERE GuildID = GU.ID AND SiegeAuthority = 1) AS Master,
				(SELECT COUNT(CharID) FROM ".Config::$shardDB.".._GuildMember WHERE GuildID = GU.ID) AS Member,
				(SELECT TOP 1 CharID FROM ".Config::$shardDB.".._GuildMember WHERE GuildID = GU.ID AND SiegeAuthority = 1) AS MasterCharID,
				GU.Lvl,
				(SUM(_Items.OptLevel)+SUM(_RefObjITem.ItemClass) * 10) AS Point,
				ROW_NUMBER() OVER(ORDER BY SUM(IT.OptLevel) desc,Lvl desc,(SELECT COUNT(CharID) FROM ".Config::$shardDB.".._GuildMember WHERE GuildID = GU.ID) desc) AS rowNumber  
				FROM ".Config::$shardDB.".._Items AS IT
				INNER JOIN ".Config::$shardDB.".._Inventory AS INV ON INV.ItemID = IT.ID64
				INNER JOIN ".Config::$shardDB.".._Items As _Items On INV.ItemID = _Items.ID64
				INNER JOIN ".Config::$shardDB.".._RefObjCommon AS Common ON Common.ID = _Items.RefItemID
				INNER JOIN ".Config::$shardDB.".._RefObjItem AS _RefObjItem on Common.Link = _RefObjItem.ID
				INNER JOIN ".Config::$shardDB.".._GuildMember AS GM ON GM.CharID = INV.CharID
				INNER JOIN ".Config::$shardDB.".._Guild AS GU ON GU.ID = GM.GuildID
				WHERE INV.Slot in (0,1,2,3,4,5,6,9,10,11,12)
				GROUP BY GU.Name,GU.Lvl,GU.ID  
		)
		As GuildInfo
		WHERE GuildInfo.RowNumber BETWEEN $offset+1 AND $totalRecords
		");
		
		$array = [];
		
		WHILE($result = SQLSRV_FETCH_ARRAY($resource,SQLSRV_FETCH_ASSOC))
		{
			$array[] = ["GuildID" => $result['ID'], "ID" => $result['rowNumber'], "Guildname" => $result['Name'], "Master" => $result['Master'], "MasterCharID" => $result['MasterCharID'], "Memeber" => $result['Member'], "Level" => $result['Lvl'], "Points" => $result['Point']];
		}
		
		return $array;		
	}
	#endregion
}