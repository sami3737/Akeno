<?php
class Security 
{
	function secure($data)
	{
		if (!isset($data) or empty($data))
			return '';
		if (is_numeric($data))
           return $data;
        
		$non_displayables = array(
			'/%0[0-8bcef]/', // url encoded 00-08, 11, 12, 14, 15
			'/%1[0-9a-f]/', // url encoded 16-31
			'/[\x00-\x08]/', // 00-08
			'/\x0b/', // 11
			'/\x0c/', // 12
			'/[\x0e-\x1f]/' // 14-31
			);
		foreach ($non_displayables as $regex)
           $data = preg_replace($regex, '', $data);
		$data = str_replace("'", "''", $data);
		return $data;
	}
		
	function checkChars($data) {
		$check = preg_match("/^[a-zA-Z0-9]+$/", $data);
		if ($check == 0) {
			return false;
		} else {
			return true;
		}
	}

	function checkEmail($data) 
	{
		$check = preg_match("/^[a-zA-Z]\w+(\.\w+)*\@\w+(\.[0-9a-zA-Z]+)*\.[a-zA-Z]{2,4}$/", $data);
		if ($check == 0) {
			return false;
		} else {
			return true;
		}
	}
	
	function checkMinLength($data, $length) 
	{
		if (strlen($data) < $length) {
			return false;
		} else {
			return true;
		}
	}
	
	function checkMaxLength($data, $length) 
	{
		
		if (strlen($data) > $length) {
				return false;
			} else {
				return true;
			}
	}
}
?>