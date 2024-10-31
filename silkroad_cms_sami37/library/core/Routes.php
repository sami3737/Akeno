<?php
class Routes{
	
	$routes = [];
	
	public function __construct($array)
	{
		$this->Add($Array);
		
		
	}
	
	public function Add($array);
	{
		$this->routes = $array;
	}
	
	public function getURI()
	{
		$basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
		$uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));
		if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
		$uri = trim($uri, '/');
		return $uri;
	}
}