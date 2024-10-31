<?php
error_reporting(0);
class paymentwall
{
	global $config;
	
	private $secret_key = $config->py_secretkey;
	
	public function clean($value)
	{
		return str_replace(array("'", '"', ";", ")", "(", "=", "%27", "%22"), "", $value);
	}
	
	public function __construct()
	{
		$DB = new Database();	
		header("HTTP/1.0 200 OK");
		//echo 'OK'; 
		$this->vars = $_GET;		
        // Only the IP's of the PaymentWall server can have access to this script.
		if(in_array($_SERVER['REMOTE_ADDR'], array("201.209.25.32", "66.220.10.2", "66.220.10.3", "174.36.92.186", "174.36.96.66", "174.36.92.187", "174.36.92.192", "174.37.14.28", "190.204.38.157")))
		{
			$uid = $this->clean($this->vars['uid']);
			$currency = $this->clean($this->vars['currency']);
			if($type == 0 || $type == 1)
			{

				//echo 'joined as 0 or 1';
				

				//echo 'joined as 0';
	
				//echo $this->vars['sig'];
				if($this->checkHash())
				{			
					//echo 'hash aproved.';
					if($this->accountExists())
					{				
						//echo 'acc exist.';
						SqlManager::Exec("INSERT INTO ".Config::$CMS_DB.".._PaymentWall_IPN (UserID, Currency, Type, Date) VALUES ('".$uid."', '".$currency."', 'Payment', GETDATE())");
						SqlManager::Exec("EXEC _SilkSystem '$uid','Silk',$currency");
						echo 'OK';
					}
				}
			}
			elseif($this->vars['type'] == 2)
			{
				if($this->checkHash())
				{
					if($this->accountExists())
					{
						SqlManager::Exec("INSERT INTO ".Config::$CMS_DB.".._PaymentWall_IPN (UserID, Currency, Type, Date) VALUES ('".$uid."', '".$currency."', 'Chargeback', GETDATE())");
						$userjid = SqlManager::ReadSingle("SELECT JID FROM Tb_User WHERE StrUserID = '$uid'");
						SqlManager::Exec("EXEC __ban_acc '$userjid','2099-01-01 00:01:00.000','Permanent BAN for Chargeback.'");

					}
				}
			}
		}
		else echo 'You are not able to use this system, u have '.$_SERVER['REMOTE_ADDR'].'.';		
	}

	private function checkHash()
	{
		
		if($this->vars['sig'] ==  md5("currency=".$this->vars['currency']."is_test=".$this->vars['is_test']."ref=".$this->vars['ref']."sign_version=2"."type=".$this->vars['type']."uid=".$this->vars['uid'].$this->secret_key))
		{
			return true;
		}
	}

	private function accountExists()
	{
		$DB = new Database();	
		if($DB->NumRows("SELECT JID FROM Tb_User WHERE StrUserID = '".$this->clean($this->vars['uid'])."'") == 1)
		{
			return true;
		}
	}
}
new paymentwall();

?>