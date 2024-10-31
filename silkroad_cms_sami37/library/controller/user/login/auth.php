<?php
class Auth extends Controller{
	
	public function index()
	{
		
	}
	
	public function login($username = "",$password = "")
	{
		$userModel = $this->model("user");
		
		echo $userModel->checkLoginData($username,$password);
	}
	
	public function logout()
	{
		if(isset($_SESSION['username']))
		{
			unset($_SESSION['username']);
			echo'<meta http-equiv="refresh" content="0.1;url=/">';
		}
		else
		{
			
		}		
	}
}