<?php
class Ini
{
	public static function iniReader($section,$key)
	{
		//$ini_parser = parse_ini_file()
		$path = APPLICATION_PATH."/configs/application.ini";
		
		$ini_parser = parse_ini_file($path,true);
		
		RETURN $ini_parser[$section][$key];
			
	}
}