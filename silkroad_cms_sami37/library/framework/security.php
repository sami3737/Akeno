<?php
class Security 
{
	public static function secure($data)
	{
		if (!isset($data) or empty($data))
			return '';
		if (is_numeric($data))
           return $data;
        
		$non_displayables = array(
			'/%0[0-8bcef]/',
			'/%1[0-9a-f]/',
			'/[\x00-\x08]/',
			'/\x0b/',
			'/\x0c/',
			'/[\x0e-\x1f]/'
			);
		foreach ($non_displayables as $regex)
           $data = preg_replace($regex, '', $data);
		$data = str_replace("'", "''", $data);
		return $data;
	}
		
	public static function checkChars($data) {
		$check = preg_match("/^[a-zA-Z0-9]+$/", $data);
		if ($check == 0) {
			return false;
		} else {
			return true;
		}
	}

	public static function checkEmail($data) 
	{
		$check = preg_match("/^[a-zA-Z]\w+(\.\w+)*\@\w+(\.[0-9a-zA-Z]+)*\.[a-zA-Z]{2,4}$/", $data);
		if ($check == 0) {
			return false;
		} else {
			return true;
		}
	}
	
	public static function checkMinLength($data, $length) 
	{
		if (strlen($data) < $length) {
			return false;
		} else {
			return true;
		}
	}
	
	public static function checkMaxLength($data, $length) 
	{
		
		if (strlen($data) > $length) {
				return false;
			} else {
				return true;
			}
	}
}
