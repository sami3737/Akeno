<?php
class dataModel extends model
{
	const WEAPON = 6;
    const SHIELD = 4;
    const ACC = 5;
    const SET = 1;
	public function checkPlayer($CharID)
	{
		return SqlManager::NumRows("SELECT CharID FROM ".Config::$shardDB.".._Char WHERE CharID = $CharID");
	}
	
	protected static function getDegree4ItemClass($iItemClass)
    {
        $iDegree = $iItemClass / 3;
        return ceil($iDegree);
    }
	protected static function getSOXRate4ItemClass($iItemClass, $iRarity)
    {
        if ($iRarity <= 1) {
            return '';
        }
        $aSOX = [3 => 'Seal of Nova', 2 => 'Seal of Star', 1 => 'Seal of Moon', 0 => 'Seal of Sun'];
        $iDegree = self::getDegree4ItemClass($iItemClass);
        $iSOXRate = (int)(($iDegree * 3) - $iItemClass);
        $iSOXRate = ($iDegree == 12 && $iSOXRate == 2) ? 3 : $iSOXRate;
        return $aSOX[$iSOXRate];
    }
	
	    protected static function calcOPTValue($iValue, $iBonus, $iOptLvl)
    {
        return round($iValue + $iBonus * $iOptLvl);
    }

    protected static function getValue($iMin, $iMax, $iProzent)
    {
        return round($iMin + ((($iMax - $iMin) / 100) * $iProzent));
    }

    protected static function getDuraMaxValue($iValue, $aSpecialInfo)
    {
        if (isset($aSpecialInfo['MATTR_DUR'])) {
            $iValue = self::getBlueValue($iValue, $aSpecialInfo['MATTR_DUR']);
        }
        if (isset($aSpecialInfo['MATTR_DEC_MAXDUR'])) {
            $iValue = self::getBlueValueNegative($iValue, $aSpecialInfo['MATTR_DEC_MAXDUR']);
        }
        return $iValue;
    }

    protected static function getBlueValue($iValue, $iProzent)
    {
        return round($iValue + (($iValue / 100) * $iProzent));
    }

    protected static function getBlueValueNegative($iValue, $iProzent)
    {
        return round($iValue - ($iValue / 100 * $iProzent));
    }
	
	public function getPlayerItems($charID)
	{
		$data = [];
		
		for($i = 0; $i < 13; $i ++)
		{
			if($i == 8)
				continue;
			

			$data['slot'. $i] = $this->getCharItembySlot($charID,$i);
		}
		
		return $data;
	}
	
	public function getPlayerAvatars($charID)
	{
		$data = [];
		
		for($i = 0; $i < 5; $i ++)
		{		
			$data['slot'. $i] = $this->getCharAvatars($charID,$i);
		}
		
		return $data;
	}
	public function getPlayerData($charID)
	{
		/*
		Fetching 
			Player name,
			Str,
			Int,
			Item Points,
			Title,
			Guild,
			jobType,
			JobAlias,
			Status,
			lastRefresh
		*/
		$resource = SQLManager::Resource
		("
		SELECT CH.CharName16, CH.RefObjID, CH.CurLevel, CH.Strength, CH.Intellect,
			SUM(OptLevel)+SUM(RefItemID)/100 AS itemPoints, GU.Name, GU.ID,
			CASE WHEN CJ.JobType = 1 THEN 'Thief' WHEN CJ.JobType = 2 THEN 'Hunter' WHEN CJ.JobType = 3 THEN 'Trader' ELSE 'None' END AS 'JobType',
			CASE WHEN LEN(CH.NickName16) = 0 THEN 'None' ELSE CH.NickName16 END AS JobAlias,
			ISNULL((SELECT Status FROM SILKROAD_CMS.._Char_Status WHERE Charname = CH.CharName16),'Offline') as Status, CH.LastLogout
		FROM ".Config::$shardDB.".._Char AS CH
			INNER JOIN ".Config::$shardDB.".._CharTrijob AS CJ ON CJ.CharID = CH.CharID
			INNER JOIN ".Config::$shardDB.".._Inventory AS INV ON INV.CharID = CH.CharID 
			INNER JOIN ".Config::$shardDB.".._Items AS IT ON IT.ID64 = INV.ItemID
			INNER JOIN ".Config::$shardDB.".._Guild AS GU ON GU.ID = CH.GuildID
		WHERE CH.CharID = $charID
		GROUP BY CH.CharName16, CH.CurLevel, CH.Strength, CH.Intellect, CH.RefObjID, CJ.JobType, GU.Name, CH.NickName16, CH.CharID, CH.LastLogout, GU.ID
		");
		
		$array = [];
		
		while($result = SQLSRV_FETCH_ARRAY($resource,SQLSRV_FETCH_ASSOC))
		{
			$GuildLevel = SQLManager::ReadSingle("SELECT Lvl FROM ".Config::$shardDB.".._Guild WHERE ID = ".$result['ID']);
			$array[] = ["CharID"=>$charID, "Charname" => $result['CharName16'], "ObjID" => $result['RefObjID'], "Level" => $result['CurLevel'], "Str" => $result["Strength"], "Int" => $result['Intellect'], "Points" => $result['itemPoints'], "GuildName" => $result['Name'], "GuildID" => $result['ID'], "GuildLevel" => $GuildLevel, "JobType" => $result['JobType'], "JobName" => $result['JobAlias'], "Status" => $result['Status'], "lastRefresh" => $result['LastLogout']->format("d/m/y h:i")];
		}
		
		return $array;
	}
	
	public function getInventoryData($charID)
	{
		
	}
	public function getCharItembySlot($charID,$slot)
	{
		$data = [];
		
		$resource = SQLManager::Resource
		("
		SELECT * 
		FROM SRO_VT_SHARD.._Inventory AS Inventory
			JOIN SRO_VT_SHARD.._Items AS Items ON Items.ID64 = Inventory.ItemID
			LEFT JOIN SRO_VT_SHARD.._BindingOptionWithItem AS BindingItem ON BindingItem.nItemDBID = Items.ID64 AND BindingItem.nOptValue > 0
			JOIN SRO_VT_SHARD.._RefObjCommon AS RefObjCommon ON RefObjCommon.ID = ITEMS.RefItemID
			JOIN SRO_VT_SHARD.._RefObjItem AS RefObjItem ON RefObjItem.ID = RefObjCommon.Link
		WHERE CharID = $charID AND Slot = $slot
		");
		
		$result = SQLSRV_FETCH_ARRAY($resource,SQLSRV_FETCH_ASSOC);		
		$aSpecialInfo = [];
		$data['itemInfo'] = $this->getItemInfo($result);
		$data['image'] = $this->getItemImage($result);
		$data['whitestate'] = $this->getWhiteStats($result, []);
		$data['blue'] = $this->getBluesStats($result,$aSpecialInfo);
		
		$data['ItemDesc'] = $this->printitemDetials($data);
		
		
		return $data;
	}
	
	public function getCharAvatars($charID,$slot)
	{
		$data = [];
		
		$resource = SQLManager::Resource
		("
		SELECT * 
		FROM SRO_VT_SHARD.._InventoryForAvatar AS Inventory
			JOIN SRO_VT_SHARD.._Items AS Items ON Items.ID64 = Inventory.ItemID
			LEFT JOIN SRO_VT_SHARD.._BindingOptionWithItem AS BindingItem ON BindingItem.nItemDBID = Items.ID64 AND BindingItem.nOptValue > 0
			JOIN SRO_VT_SHARD.._RefObjCommon AS RefObjCommon ON RefObjCommon.ID = ITEMS.RefItemID
			JOIN SRO_VT_SHARD.._RefObjItem AS RefObjItem ON RefObjItem.ID = RefObjCommon.Link
		WHERE CharID = $charID AND Slot = $slot
		");
		
		$result = SQLSRV_FETCH_ARRAY($resource,SQLSRV_FETCH_ASSOC);		
		$aSpecialInfo = [];
		$data['itemInfo'] = $this->getItemInfo($result);
		$data['image'] = $this->getItemImage($result);
		$data['blue'] = $this->getBluesStats($result,$aSpecialInfo);
		
		$data['ItemDesc'] = $this->printAvatarDetails($data);
		return $data;
	}
	
	private function printAvatarDetails($result)
	{
		/*
								<span style="color:#50cecd;font-weight: bold;">
						Fairy Hat (F) 	</span>
						<br>
						<br>
						<span style="color:#efdaa4;">Sort of item: Avatar hat</span>
						<br>
						<br>
						Female
						<br>
						<br>
						<span style="color:#efdaa4;">Max. no. of magic options: 2 Unit</span>
						<br>
						<br>
						<span style="color:#50cecd;font-weight: bold;">Str 1 Increase</span><br>
						<span style="color:#50cecd;font-weight: bold;">Parry rate 5% Increase</span><br>
						*/
		$details;
		
		$color1 =  (count($result['blue']) > 0) ? '50cecd' : '99999999';
		
		$plus = $result['itemInfo']['itemPlus'] + $result['itemInfo']['nOptValue'];

		
		$details = '<span style="color:#'.$color1.';font-weight: bold;">'. $result['itemInfo']['itemName'];
		$details .= ($plus > 0) ? " ($plus)" : "";
		$details .= '<br/><br/><span style="color:#efdaa4;">Sort of item:  '.$result['itemInfo']['Type'].'</span><br/><br/>';
		
		$details .= $result['itemInfo']['Sex'].'<br/><br/>';
		
		$details .= '<span style="color:#efdaa4;">Max. no. of magic options: '.$result['itemInfo']['MaxMagicOptCount'].' Unit</span>';
		
		if(count($result['blue'] > 0))
			$details .= '<br><br>';
		
		foreach($result['blue'] as $key => $value)
		{
			$details .= "<span style='color:#".$value['color'].";font-weight: bold;'>".$value['name']."</span><br>";

		}		
		
		return $details;
	}

	
	private function printitemDetials($result)
	{
		$details;
		

		if(isset($result['itemInfo']['TypeID2']))	
		{
			$details = [];
			$details['count'] = $result['itemInfo']['Data'];
			$details['name'] = SQLManager::ReadSingle("SELECT Name FROM SILKROAD_CMS.._Web_ItemName WHERE CodeName128 like '%".$result['itemInfo']['NameStrID128']."'");
			return $details;
		}
		

		$color1 =  (count($result['blue']) > 0) ? '50cecd' : '99999999';
		
		if(strlen($result['itemInfo']['sox']) > 0)
			$color1 = 'f2e43d';

		$plus = $result['itemInfo']['itemPlus'] + $result['itemInfo']['nOptValue'];

		
		$details = '<span style="color:#'.$color1.';font-weight: bold;">'. $result['itemInfo']['itemName'];
		$details .= ($plus > 0) ? " ($plus)" : "";
		$details .= '<br><br> '.$result['itemInfo']['sox'].'</span><br>';
		$details .= '<span style="color:#efdaa4;">Sort of item:  '.$result['itemInfo']['Type'].'<br/>';
		
        if (isset($result['itemInfo']['Detials']))
			$details .= 'Mounting part: '.$result['itemInfo']['Detials'].'<br/>';
		 
        $details .= 'Degree: '.$result['itemInfo']['Degree'].' degrees</span><br/><br/>';
		
		//print_r($result['whitestate']);
		foreach($result['whitestate'] as $key => $value)
		{
			$details .= $value.'<br>';
		}
		$details .= '<br>';
		if (isset($result['itemInfo']['ReqLevel1']))
			$details .= 'Reqiure level '.$result['itemInfo']['ReqLevel1'].'<br/>';
		
		if (isset($result['itemInfo']['Sex']))
			$details .= $result['itemInfo']['Sex'].'<br/>';
		
		$details .= $result['itemInfo']['Race'].'<br/>';
		
		$details .= '<br><span style="color:#efdaa4;">Max. no. of magic options: '.$result['itemInfo']['MaxMagicOptCount'].' Unit</span>';
		
		///$details .= print_r($result['blue']);
		if(count($result['blue'] > 0))
			$details .= '<br><br>';
		
		foreach($result['blue'] as $key => $value)
		{
			$details .= "<span style='color:#".$value['color'].";font-weight: bold;'>".$value['name']."</span><br>";

		}
		
//echo "<span style='color:#50cecd;font-weight: bold;'>Immortal (".$Value."Time/times)</span><br>";
		
		return $details;
	}
	
	public function getItemName($result)
	{
		
	}
	public function getitemImage($result)
	{
		$icon = $result['AssocFileIcon128'];
		$icon = str_replace(".ddj",".png",$icon);
		$icon = str_replace("\\","/",$icon);
		$icon = "images/media/icon/".$icon;
		
		if(!file_exists($icon))
		{
			$icon = "../../images/media/icon/icon_default.png";
		}
		return $icon;
	}
	public function getItemInfo($result)
	{
		$data = [];
		
		if ($result['TypeID2'] != 1)
			return $result;
			
		$aStats = explode('_', $result['CodeName128']);
		$aRace = ['CH' => 'Chinese', 'EU' => 'European'];
		$aSEX = [0 => 'Female', 1 => 'Male'];
		$aClothDetail = [
			'FA' => 'Foot',
			'HA' => 'Head',
			'CA' => 'Head',
			'SA' => 'Shoulder',
			'BA' => 'Chest',
			'LA' => 'Legs',
			'AA' => 'Hands'
		];
		$aClothType = [
			'CH' => ['CLOTHES' => 'Garment', 'HEAVY' => 'Armor', 'LIGHT' => 'Protector'],
			'EU' => ['CLOTHES' => 'Robe', 'HEAVY' => 'Heavy armor', 'LIGHT' => 'Light armor']
		];
		$aWeaponType = [
			'CH' => [
				'TBLADE' => 'Glavie',
				'SPEAR' => 'Spear',
				'SWORD' => 'Sword',
				'BLADE' => 'Blade',
				'BOW' => 'Bow',
				'SHIELD' => 'Shield'
			],
			'EU' => [
				'AXE' => 'Dual axe',
				'CROSSBOW' => 'Crossbow',
				'DAGGER' => 'Dagger',
				'DARKSTAFF' => 'Dark staff',
				'HARP' => 'Harp',
				'SHIELD' => 'Shield',
				'STAFF' => 'Light staff',
				'SWORD' => 'Onehand sword',
				'TSTAFF' => 'Twohand staff',
				'TSWORD' => 'Twohand sword'
			]
		];
		//print_r($aStats);
		//echo $aStats[1];
		//print_r($aRace);

		$data['Race'] = (isset($aRace[$aStats[1]])) ? $aRace[$aStats[1]] : '';
	
		switch ($result['TypeID3']) {
			case self::WEAPON:
				$data['Type'] = isset($aWeaponType[$aStats[1]][$aStats[2]]) ? $aWeaponType[$aStats[1]][$aStats[2]] : '';
				$data['Degree'] = self::getDegree4ItemClass($result['ItemClass']);
				$data['sox'] = self::getSOXRate4ItemClass($result['ItemClass'], $result['Rarity']);
				break;
			case self::SHIELD:
				$data['Type'] = isset($aWeaponType[$aStats[1]][$aStats[2]]) ? $aWeaponType[$aStats[1]][$aStats[2]] : '';
				$data['Degree'] = self::getDegree4ItemClass($result['ItemClass']);
				$data['sox'] = self::getSOXRate4ItemClass($result['ItemClass'], $result['Rarity']);
				break;
			case 12:
			case self::ACC:
				$data['Type'] = $aStats[2];
				$data['Degree'] = self::getDegree4ItemClass($result['ItemClass']);
				$data['sox'] = self::getSOXRate4ItemClass($result['ItemClass'], $result['Rarity']);
				break;
			/**
			* DEVIL
			*/
			case 14:
				$data['Type'] = 'Devil´s Spirit';
				$data['Degree'] = 'sdf';
				$data['Sex'] = $aSEX[$result['ReqGender']];
				break;
			/**
			* DRESS
			*/
			case 13:
				$data['Type'] = $aStats[2] . ' ' . ((!isset($aStats[5]) || is_numeric($aStats[5])) ? 'dress' : $aStats[5]);
				$data['Degree'] = $aStats[3];
				$data['Sex'] = $aSEX[$result['ReqGender']];
				break;
			default:
				$data['Degree'] = self::getDegree4ItemClass($result['ItemClass']);
				if (isset($aSEX[$result['ReqGender']])) {
					$data['Sex'] = $aSEX[$result['ReqGender']];
				}
				if (isset($aClothType[$aStats[1]][$aStats[3]])) {
					$data['Type'] = $aClothType[$aStats[1]][$aStats[3]];
				}
				if (isset($aClothDetail[$aStats[5]])) {
					$data['Detail'] = $aClothDetail[$aStats[5]];
				}
				$data['sox'] = self::getSOXRate4ItemClass($result['ItemClass'], $result['Rarity']);
				break;		
		}
		$data['itemName'] = SQLManager::ReadSingle("SELECT Name FROM SILKROAD_CMS.._Web_ItemName WHERE CodeName128 like '%".$result['NameStrID128']."'");
		$data['itemPlus'] = $result['OptLevel'];
		$data['nOptValue'] = $result['nOptValue'];
		$data['ReqLevel1'] = $result['ReqLevel1'];
		$data['MaxMagicOptCount'] = $result['MaxMagicOptCount'];
		
		return $data;
	}
	
    protected function getBluesStats($aItem, &$aSpecialInfo)
    {

        $aBlues = [];

		$_aMagOptLevel = [];
		
		$blue_desc = ['MATTR_STR' => 'Str %desc Increase',
		
					'MATTR_STR_AVATAR' => 'Str %desc Increase',
					'MATTR_AVATAR_STR_2' => 'Str %desc Increase',
					'MATTR_AVATAR_STR_3' => 'Str %desc Increase',
					'MATTR_AVATAR_STR_4' => 'Str %desc Increase',					
					'MATTR_INT_AVATAR' => 'Int %desc Increase',
					'MATTR_AVATAR_INT_2' => 'Int %desc Increase',
					'MATTR_AVATAR_INT_3' => 'Int %desc Increase',
					'MATTR_AVATAR_INT_4' => 'Int %desc Increase',							
					'MATTR_AVATAR_HR' => 'Attack rate %desc% Increase',
					'MATTR_AVATAR_ER' => 'Parry rate %desc% Increase',
					'MATTR_AVATAR_HP' => 'HP %desc Increase',
					'MATTR_AVATAR_MP' => 'MP %desc Increase',
					'MATTR_AVATAR_DRUA' => 'Damage %desc Increase',
					'MATTR_AVATAR_DARA' => 'Damage Absorption %desc Increase',
					'MATTR_AVATAR_MDIA' => 'Ignore Monster Defense %desc Probability',
					'MATTR_AVATAR_MDIA_2' => 'Ignore Monster Defense %desc Probability',
					'MATTR_AVATAR_MDIA_3' => 'Ignore Monster Defense %desc Probability',
					'MATTR_AVATAR_MDIA_4' => 'Ignore Monster Defense %desc Probability',

					
					'MATTR_AVATAR_HPRG' => 'HP Recovery %desc Increase',


					'MATTR_AVATAR_LUCK' => 'Lucky %desc Increase',
					'MATTR_AVATAR_LUCK_2' => 'Lucky %desc Increase',
					'MATTR_AVATAR_LUCK_3' => 'Lucky %desc Increase',
					'MATTR_AVATAR_LUCK_4' => 'Lucky %desc Increase',
					
					'MATTR_INT' => 'Int %desc Increase',
					'MATTR_HP' => 'HP %desc Increase',
					'MATTR_MP' => 'MP %desc Increase',
					'MATTR_DUR' => 'Durability %desc% Increase',
					'MATTR_ER' => 'Parry rate %desc% Increase',
					'MATTR_RESIST_FROSTBITE' => 'Freezing,FrostbiteHour %desc% Reduce',
					'MATTR_RESIST_ESHOCK' => 'Electric  shockHour %desc% Reduce',
					'MATTR_RESIST_BURN' => 'BurnHour %desc% Reduce',
					'MATTR_RESIST_POISON' => 'PoisoningHour %desc% Reduce',
					'MATTR_RESIST_ZOMBIE' => 'ZombieHour %desc% Reduce',
					'MATTR_LUCK' => 'Lucky (%descTime/times)',
					'MATTR_SOLID' => 'Steady (%descTime/times)',
					'MATTR_ATHANASIA' => 'Immortal (%descTime/times)',
					'MATTR_ASTRAL' => 'Astral (%descTime/times)',
					'MATTR_HR' => 'Attack rate %desc% Increase',
					'MATTR_EVADE_BLOCK' => 'Blocking rate %desc',
					'MATTR_EVADE_CRITICAL' => 'Critical %desc'];
		
		
		
        for ($i = 1; $i <= $aItem['MagParamNum']; $i++) {
            if (isset($aItem['MagParam' . $i]) && $aItem['MagParam' . $i] > 1) {
				$MAGID = $this->getMagParamID($aItem['MagParam' . $i]);
				$level = SQLManager::ReadSingle("SELECT MLevel FROM SRO_VT_SHARD.._RefMagicOpt WHERE ID = $MAGID");
				$name = SQLManager::ReadSingle("SELECT MOptName128 FROM SRO_VT_SHARD.._RefMagicOpt WHERE ID = $MAGID");
				
				$_aMagOptLevel[$MAGID]['name'] = $name;
				$_aMagOptLevel[$MAGID]['sortkey'] = str_replace("MATTR_", "",$name);
				
				$_aMagOptLevel[$MAGID]['desc'] = (isset($blue_desc[$name])) ? $blue_desc[$name	] : '';
				
				
				
				//ECHO hexdec(substr(dechex($aItem['MagParam'. $i]),-2));
                $aData = self::convertBlue($aItem['MagParam' . $i], $_aMagOptLevel, $aSpecialInfo);
				$aBlues[$aData['sortkey'] . '_' . $aData['id']] = $aData;
            }
        }
        ksort($aBlues);
        return $aBlues;
    }
	
	private function getMagParamID($MagParam)
	{
		$hMagParam = (string)dechex($MagParam);
		$aString = str_split($hMagParam);
        if (($iNumber = count($aString)) < 11) {
            $iNumber++;
            for ($i = $iNumber; $i <= 11; $i++) {
                array_unshift($aString, 0);
            }
        }
        $i = $aString[0] . $aString[1] . $aString[2];
        $aData = str_split($i);

        for ($i = 0; $i <= 5; $i++) {
            unset($aString[$i]);
        }

        $iState = hexdec(implode('', $aString));
		
		
		return $iState;
	}
	
	protected static function convertBlue($iMagParam, $_aMagOptLevel, &$aSpecialInfo)
    {
        if ($iMagParam == 65) {
            $aSpecialInfo['MATTR_DUR'] = (isset($aSpecialInfo['MATTR_DUR'])) ? ($aSpecialInfo['MATTR_DUR'] + 400) : 400;
            return [
                'name' => 'Repair invalid (Maximum durability 400% increase)',
                'color' => 'ff2f51',
                'sortkey' => 0,
                'id' => 0
            ];
        }
        $hMagParam = (string)dechex($iMagParam);
        $aString = str_split($hMagParam);
        if (($iNumber = count($aString)) < 11) {
            $iNumber++;
            for ($i = $iNumber; $i <= 11; $i++) {
                array_unshift($aString, 0);
            }
        }
        $i = $aString[0] . $aString[1] . $aString[2];
        $aData = str_split($i);

        for ($i = 0; $i <= 5; $i++) {
            unset($aString[$i]);
        }

        $iState = hexdec(implode('', $aString));
        if (!isset($_aMagOptLevel[$iState])) {
			echo $iState;

            return [];
        }

        //DuraFix für 160%
        if ($_aMagOptLevel[$iState]['name'] == 'MATTR_DUR') {
            $iValue = implode('', $aData);
        } else {
            $iValue = implode('', $aData);
        }

        $iValue = hexdec($iValue);
        if ($_aMagOptLevel[$iState]['name'] == 'MATTR_REPAIR') {
            $iValue--;
        }
        $aSpecialInfo[$_aMagOptLevel[$iState]['name']] = (isset($aSpecialInfo[$_aMagOptLevel[$iState]['name']])) ? ($aSpecialInfo[$_aMagOptLevel[$iState]['name']] + $iValue) : $iValue;
        return [
            'name' =>  str_replace("%desc", $iValue, $_aMagOptLevel[$iState]['desc']),
            'color' => $_aMagOptLevel[$iState]['name'] == 'MATTR_DEC_MAXDUR' ? 'ff2f51' : '50cecd',
            'sortkey' => $_aMagOptLevel[$iState]['sortkey'],
            'id' => $iState
        ];
    }
	protected function getWhiteStats($aItem, $aSpecialInfo)
    {
        if ($aItem['TypeID2'] != 1) {
            return [];
        }
        $aWhiteStats = [];
        $iBinar = self::bin($aItem['Variance']);
        $aStats = strrev($iBinar);
        $aStats = str_split($aStats, 5);
        foreach ($aStats as $iBinar) {
            $iDezimal = bindec(strrev($iBinar));
            if ($iDezimal == 0) {
                $aWhiteStats[] = 0;
                continue;
            }
            $aWhiteStats[] = (int)($iDezimal * 100 / 31);
        }
        return self::convertToStats($aItem, $aWhiteStats, $aSpecialInfo);
    }
	
	protected static function bin($int)
    {
        $i = 0;
        $binair = "";
        while ($int >= pow(2, $i)) {
            $i++;
        }

        if ($i != 0) {
            $i = $i - 1; //max i
        }

        while ($i >= 0) {
            if ($int - pow(2, $i) < 0) {
                $binair = "0" . $binair;
            } else {
                $binair = "1" . $binair;
                $int = $int - pow(2, $i);
            }
            $i--;
        }
        return strrev($binair);
    }
	
	protected static function convertToStats($aItem, $aWhiteStats, $aSpecialInfo)
    {
        for ($i = 0; $i <= 6; $i++) {
            $aWhiteStats[$i] = isset($aWhiteStats[$i]) ? $aWhiteStats[$i] : 0;
        }

        $aItem['nOptValue'] = isset($aItem['nOptValue']) ? $aItem['nOptValue'] : 0;

        switch ($aItem['TypeID3']) {
            case self::WEAPON:
                $aStats = [
                    0 => 'Phy. atk. pwr. ' . self::calcOPTValue(self::getValue($aItem['PAttackMin_L'],
                            $aItem['PAttackMin_U'], $aWhiteStats[4]), $aItem['PAttackInc'],
                            ((int)$aItem['nOptValue'] + (int)$aItem['OptLevel'])) . ' ~ ' . self::calcOPTValue(self::getValue($aItem['PAttackMax_L'],
                            $aItem['PAttackMax_U'], $aWhiteStats[4]), $aItem['PAttackInc'],
                            ((int)$aItem['nOptValue'] + (int)$aItem['OptLevel'])) . ' (+' . $aWhiteStats[4] . '%)',
                    1 => 'Mag. atk. pwr. ' . self::calcOPTValue(self::getValue($aItem['MAttackMin_L'],
                            $aItem['MAttackMin_U'], $aWhiteStats[5]), $aItem['MAttackInc'],
                            ((int)$aItem['nOptValue'] + (int)$aItem['OptLevel'])) . ' ~ ' . self::calcOPTValue(self::getValue($aItem['MAttackMax_L'],
                            $aItem['MAttackMax_U'], $aWhiteStats[5]), $aItem['MAttackInc'],
                            ((int)$aItem['nOptValue'] + (int)$aItem['OptLevel'])) . ' (+' . $aWhiteStats[5] . '%)',
                    2 => 'Durability ' . $aItem['Data'] . '/' . self::getDuraMaxValue(self::getValue($aItem['Dur_L'],
                            $aItem['Dur_U'], $aWhiteStats[0]), $aSpecialInfo) . ' (+' . $aWhiteStats[0] . '%)',
                    3 => 'Attack rating ' . self::calcOPTValue(self::getBlueValue(self::getValue($aItem['HR_L'],
                            $aItem['HR_U'], $aWhiteStats[3]),
                            (isset($aSpecialInfo['MATTR_HR']) ? $aSpecialInfo['MATTR_HR'] : 0)), $aItem['HRInc'],
                            ((int)$aItem['nOptValue'] + (int)$aItem['OptLevel'])) . ' (+' . $aWhiteStats[3] . '%)',
                    4 => 'Critical ' . self::getValue($aItem['CHR_L'], $aItem['CHR_U'],
                            $aWhiteStats[6]) . ' (+' . $aWhiteStats[6] . '%)',
                    5 => 'Phy. reinforce ' . self::getValue($aItem['PAStrMin_L'], $aItem['PAStrMin_U'],
                            $aWhiteStats[1]) / 10 . ' % ~ ' . self::getValue($aItem['PAStrMax_L'], $aItem['PAStrMax_U'],
                            $aWhiteStats[1]) / 10 . ' % (+' . $aWhiteStats[1] . '%)',
                    6 => 'Mag. reinforce ' . self::getValue($aItem['MAInt_Min_L'], $aItem['MAInt_Min_U'],
                            $aWhiteStats[2]) / 10 . ' % ~ ' . self::getValue($aItem['MAInt_Max_L'],
                            $aItem['MAInt_Max_U'], $aWhiteStats[2]) / 10 . ' % (+' . $aWhiteStats[2] . '%)'
                ];
                if ($aItem['PAttackMin_L'] == 0) {
                    unset($aStats[0]);
                    unset($aStats[5]);
                    $aStats[4] = 'Critical 2 (+100%)';
                }
                if ($aItem['MAttackMin_L'] == 0) {
                    unset($aStats[1]);
                    unset($aStats[6]);
                }
                break;
            case self::SHIELD:
                $aStats = [
                    0 => 'Phy. def. pwr. ' . self::calcOPTValue(self::getValue($aItem['PD_L'] * 10, $aItem['PD_U'] * 10,
                            $aWhiteStats[4]), $aItem['PDInc'] * 10,
                            ((int)$aItem['nOptValue'] + (int)$aItem['OptLevel'])) / 10 . ' (+' . $aWhiteStats[4] . '%)',
                    1 => 'Mag. def. pwr. ' . self::calcOPTValue(self::getValue($aItem['MD_L'] * 10, $aItem['MD_U'] * 10,
                            $aWhiteStats[5]), $aItem['MDInc'] * 10,
                            ((int)$aItem['nOptValue'] + (int)$aItem['OptLevel'])) / 10 . ' (+' . $aWhiteStats[5] . '%)',
                    2 => 'Durability ' . $aItem['Data'] . '/' . self::getDuraMaxValue(self::getValue($aItem['Dur_L'],
                            $aItem['Dur_U'], $aWhiteStats[0]), $aSpecialInfo) . ' (+' . $aWhiteStats[0] . '%)',
                    3 => 'Blocking rate ' . self::getValue($aItem['BR_L'], $aItem['BR_U'],
                            $aWhiteStats[3]) . ' (+' . $aWhiteStats[3] . '%)',
                    4 => 'Phy. reinforce ' . self::getValue($aItem['PDStr_L'], $aItem['PDStr_U'],
                            $aWhiteStats[1]) / 10 . ' % (+' . $aWhiteStats[1] . '%)',
                    5 => 'Mag. reinforce ' . self::getValue($aItem['MDInt_L'], $aItem['MDInt_U'],
                            $aWhiteStats[2]) / 10 . ' % (+' . $aWhiteStats[2] . '%)'
                ];
                break;
            case 12:
            case self::ACC:
                $aStats = [
                    0 => 'Phy. absorption ' . self::calcOPTValue(self::getValue($aItem['PAR_L'] * 10,
                            $aItem['PAR_U'] * 10, $aWhiteStats[0]), $aItem['PARInc'] * 10,
                            ((int)$aItem['nOptValue'] + (int)$aItem['OptLevel'])) / 10 . ' (+' . $aWhiteStats[0] . '%)',
                    1 => 'Mag. absorption ' . self::calcOPTValue(self::getValue($aItem['MAR_L'] * 10,
                            $aItem['MAR_U'] * 10, $aWhiteStats[1]), $aItem['MARInc'] * 10,
                            ((int)$aItem['nOptValue'] + (int)$aItem['OptLevel'])) / 10 . ' (+' . $aWhiteStats[1] . '%)'
                ];
                break;
            default:
                $aStats = [
                    0 => 'Phy. def. pwr. ' . self::calcOPTValue(self::getValue($aItem['PD_L'] * 10, $aItem['PD_U'] * 10,
                            $aWhiteStats[3]), $aItem['PDInc'] * 10,
                            ((int)$aItem['nOptValue'] + (int)$aItem['OptLevel'])) / 10 . ' (+' . $aWhiteStats[3] . '%)',
                    1 => 'Mag. def. pwr. ' . self::calcOPTValue(self::getValue($aItem['MD_L'] * 10, $aItem['MD_U'] * 10,
                            $aWhiteStats[4]), $aItem['MDInc'] * 10,
                            ((int)$aItem['nOptValue'] + (int)$aItem['OptLevel'])) / 10 . ' (+' . $aWhiteStats[4] . '%)',
                    2 => 'Durability ' . $aItem['Data'] . '/' . self::getDuraMaxValue(self::getValue($aItem['Dur_L'],
                            $aItem['Dur_U'], $aWhiteStats[0]), $aSpecialInfo) . ' (+' . $aWhiteStats[0] . '%)',
                    3 => 'Parry rate ' . self::calcOPTValue(self::getBlueValue(self::getValue($aItem['ER_L'],
                            $aItem['ER_U'], $aWhiteStats[5]),
                            (isset($aSpecialInfo['MATTR_ER']) ? $aSpecialInfo['MATTR_ER'] : 0)), $aItem['ERInc'],
                            ((int)$aItem['nOptValue'] + (int)$aItem['OptLevel'])) . ' (+' . $aWhiteStats[5] . '%)',
                    4 => 'Phy. reinforce ' . self::getValue($aItem['PDStr_L'], $aItem['PDStr_U'],
                            $aWhiteStats[1]) / 10 . ' % (+' . $aWhiteStats[1] . '%)',
                    5 => 'Mag. reinforce ' . self::getValue($aItem['MDInt_L'], $aItem['MDInt_U'],
                            $aWhiteStats[2]) / 10 . ' % (+' . $aWhiteStats[2] . '%)'
                ];
                break;
        }
        return $aStats;
    }
	
	public function getItemInfoData()
	{
		
	}
	
	public function getInventoryAvatarData($charID)
	{
		
	}
	
}