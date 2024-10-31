<?php
class Application{
	
	protected $controller = 'home';

	protected $method = 'index';
	
	protected $params = [];
	
	
	public function __construct()
	{
		global $routes;
		global $config;
		$url = explode('/',$this->getCurrentURI());
		
		if(empty($url[0]))
			$url[0] = $this->controller;
		
		if(array_key_exists($url[0],$routes))
		{

			$item = $url[0];
			
			if(isset($url[1]) && array_key_exists($url[0]."/".$url[1],$routes))
				$item = $item."/".$url[1];


			$controller_PATCH = $routes[$item][0];
			$controller_Title = $routes[$item]['pageTitle'];
			$controller_Method = $routes[$item]['is_Post'];



			if(file_exists($controller_PATCH))
			{
				$this->controller = $url[0];
				unset($url[0]);	
			}
			
			require_once $controller_PATCH;

			if(!$controller_Method)
			{
				echo '<title> '.$config->Title.$controller_Title.'</title>';
				require_once LIBRARY_PATH.'/views/template/head.html';
				require_once LIBRARY_PATH.'/views/template/top.html';
				require_once LIBRARY_PATH.'/views/template/slider.html';	
			}
			
			$this->controller = new $this->controller;
	

			if(isset($url[1]))
			{
				if(method_exists($this->controller,$url[1]))
				{
					$this->method = $url[1];
					unset($url[1]);
				}
			}
			
			$this->params = $url ? array_values($url) : [];
			
			call_user_func_array([$this->controller, $this->method], $this->params);
			
			if(!$controller_Method)
			{
				require_once LIBRARY_PATH.'/views/sidebar/start.html';
				require_once LIBRARY_PATH.'/views/sidebar/serverstats.html';				
				require_once LIBRARY_PATH.'/views/sidebar/serverinfo.html';				
				require_once LIBRARY_PATH.'/views/sidebar/servertime.phtml';				
				require_once LIBRARY_PATH.'/views/sidebar/end.html';				
				require_once LIBRARY_PATH.'/views/template/footer.html';
			}
		}
		else
		{
				require_once LIBRARY_PATH.'/controller/error.php';
				$this->controller = new $this->controller;
				return;
		}
	}

	
	public function getCurrentURI()
	{
		$basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
		$uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));
		if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
		$uri = trim($uri, '/');
		return $uri;
	}
	
}