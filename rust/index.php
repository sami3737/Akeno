<?php
session_start();
include_once("api/Helper/socket.class.php");
include_once("api/Helper/pagination.php");
include_once("api/config/config.class.php");
include_once("api/database/mssql.class.php");
$Socket = new Socket();
$config = new Config();
$Paginator = new Paginator();
SQLManager::Connect(config::$SQLHOST,config::$SQLUsername,config::$SQLPassword,config::$accDB);

include('content/header.php');

include('content/menu.html');

if(!isset($_GET['page']) || empty($_GET['page'])){
	include_once('content/main.php');
}elseif(isset($_GET['page']) && !empty($_GET['page'])){
	if($_GET['page'] == 'panel')
		include('user/panel.php');
	elseif(file_exists('content/'.$_GET['page'].'.php')){
		include('content/'.$_GET['page'].'.php');
	}else{
		include('content/main.php');
	}
}

include('content/footer.html');