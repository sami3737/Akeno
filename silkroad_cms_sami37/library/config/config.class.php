<?php


class Config
{

	//--- MSSQL DATABASE Connection ---//
    public static $SQLHOST = "127.0.0.1:1433"; //MSSQL HOST	
	public static $SQLUsername = "sa"; //MSSQL Username
	public static $SQLPassword = "test123456"; //MSSQL Password
	public static $accDB = "VSRO_Account"; //MSSQL ACC-DB
	public static $shardDB = "VSRO_SHARD"; //MSSQL Shard-DB
	public static $logDB = "VSRO_LOG"; //MSSQL LOG-DB
	public static $CMS_DB = "SilkroadCMS"; //MSSQL CMS-DB

	
	//--- Server Status ---//
	var $ServerIP = "localhost"; //Server RealIP
	var $LoginPort = 0; //Server Login Port
	var $FakePlayer = 0; //Server Fake Player
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
	var $news_totalRecords = 0;
	var $news_rightLinks = 0;
	var $news_leftLinks = 0;
	
	//--- Ranking ---//
	var $ranking_totalRecords = 0;
	var $ranking_rightLinks = 0;
	var $ranking_leftLinks = 0;
	
	//--- Donate ---//
	var $site_http = "";
	var $sr_secretkey = "";
	var $sr_projectkey = "";
	var $py_secretkey = "";
	var $py_projectkey = "";
	var $paypal_email= "";
	

	//--- General ---//
	var $Serverame = "";
	var $FaceBook = "";
	var $Youtube = "";
	var $Epvp = "";

	var $Title = "Black Lagoon Gaming"; //Site Title
	
	public function __construct()
	{
		
		//--- MSSQL DATABASE Connection ---//
		self::$SQLHOST = Ini::iniReader("MSSQL","host");
		self::$SQLUsername = Ini::iniReader("MSSQL","username");
		self::$SQLPassword = Ini::iniReader("MSSQL","password");
		self::$accDB = Ini::iniReader("MSSQL","accDB");
		self::$shardDB = Ini::iniReader("MSSQL","shardDB");
		self::$logDB = Ini::iniReader("MSSQL","logDB");
		self::$CMS_DB = Ini::iniReader("MSSQL","cmsDB");

	
		//--- Server Status ---//
		$this->ServerIP = Ini::iniReader("ServerStatus","ip_address");
		$this->LoginPort = Ini::iniReader("ServerStatus","login_port");
		$this->Fakelayer = Ini::iniReader("ServerStatus","FakePlayer");
		$this->MaxPlayer = Ini::iniReader("ServerStatus","FakePlayer");
		$this->ServerCap = Ini::iniReader("ServerStatus","server_cap");
		$this->ServerRace = Ini::iniReader("ServerStatus","server_race");
		$this->ServerExp = Ini::iniReader("ServerStatus","server_exp");
		$this->ServerExpParty = Ini::iniReader("ServerStatus","server_partyexp");
		$this->ServerGoldDrop = Ini::iniReader("ServerStatus","server_golddrop");
		$this->ServerItemDrop = Ini::iniReader("ServerStatus","server_itemdrop");
		$this->ServerTradeGoods = Ini::iniReader("ServerStatus","server_tradegoods");
		$this->ServerMaxPlus = Ini::iniReader("ServerStatus","server_maxplus");
		$this->ServerPclimit = Ini::iniReader("ServerStatus","server_pclimit");
				
		//--- News ---//
		$this->news_totalRecords = Ini::iniReader("News","totalRecords");
		$this->news_rightLinks = Ini::iniReader("News","rightLinks");
		$this->news_leftLinks = Ini::iniReader("News","leftLinks");
		
		//--- Ranking ---//
		$this->ranking_totalRecords = Ini::iniReader("Ranking","totalRecords");
		$this->ranking_rightLinks = Ini::iniReader("Ranking","rightLinks");
		$this->ranking_leftLinks = Ini::iniReader("Ranking","leftLinks");
		
		//--- Donate ---//
		$this->site_http = Ini::iniReader("Donate","siteLink");
		$this->sr_secretkey = Ini::iniReader("Donate","superreward.secretkey");
		$this->sr_projectkey = Ini::iniReader("Donate","superreward.apphash");
		$this->py_secretkey = Ini::iniReader("Donate","paymentwall.secretkey");
		$this->py_projectkey = Ini::iniReader("Donate","paymentwall.projectkey");
		$this->paypal_email = Ini::iniReader("Donate","paypal.email");
		
		

		//--- General ---//
		$this->Serverame = Ini::iniReader("General","servername");
		$this->FaceBook = Ini::iniReader("General","facebook");
		$this->Youtube = Ini::iniReader("General","youtube");
		$this->Epvp = Ini::iniReader("General","epvp");
		$this->Title = $this->Serverame;
	}
	
	
	function setTitle($page)
	{
		$this->Title = $this->Title.$page;
		echo "<title>$this->Title</title>";		
	}
}

?>