<?php
session_start();
require('../helper/json.class.php');
require('./checker.class.php');
include_once("../security/security.class.php");
require('user.php');
include_once("../config/config.class.php");
include_once("../database/mssql.class.php");

$UserModel = new UserModel();
$Security = new Security();

SQLManager::Connect(config::$SQLHOST,config::$SQLUsername,config::$SQLPassword,config::$accDB);

echo $UserModel->CheckRegisterInputs();
