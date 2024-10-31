<?php
class JSONHelper
{
	public static $status;
	public static $reason;
	public static $message;
	
	public static function GetJSON()
	{
			return json_encode(['status'=>self::$status,'reason'=>self::$reason,'message'=>self::$message]);
	}
}
