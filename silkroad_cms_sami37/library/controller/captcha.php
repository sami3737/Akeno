<?php
class Captcha extends Controller{
	
	public function index()
	{
		
	}
	public function gen()
	{
		$_SESSION['captcha'] = captcha();
		header("Content-type: application/json");
		echo json_encode(['captcha'=>$_SESSION['captcha']['image_src']]);
	}
	
}