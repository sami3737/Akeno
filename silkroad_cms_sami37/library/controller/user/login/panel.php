<?php
class Panel extends Controller
{
	public function index()
	{
		
	}
	
	public function account()
	{
		if(isset($_SESSION['username']))
			$this->view("user/auth/index");
		

	}
	
	public function changepassword()
	{
		$userModel = $this->model("user");

		echo $userModel->changegamepassword();
	}

	public function Donate()
	{
		if(isset($_SESSION['username']))
			$this->view("user/donate/index");
		
	}
	
	public function superrewardipn()
	{
		require_once(LIBRARY_PATH."/ipn/superreward.php");
	}
		
	public function paypalipn()
	{
		require_once(LIBRARY_PATH."/ipn/paypal.php");
	}
}