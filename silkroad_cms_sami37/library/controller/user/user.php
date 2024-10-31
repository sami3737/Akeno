<?php
class User extends Controller{
	
	public function index()
	{
		
	}
	
	public function Register()
	{
		if(!isset($_SESSION['username']))
			$this->view("user/register");
	}
	
	public function ResetPassword()
	{
		
	}
	
	public function checkregisterinputs()
	{
		$userModel = $this->model("user");
		if($_SERVER['REQUEST_METHOD'] == "POST")
		{
			echo $userModel->checkregisterinputs();

		}
		
		else
			echo'<meta http-equiv="refresh" content="0.1;url=/">';
		
	}

}