<?php
class Controller
{
	
	public function model($model)
	{
		require_once LIBRARY_PATH.'/models/'. $model . '.php';
		$modelName = $model.'Model';
		return new $modelName();
	}
	
	public function view($view,$data = [])
	{
		
		foreach($data as $var=>$con)
			${$var} = $con;
			
		require_once LIBRARY_PATH.'/views/'. $view . '.php';
		

	}
	
	public function uri($index = 0){
		return $this->getURI($index);
	}
	
		
	
	public function getURI($index = 0)
	{
		$uri = explode("/",$_SERVER['REQUEST_URI']);
		if(count($uri) >= $index)
			return $uri[$index];
		
		return '';
	}
}