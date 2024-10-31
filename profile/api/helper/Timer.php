<?php
class Timer
{
    static function getNextFortressWar()
    {
        return self::getNextFightDay('fortresswar');
    }

	static function getServerTime()
	{
		return strtotime("now");
	}
    public function getNextFightDay($sKey)
    {
		// initilaize
		$fightDay =  Ini::iniReader("Timer",$sKey."_day");
		$fightHour =  Ini::iniReader("Timer",$sKey."_hour");
		$fightMin =  Ini::iniReader("Timer",$sKey."_min");
		
        $aFortressDay = explode(',', $fightDay);
        $iNextTime = PHP_INT_MAX;
        foreach ($aFortressDay as $sDay) {
            if (date('l', time()) == $sDay) {
                if (time() <= ($time = mktime($fightHour, $fightMin, 0, date("n"), date("j"),
                        date("Y")))
                ) {
                    $iNextTime = $time;
                    break;
                }
            }
            $iTime = mktime($fightHour, $fightMin, 0, date('n', strtotime('next ' . $sDay)),
                date('j', strtotime('next ' . $sDay)), date('Y', strtotime('next ' . $sDay)));
            if ($iNextTime > $iTime) {
                $iNextTime = $iTime;
            }
        }
        return $iNextTime;
    }

    public static function getNextCTFWar()
    {
        return self::nextFight(explode(',', Ini::iniReader("Timer","ctf_hour")), Ini::iniReader("Timer","ctf_min"));
    }

    public static function nextFight($aHours, $iM)
    {
        sort($aHours);
        foreach ($aHours as $iKey => $iHour) {
            if (($iTime = mktime($iHour, $iM, 0, date("n"), date("j"), date("Y"))) >= time()) {
                return $iTime;
            }
        }
        foreach ($aHours as $iKey => $iHour) {
            if (($iTime = mktime($iHour, $iM, 0, date("n"), date('j', strtotime('+1 day')), date("Y"))) >= time()) {
                return $iTime;
            }
        }
        foreach ($aHours as $iKey => $iHour) {
            if (($iTime = mktime($iHour, $iM, 0, date("m") + 1, 1, date("Y"))) >= time()) {
                return $iTime;
            }
        }
    }

    public static function getNextMedusa()
    {
		return self::nextFight(explode(',', Ini::iniReader("Timer","medusa_hour")), Ini::iniReader("Timer","medusa_min"));
    }

    public static function getNextRoc()
    {
        return self::getNextFightDay('roc');
    }
}