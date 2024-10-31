<?php 
session_start();
DEFINE("DIVINE_PATH",REALPATH(str_replace("public","",__DIR__)));
DEFINE("APPLICATION_PATH",REALPATH(str_replace("public","application",__DIR__)));
DEFINE("LIBRARY_PATH",REALPATH(str_replace("public","library",__DIR__)));
DEFINE("PUBLIC_PATH",REALPATH(__DIR__));

require_once(LIBRARY_PATH."/autoload.php");

date_default_timezone_set('Europe/Amsterdam'); 


$App = new Application();

?>
