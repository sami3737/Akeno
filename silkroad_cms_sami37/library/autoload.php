<?php
//echo __DIR__;
/*
$routes = [];
$routes['news'] = "home";
$routes['news/*'] = "home/page";
$routes['ranking'] = "home/page";
$routes['ranking/*'] = "home/page";
*/

$routes = require_once(APPLICATION_PATH."/configs/routes.php");

require_once("Config\config.class.php");
require_once("captcha\captcha.php");
require_once("Helper\json.class.php");
require_once("Helper\security.class.php");
require_once("Helper\inireader.php");
require_once("Helper\pagination.php");
require_once("Helper\socket.class.php");
require_once("Database\mssql.class.php");
require_once("Helper\sidebar.php");
require_once("Helper\Timer.php");
require_once 'core/Controller.php';
require_once 'core/Model.php';
require_once 'core/Application.php';

$config = new Config();
$Security = new Security();
$Socket = new Socket();

SQLManager::Connect(config::$SQLHOST,config::$SQLUsername,config::$SQLPassword,config::$accDB);
