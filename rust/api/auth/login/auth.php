<?php
session_start();
include_once("../../Helper/socket.class.php");
include_once("../../Helper/json.class.php");
include_once("../../config/config.class.php");
include_once("../../database/mssql.class.php");
include_once("../../security/security.class.php");
$Socket = new Socket();
$config = new Config();
$Security = new Security();

require("../function.php");
function login($username = "",$password = "")
{	
	echo checkLoginData($username,$password);
}

function logout()
{
	if(isset($_SESSION['username']))
	{
		unset($_SESSION['username']);
		echo'<meta http-equiv="refresh" content="0.1;url=/">';
	}	
}

if(isset($_POST['action']))
{
	if($_POST['action'] == 'login')
	{
		echo login($_POST['user'], $_POST['pass']);
	}
}
if(isset($_GET['action']))
{
	if($_GET['action'] == 'logout')
	{
		logout();
	}
}