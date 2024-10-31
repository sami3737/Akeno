<?php
class Database
{
	private static $_connString;
	public static function Connect($host, $username, $password, $db)
	{
		if(!self::$_connString)
		{
			$arrayConn = ["Database"=> "$db","UID"=> "$username","PWD"=> "$password"];
			self::$_connString = sqlsrv_connect($host, $arrayConn);
			if(!self::$_connString)
			{
				echo "Connection could not be established.<br />";
				die( print_r( sqlsrv_errors(), true) );
			}
		}
		else { exit; }
	}
	
	public static function Close()
	{
		sqlsrv_close(self::$_connString);
	}
	
	public static function Execute($query)
	{
		$exec = sqlsrv_query(self::$_connString, $query);
		if(!$exec)
		{
			echo "SQLSRV:: Error in Execute() function";
			die( print_r( sqlsrv_errors($exec), true) );
		}
	}
	
	public static function ReadSingleRow($query)
	{
		$result = sqlsrv_query(self::$_connString, $query);
		if(!$result)
		{
			echo "SQLSRV:: Error in ReadSingleRow() function";
			die( print_r( sqlsrv_errors($result),true) );
		}
		$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_NUMERIC);
		
		return $row[0];
	}
}