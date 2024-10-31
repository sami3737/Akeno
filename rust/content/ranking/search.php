<?php
$searchType = '';
$searchValue = '';
if(isset($_GET['searchtype']))
	$searchType = strtolower($_GET['searchtype']);
if(isset($_GET['value']))
	$searchValue = $_GET['value'];

require('../../api/security/security.class.php');
require("../../api/Helper/socket.class.php");
require("../../api/config/config.class.php");
require("../../api/database/mssql.class.php");

$Socket = new Socket();
$config = new Config();
$Security = new Security();

SQLManager::Connect(config::$SQLHOST,config::$SQLUsername,config::$SQLPassword,config::$accDB);

$object = '';
function search($searchType = 'charname',$searchValue = "")
{
	global $config;
	global $object;

	$Type;
	switch(strtolower($searchType))
	{
		case "charname":
		{
			$Type = "Player";
		}
		break;
		
		case "guild":
		{
			$Type = "Guild";
		}
		break;
		
		default:
		{
			$Type = "Player";	

		}
		break;
	}
	if(strlen($searchValue) == 0)
	{
		$object = ["object"=>getTopPlayers($config->ranking_totalRecords,1), "totalPage" => getPlayersCount(), "page" => 1, "totalRecords" => $config->ranking_totalRecords];
		return;
	}
	$object = ["object"=>searchs($Type,$searchValue),"searchType"=>$Type];
}


function topPlayers($param1 = "page",$page = 1)
{
	global $config;
	global $object;
	if(!is_numeric($page))
		die;
	
	$object = ["object"=>getTopPlayers($config->ranking_totalRecords,$page), 
	"totalPage" => getPlayersCount(),
	"page" => $page,
	"totalRecords" => $config->ranking_totalRecords];
}

function topGuilds($param1 = "page",$page = 1)
{
	global $config;
	global $object;
	
	if(!is_numeric($page))
		die;
	
	$object = ["object"=>getTopGuilds($config->ranking_totalRecords,$page), 
	"totalPage" => getGuildsCount(),
	"page" => $page,
	"totalRecords" => $config->ranking_totalRecords];
}

function getPlayersCount()
{
	return SqlManager::NumRows("SELECT TOP 50 CharID FROM ".Config::$shardDB.".dbo._Char WHERE (CharName16 NOT LIKE '%GM%') AND (CharName16 != 'd')");
}

function getGuildsCount()
{
	return SqlManager::NumRows("SELECT TOP 50 ID FROM ".Config::$shardDB.".dbo._Guild WHERE (Name NOT LIKE 'dummy')");
}

function searchs($type = "player",$searchValue)
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
			FROM ".Config::$shardDB.".dbo._Char As _Char
			INNER JOIN ".Config::$shardDB.".dbo._Guild As _Guild On _Char.GuildID = _Guild.ID
			INNER JOIN ".Config::$shardDB.".dbo._CharTrijob As _CharTrijob On _Char.CharID = _CharTrijob.CharID
			INNER JOIN ".Config::$shardDB.".dbo._Inventory As _Inventory On _Char.CharID = _Inventory.CharID
			INNER JOIN ".Config::$shardDB.".dbo._Items As _Items On _Inventory.ItemID = _Items.ID64
			INNER JOIN ".Config::$shardDB.".dbo._RefObjCommon AS Common ON Common.ID = _Items.RefItemID
			INNER JOIN ".Config::$shardDB.".dbo._RefObjItem AS _RefObjItem on Common.Link = _RefObjItem.ID
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
			(SELECT TOP 1 CharName FROM ".Config::$shardDB.".dbo._GuildMember WHERE GuildID = GU.ID AND SiegeAuthority = 1) AS Master,
			(SELECT COUNT(CharID) FROM ".Config::$shardDB.".dbo._GuildMember WHERE GuildID = GU.ID) AS Member,
			(SELECT TOP 1 CharID FROM ".Config::$shardDB.".dbo._GuildMember WHERE GuildID = GU.ID AND SiegeAuthority = 1) AS MasterCharID,
			GU.Lvl,
			(SUM(_Items.OptLevel)+SUM(_RefObjITem.ItemClass) * 10) AS Point,
			ROW_NUMBER() OVER(ORDER BY SUM(IT.OptLevel) desc,Lvl desc,(SELECT COUNT(CharID) FROM ".Config::$shardDB.".dbo._GuildMember WHERE GuildID = GU.ID) desc) AS rowNumber  
			FROM ".Config::$shardDB.".dbo._Items AS IT
			INNER JOIN ".Config::$shardDB.".dbo._Inventory AS INV ON INV.ItemID = IT.ID64
			INNER JOIN ".Config::$shardDB.".dbo._Items As _Items On INV.ItemID = _Items.ID64
			INNER JOIN ".Config::$shardDB.".dbo._RefObjCommon AS Common ON Common.ID = _Items.RefItemID
			INNER JOIN ".Config::$shardDB.".dbo._RefObjItem AS _RefObjItem on Common.Link = _RefObjItem.ID
			INNER JOIN ".Config::$shardDB.".dbo._GuildMember AS GM ON GM.CharID = INV.CharID
			INNER JOIN ".Config::$shardDB.".dbo._Guild AS GU ON GU.ID = GM.GuildID
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

function getTopPlayers(int $limit = 1,int $offset = 0)
{
	$offset = $offset * $limit - $limit;
	
	$totalRecords = getPlayersCount();

	
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
		FROM ".Config::$shardDB.".dbo._Char As _Char
			INNER JOIN ".Config::$shardDB.".dbo._Guild As _Guild On _Char.GuildID = _Guild.ID
			INNER JOIN ".Config::$shardDB.".dbo._CharTrijob As _CharTrijob On _Char.CharID = _CharTrijob.CharID
			INNER JOIN ".Config::$shardDB.".dbo._Inventory As _Inventory On _Char.CharID = _Inventory.CharID
			INNER JOIN ".Config::$shardDB.".dbo._Items As _Items On _Inventory.ItemID = _Items.ID64
			INNER JOIN ".Config::$shardDB.".dbo._RefObjCommon AS Common ON Common.ID = _Items.RefItemID
			INNER JOIN ".Config::$shardDB.".dbo._RefObjItem AS _RefObjItem on Common.Link = _RefObjItem.ID
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

function getTopGuilds(int $limit = 1,int $offset = 0)
{
	$offset = $offset * $limit - $limit;
	
	$totalRecords = getGuildsCount();

	
	if($offset == 1)
		$offset = 0;

	$resource = SqlManager::Resource
	("
	SELECT Top $limit GuildInfo.* 
	FROM
	(
			SELECT TOP 50 GU.ID,
			GU.Name,
			(SELECT TOP 1 CharName FROM ".Config::$shardDB.".dbo._GuildMember WHERE GuildID = GU.ID AND SiegeAuthority = 1) AS Master,
			(SELECT COUNT(CharID) FROM ".Config::$shardDB.".dbo._GuildMember WHERE GuildID = GU.ID) AS Member,
			(SELECT TOP 1 CharID FROM ".Config::$shardDB.".dbo._GuildMember WHERE GuildID = GU.ID AND SiegeAuthority = 1) AS MasterCharID,
			GU.Lvl,
			(SUM(_Items.OptLevel)+SUM(_RefObjITem.ItemClass) * 10) AS Point,
			ROW_NUMBER() OVER(ORDER BY SUM(IT.OptLevel) desc,Lvl desc,(SELECT COUNT(CharID) FROM ".Config::$shardDB.".dbo._GuildMember WHERE GuildID = GU.ID) desc) AS rowNumber  
			FROM ".Config::$shardDB.".dbo._Items AS IT
			INNER JOIN ".Config::$shardDB.".dbo._Inventory AS INV ON INV.ItemID = IT.ID64
			INNER JOIN ".Config::$shardDB.".dbo._Items As _Items On INV.ItemID = _Items.ID64
			INNER JOIN ".Config::$shardDB.".dbo._RefObjCommon AS Common ON Common.ID = _Items.RefItemID
			INNER JOIN ".Config::$shardDB.".dbo._RefObjItem AS _RefObjItem on Common.Link = _RefObjItem.ID
			INNER JOIN ".Config::$shardDB.".dbo._GuildMember AS GM ON GM.CharID = INV.CharID
			INNER JOIN ".Config::$shardDB.".dbo._Guild AS GU ON GU.ID = GM.GuildID
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

if(strtolower($searchType) == 'topplayers')
	getTopPlayers($config->ranking_totalRecords,1);
if(strtolower($searchType) == 'topguilds')
	 getTopGuilds($config->ranking_totalRecords,1);

if(strtolower($searchType) == "player")
{
	search($searchType, $searchValue);
	if(isset($object['object']) && count($object['object']) > 0)
	{
		?>
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th>Character / Guild</th>
						<th>Race</th>
						<th>Level</th>
						<th>Job</th>
						<th>Itempoints</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach($object['object'] as $result) {
						echo '<tr>
						<td>'.$result['ID'].'</td>
						<td><a href="./?page=chardata&char='.$result['CharID'].'">'.$result['Charname'].'</a></td>
						<td>'.$result['Race'].'</td>
						<td>'.$result['Level'].'</td>
						<td>'.$result['Job'].'</td>
						<td>'.$result['Points'].'</td>
						</tr>';
					}
					?>
				</tbody>
			</table>
		</div>
		<?php
	}
	else
		echo "No character found";
}
if(strtolower($searchType) == "guild")
{
	search($searchType, $searchValue);
	if(isset($object['object']) && count($object['object']) > 0)
	{
		?>
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th>Guild</th>
						<th>Master</th>
						<th>Member</th>
						<th>Level</th>
						<th>itemPoints</th>
					</tr>
				</thead>
				<tbody>
				<?php 
				foreach($object['object'] as $result) {
					echo '<tr>
						<td>'.$result["ID"].'</td>
						<td><a href="/ranking/guild/'.$result["GuildID"].'">'.$result['Guildname'].'</a></td>
						<td><a href="./?page=chardata&char='.$result['MasterCharID'].'">'.$result['Master'].'</a></td>
						<td>'.$result['Memeber'].'</td>
						<td>'.$result['Level'].'</td>
						<td>'.$result['Points'].'</td>
						</tr>';
				}
				?>
				</tbody>
			</table>
		</div>
		<?php
	} else
		echo "No guild found";
}