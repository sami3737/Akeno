<?php
session_start();
include_once("./api/Helper/socket.class.php");
include_once("./api/Helper/pagination.php");
include_once("./api/config/config.class.php");
include_once("./api/database/mssql.class.php");
include_once("./api/database/mysql.class.php");
$Socket = new Socket();
$config = new Config();
$Paginator = new Paginator();
SQLManager::Connect(config::$SQLHOST,config::$SQLUsername,config::$SQLPassword,config::$accDB);
MYSQLManager::MysqliConnect(config::$MySQLHOST,config::$MySQLUsername,config::$MySQLPassword,config::$MySQLDatabase,config::$MySQLPorts);

include('./header.php');

include('./menu.html');
if(!isset($_GET['page']) || empty($_GET['page'])){
	include('content/main.php');
}
elseif(isset($_GET['page']) && !empty($_GET['page'])){
	if($_GET['page'] == 'panel')
		include('./content/profile.php');
	elseif(file_exists('content/'.$_GET['page'].'.php')){
		include('content/'.$_GET['page'].'.php');
	}else{
		include('content/main.php');
	}
}

MYSQLManager::MysqliConnection_Close();
include('./footer.html');