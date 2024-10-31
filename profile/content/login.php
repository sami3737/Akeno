<?php
session_start();
include_once("../api/Helper/socket.class.php");
include_once("../api/Helper/json.class.php");
include_once("../api/config/config.class.php");
require('../api/security/security.class.php');
include_once("../api/database/mysql.class.php");

$Socket = new Socket();
$config = new Config();
$Security = new Security();

MYSQLManager::MysqliConnect(config::$MySQLHOST,config::$MySQLUsername,config::$MySQLPassword,config::$MySQLDatabase,config::$MySQLPorts);

function login()
{
	global $Security;
	
	$username = empty($_POST['user']) ? "" : $Security->secure($_POST['user']);
	$password = empty($_POST['pass']) ? "" : $Security->secure($_POST['pass']);
	if(strlen($username) > 0 && strlen($password) > 0)
	{
		$query = "SELECT * FROM users WHERE (username = '".$username."' OR email = '".$username."') and password = '".$password."'";
		$account = MYSQLManager::MysqliReadAll($query);
	}
	else
		$account = array();
	$username_exist = count($account);
	if($username_exist > 0)
	{
		if(isset($_SESSION['steam']))
		{
			$steamid = $_SESSION['steam']['response']['players'][0]['steamid'];
			$avatar = $_SESSION['steam']['response']['players'][0]['avatarfull'];
		}
		else
		{
			$steamid = $account[0]['SteamID'];
			$avatar = $account[0]['avatar'];
		}
		if(isset($_SESSION['silkroad']))
			$silkroad = $_SESSION['silkroad'];
		else
			$silkroad = $account[0]['SilkroadAccount'];
		MYSQLManager::MysqliExecute("UPDATE users SET SteamID = '".$steamid."', SilkroadAccount = '".$silkroad."', avatar = '".$avatar."', last_signin = '".time()."' WHERE username = '".$username."' OR email = '".$username."'");
	}
	else
	{
		JSONHelper::$reason[] = 'Wrong username and/or password';
	}
	if(!empty(JSONHelper::$reason))
	{
		JSONHelper::$status = "Failed";
	}
	else
	{
		JSONHelper::$status = "Successfully";
		
		$_SESSION['username'] = $username;
		$id = MYSQLManager::MysqliReadAll("SELECT id FROM users WHERE username = '".$username."' OR email = '".$username."'");
		$_SESSION['userid'] = $id[0]['id'];
		JSONHelper::$message = "Succussfully connected/linked to your account.";
	}
	
	return JSONHelper::GetJSON();
}
if(isset($_POST['action']))
	if($_POST['action'] == 'login')
		echo login();
