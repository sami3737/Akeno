<?php
session_start();
include_once("./api/Helper/socket.class.php");
include_once("./api/Helper/json.class.php");
include_once("./api/config/config.class.php");
require('./api/security/security.class.php');
include_once("./api/database/mysql.class.php");

$Socket = new Socket();
$config = new Config();
$Security = new Security();

MYSQLManager::MysqliConnect(config::$MySQLHOST,config::$MySQLUsername,config::$MySQLPassword,config::$MySQLDatabase,config::$MySQLPorts);

function checkregisterinputs()
{
	global $Security;
	
	$username = empty($_POST['username']) ? "" : $Security->secure($_POST['username']);
	$password = empty($_POST['password']) ? "" : $Security->secure($_POST['password']);
	$password2 = empty($_POST['password2']) ? "" : $_POST['password2'];
	$email = empty($_POST['email']) ? "" : $Security->secure($_POST['email']);
	$email2 = empty($_POST['email2']) ? "" : $_POST['email2'];
	$secret = empty($_POST['secretcode']) ? "" : $_POST['secretcode'];
	$code = empty($_POST['code']) ? "" : $_POST['code'];	
	$session_code = isset($_SESSION['captcha']['code']) ? $_SESSION['captcha']['code'] : "YOUWILLNOTGETIT";
	
	$query = "SELECT * FROM users WHERE username = '".$username."'";
	$account = MYSQLManager::MysqliReadAll($query);
	$query = "SELECT * FROM users WHERE email = '".$email."'";
	$emails = MYSQLManager::MysqliReadAll($query);
	$username_exist = count($account);
	$email_exist = (isset($emails[0]) && isset($emails[0]['email']) ? $emails[0]['email'] : '');		
		
	if(!$Security->checkChars($username) || !$Security->checkChars($password)) JSONHelper::$reason[] = "Fields contains forbidden. (make sure to use letters and numbers only)";
	if(strlen($username) < 3) JSONHelper::$reason[] = "Your username is shorter than 3 characters.";
	if(strlen($username) > 16) JSONHelper::$reason[] = "Your username is longer than 16 characters.";
	if(strlen($password) < 6) JSONHelper::$reason[] = "Your password is shorter than 6 characters.";
	if(strlen($password) > 16) JSONHelper::$reason[] = "Your password is longer than 16 characters.";
	if($username_exist > 0) JSONHelper::$reason[] = "Your username is already exists.";
	if(strlen($email_exist) > 0) JSONHelper::$reason[] = "Your email address is already exists.";
	if($password != $password2) JSONHelper::$reason[] = "Passwords are not the same.";
	if($email != $email2) JSONHelper::$reason[] = "Emails are not the same.";
	if(!$Security->checkEmail($email)) JSONHelper::$reason[] = "Invalid email address";
	if(strtolower($code) != strtolower($session_code)) JSONHelper::$reason[] = "Robot verification failed, please try again.";
	if(isset($_SESSION['steam']))
	{
		$steamid = $_SESSION['steam']['response']['players'][0]['steamid'];
		$avatar = $_SESSION['steam']['response']['players'][0]['avatarfull'];
	}
	else
	{
		$steamid = '';
		$avatar = '';
	}
	if(isset($_SESSION['silkroad']))
		$silkroad = $_SESSION['silkroad'];
	else
		$silkroad = '';
	if(!empty(JSONHelper::$reason))
	{
		JSONHelper::$status = "Failed";
	}
	else
	{
		JSONHelper::$status = "Successfully";
		$query = 'INSERT INTO users(username, password, email, SteamID, SilkroadAccount, avatar, signup_date) values ("'.$username.'", "'.$password.'", "'.$email.'", "'.$steamid.'", "'.$silkroad.'", "'.$avatar.'", "'.time().'")';
		MYSQLManager::MysqliExecute($query);
		
		$_SESSION['username'] = $username;
		$id = MYSQLManager::MysqliReadAll("SELECT id FROM users WHERE username = '".$username."'");
		$_SESSION['userid'] = $id[0]['id'];

		JSONHelper::$message = "Registration completed successfully.";
	}
	
	return JSONHelper::GetJSON();
}
echo checkregisterinputs();

