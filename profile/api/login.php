<?php
	include_once("./Helper/socket.class.php");
	include_once("./Helper/pagination.php");
	include_once("./config/config.class.php");
	include_once("./database/mssql.class.php");
	include_once("./database/mysql.class.php");
	$Socket = new Socket();
	$config = new Config();
	$Paginator = new Paginator();
	SQLManager::Connect(config::$SQLHOST,config::$SQLUsername,config::$SQLPassword,config::$accDB);
	MYSQLManager::MysqliConnect(config::$MySQLHOST,config::$MySQLUsername,config::$MySQLPassword,config::$MySQLDatabase,config::$MySQLPorts);

	require "steam/apikey.php";
	require "steam/openid.php";

	$OpenID = new LightOpenID("localhost");
if (!function_exists('is_session_started')) {
    function is_session_started()
    {
        if (php_sapi_name() !== 'cli') {
            if (version_compare(phpversion(), '5.4.0', '>=')) {
                return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
            } else {
                return session_id() === '' ? FALSE : TRUE;
            }
        }
        return FALSE;
    }
}
if ( is_session_started() === FALSE ) session_start();
	if(!$OpenID->mode){
		
		if(isset($_GET['login'])){
			$OpenID->identity = "https://steamcommunity.com/openid";
			header("Location: ".$OpenID->authUrl()."");
		}
		
		if(!isset($_SESSION['T2SteamAuth'])){
			$login = "<div id=\"login\"><a href=\"api/login.php?login\"><img src=\"https://steamcommunity-a.akamaihd.net/public/images/signinthroughsteam/sits_small.png\"/></a></div>";
		}
	}
	elseif($OpenID->mode == "cancel"){
		echo "User has canceled Authentication";
		header("Location: ../index.php");
	}
	else
	{
		if(!isset($_SESSION['T2SteamAuth'])){
            $_SESSION['T2SteamAuth'] = $OpenID->validate() ? $OpenID->identity : null;
			$_SESSION['T2SteamID64'] = str_replace("https://steamcommunity.com/openid/id/", "", $_SESSION['T2SteamAuth']);
			
			if($_SESSION['T2SteamAuth'] != null)
			{
				$Steam64 = $_SESSION['T2SteamID64'];
				$profile = file_get_contents("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key={$api}&steamids={$Steam64}");
				$buffer = fopen("../cache/{$Steam64}.json", "w+");
				fwrite($buffer, $profile);
				fclose($buffer);
			}
			
			$query = "SELECT * FROM users WHERE SteamID = '".$_SESSION['T2SteamID64']."'";
			$account = MYSQLManager::MysqliReadAll($query);

			if(count($account) > 0)
			{
				$_SESSION['userid'] = $account[0]['id'];
				$_SESSION['username'] = $account[0]['username'];
				$_SESSION['email'] = $account[0]['email'];	
				$_SESSION['silkroad'] = $account[0]['SilkroadAccount'];
				MYSQLManager::MysqliExecute("UPDATE users SET last_signin = '".time()."'");
			}
		}
		if(!isset($_SESSION['username']))
			header("Location: ../index.php?page=register");
		else
			header("Location: ../index.php");
		$_SESSION['Message'] = 'You don\'t have a webaccount yet, please create an account';
	}
	
	if(isset($_GET['logout'])){
		unset($_SESSION['T2SteamAuth']);
		unset($_SESSION['T2SteamID64']);
		unset($_SESSION['steam']);
		header("Location: ../index.php");
	}
	if(isset($_SESSION['T2SteamAuth'])){
		$login = "<div id$\"login\"><a href=\"index.php?page=register&logout\">Logout</a></div>";
	}

	try {
		if(isset($_SESSION['T2SteamID64'])) {
		$content = file_get_contents("../cache/{$_SESSION['T2SteamID64']}.json");
            $_SESSION['steam'] = json_decode($content, FILE_USE_INCLUDE_PATH);
        }
		else {
            $steam = null;
        }
	}catch (Exception $e) {
		echo 'Exception reçue : '.  $e->getMessage(). "\n";
	}
	
	echo $login;
?>