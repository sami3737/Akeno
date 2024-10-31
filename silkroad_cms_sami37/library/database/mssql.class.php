<?php
Class SQLManager
{
	private static $sqlsrvConnection;
	
	public static function Connect($host, $username, $password, $DB)
	{
		if(!self::$sqlsrvConnection)
		{
			$connection_array = array("Database"=> "$DB","UID" => "$username","PWD" => "$password");
			self::$sqlsrvConnection = sqlsrv_connect($host, $connection_array);
			if(!self::$sqlsrvConnection)
			{
				echo "Connection could not be established.<br />";
				die( '<pre>'.print_r( sqlsrv_errors(), true).'</pre>');
			}
		}
		else { exit; }
	}
	
	public static function Connection_Close()
	{
		sqlsrv_close(self::$sqlsrvConnection);
	}
	
	public static function Exec($query)
	{
		$stmt = sqlsrv_query(self::$sqlsrvConnection, $query);
		if(!$stmt)
		{
			echo "SQLSRV:: Exec (Function) --";
			die( print_r(sqlsrv_errors(), true));
		}
	}
	
	public static function resource($query)
	{		
		$stmt = sqlsrv_query(self::$sqlsrvConnection, $query);
		if(!$stmt)
		{
			echo "Error: Query execution failed. <br />";
			die( print_r( sqlsrv_errors(), true));		
		}
		return $stmt;
	}

	function NumRows($query)
	{
		
		$params = array();
		$opt = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
		$stmt = sqlsrv_query(self::$sqlsrvConnection, $query, $params, $opt);
		$rowCount = sqlsrv_num_rows($stmt);

		if( $stmt === false ) {
			die( $query.'<br>'. print_r( sqlsrv_errors(self::$sqlsrvConnection), true));
		}
		return $rowCount;		
	}
	
	
	public static function ReadSingle($query)
	{
		$stmt = sqlsrv_query(self::$sqlsrvConnection, $query);
		if(!$stmt)
		{
			echo "SQLSRV:: ReadSingleRow (Function) --";
			die( print_r(sqlsrv_errors(), true));
		}
		$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);
		
		return $row[0];
	}
	
	public static function ReadArrayAssoc($query)
	{
		$stmt = sqlsrv_query(self::$sqlsrvConnection, $query);
		if(!$stmt)
		{
			echo "SQLSRV:: ReadArrayAssoc (Function) --";
			die( print_r(sqlsrv_errors(), true));
		}

		return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
	}
	
	public static function ReadArrayNumeric($query)
	{
				$stmt = sqlsrv_query(self::$sqlsrvConnection, $query);
		if(!$stmt)
		{
			echo "SQLSRV:: ReadArrayNumeric (Function) --";
			die( print_r(sqlsrv_errors(), true));
		}
		$row[] = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);
		
		return $row;
	}

	
	
}
