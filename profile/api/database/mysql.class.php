<?php
class MYSQLManager
{
	private static $mysqliConnection;
	
	public static function MysqliConnect($host, $username, $password, $DB,$port)
	{
		if(!self::$mysqliConnection)
		{
			self::$mysqliConnection = new mysqli("$host", "$username", "$password", "$DB",$port);
			if(self::$mysqliConnection->connect_errno)
			{
				echo "Failed to connect to MySQL: (" . self::$mysqliConnection->connect_errno . ") " . self::$mysqliConnection->connect_error;
				exit;
			}
		}
	}
	
	public static function MysqliConnection_Close()
	{
		mysqli_close(self::$mysqliConnection);
	}
	
	public static function MysqliExecute($query)
	{
		$result = mysqli_query(self::$mysqliConnection,$query);
		
		if(!$result)
		{
			echo "Error: Query execution failed. <br />";
			die( print_r( mysqli_error(self::$mysqliConnection), true));
			return false;
		}
		return true;
	}
	
	public static function EscapeString($string)
	{
		return mysqli_real_escape_string(self::$mysqliConnection,$string);
	}
	
	public static function MysqliReadSingleRow($query)
	{
		$result = mysqli_query(self::$mysqliConnection,$query);
		
		if(!$result)
		{
			echo "Error: Query execution failed. <br />";
			die( print_r( mysqli_error(self::$mysqliConnection), true));		
		}
		
		$row = mysqli_fetch_array($result,MYSQLI_NUM);
		
		return $row[0];
	}
	
	public static function MysqliReadArrayAssoc($query)
	{
		$result = mysqli_query(self::$mysqliConnection,$query);
		
		if(!$result)
		{
			echo "Error: Query execution failed. <br />";
			die( print_r( mysqli_error(self::$mysqliConnection), true));		
		}
		var_dump($result);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		
		return $row;
	}
	
	public static function MysqliReadArrayNum($query)
	{
		$result = mysqli_query(self::$mysqliConnection,$query);
		
		if(!$result)
		{
			echo "Error: Query execution failed. <br />";
			die( print_r( mysqli_error(self::$mysqliConnection), true));		
		}
		
		$row = mysqli_fetch_array($result,MYSQLI_NUM);
		
		return $row;
	}
	
	public static function MysqliReadAll($query)
	{
		$result = mysqli_query(self::$mysqliConnection,$query);
		
		if(!$result)
		{
			echo "Error: Query execution failed. <br />";
			echo $query;
			die( print_r( mysqli_error(self::$mysqliConnection), true));	
		}
		return mysqli_fetch_all($result,MYSQLI_ASSOC);
	}
}