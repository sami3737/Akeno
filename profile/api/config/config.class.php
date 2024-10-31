<?php


class Config
{

	//--- MSSQL DATABASE Connection ---//
    public static $SQLHOST = "localhost"; //MSSQL HOST	
	public static $SQLUsername = "sa"; //MSSQL Username
	public static $SQLPassword = "test123456"; //MSSQL Password
	public static $accDB = "VSRO_Account"; //MSSQL ACC-DB
	public static $shardDB = "VSRO_SHARD"; //MSSQL Shard-DB
	public static $logDB = "VSRO_LOG"; //MSSQL LOG-DB
	public static $CMS_DB = "SilkroadCMS"; //MSSQL CMS-DB
	
	//--- MySQL DATABASE Connection ---//
    public static $MySQLHOST = "localhost"; //MSSQL HOST	
	public static $MySQLUsername = "root"; //MSSQL Username
	public static $MySQLPassword = ""; //MSSQL Password
	public static $MySQLPorts = "3306"; //MSSQL Password
	public static $MySQLDatabase = "private_message"; //MSSQL Password

	
	//--- Server Status ---//
	var $ServerIP = ""; //Server RealIP
	var $LoginPort = 0; //Server Login Port
	var $MaxPlayer = 0; //Server Max Player
	var $ServerCap = 0;
	var $ServerRace = 0;
	var $ServerExp = 0;
	var $ServerExpParty = 0;
	var $ServerGoldDrop = 0;
	var $ServerItemDrop = 0;
	var $ServerTradeGoods = 0;
	var $ServerMaxPlus = 0;
	var $ServerPclimit = 0;
	
	//--- News ---//
	var $news_totalRecords = 4;
	var $news_rightLinks = 0;
	var $news_leftLinks = 0;
	
	//--- Ranking ---//
	var $ranking_totalRecords = 0;
	var $ranking_rightLinks = 0;
	var $ranking_leftLinks = 0;
	
	//--- General ---//
	var $Serverame = "";
	var $FaceBook = "";
	var $Youtube = "";
	var $Epvp = "";
	
	//--- Donate ---//
	var $site_http = "http://localhost/";
	var $sr_secretkey = "";
	var $sr_projectkey = "";
	var $py_secretkey = "";
	var $py_projectkey = "";
	var $paypal_email = "";
	
	var $Title = "Black Lagoon Gaming"; //Site Title
	
	function setTitle($page)
	{
		$this->Title = $this->Title.$page;
		echo "<title>$this->Title</title>";		
	}
}

?>