<?php
session_start();

include_once("../models/user.php");
include_once("../Helper/socket.class.php");
include_once("../Helper/json.class.php");
include_once("../Helper/security.class.php");
include_once("../config/config.class.php");
include_once("../database/mssql.class.php");
$username = $_SESSION['username'];
$config = new Config();
$Socket = new Socket();
$userModel = new UserModel();
$Security = new Security();

SQLManager::Connect(config::$SQLHOST,config::$SQLUsername,config::$SQLPassword,config::$accDB);

echo $userModel->changegamepassword();

