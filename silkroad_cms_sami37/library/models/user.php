<?php
class UserModel extends model
{
	#region getting region
	private function getIp()
    {

        return "127.0.0.1";
    }
	
	private function getUserJID($username)
	{
		$JID = 0;
		if(SqlManager::NumRows("SELECT JID FROM TB_USER WHERE StrUserID = '$username'") > 0)
		{
			$JID = SqlManager::ReadSingle("SELECT JID FROM TB_USER WHERE StrUserID = '$username'");
		}
		
		return $JID;
	}
	
	private function getUserEmail($username)
	{
		$email = "";
		
		if($this->getUserJID($username) > 0)
			$email = SqlManager::ReadSingle("SELECT Email FROM TB_USER WHERE StrUserID = '$username'");
		
		return $email;
	}
	
	private function getUserPassword($username)
	{
		$password = "";
		
		if($this->getUserJID($username) > 0)
			$password = SqlManager::ReadSingle("SELECT Password FROM TB_USER WHERE StrUserID = '$username'");
		
		return $password;
	}
	
	private function UpdateUserPassword($username,$password)
	{
		SqlManager::Exec("UPDATE TB_USER SET Password = '$password' WHERE StrUserID = '$username'");
	}
	
	private function getUsername($Charname)
	{
		
	}
	#endregion
	
	#region isBlocked , is Activate , is Admin, is Supporter
	public function isBlocked($username)
	{
		
		$isBlocked = "No";
		$blockDate = date("d/m/y H:i", time());
		$reason = "";		
		
		$JID = $this->getUserJID($username);
		
		if(SqlManager::NumRows("SELECT * FROM _BlockedUser WHERE UserJID = $JID") > 0)
		{
			
			IF(SqlManager::NumRows("SELECT UserJID FROM _BlockedUser WHERE UserJID = $JID AND timeEnd <= GETDATE()") == 0)
			{
				$isBlocked = "Yes";
				$blockDate = SqlManager::ReadSingle("SELECT timeEnd FROM _BlockedUser WHERE UserJID = $JID")->format("d/m/y H:i");
				$reason = SqlManager::ReadSingle("SELECT Description FROM _Punishment WHERE UserJID = $JID");
			}
			else
			{
				$isBlocked = "No";
			}
		}
		else
		{
			$isBlocked = "No";
		}
		
		return ["isBlocekd" => $isBlocked, "Reason" => $reason ,"TimeEnd" => $blockDate];
	}
	
	public function isActivate($username)
	{
		
	}
	
	public function isAdmin($username)
	{
		
	}
	
	public function isSupporter($username)
	{
		
	}
	#endregion
	
	
	private function createrUser($username,$password,$email,$secretcode)
	{
		$userip = $this->getIP();
		$md5pw = md5($password);
		SqlManager::Exec("INSERT INTO TB_User (StrUserID,password,email,SecretCode,sec_primary,sec_content,RealPW,regtime,reg_ip) values ('$username', '$md5pw','$email','$secretcode',3,3,'$password',GETDATE(),'$userip')");
	}
	
	#region checking login data
	public function checkLoginData($username,$password)
	{
		global $Security;
				
		$username = $Security->secure($username);
		$password = md5($password);
		
		$blockArray = $this->isBlocked($username);	

		JSONHelper::$status = "Failed";
		JSONHelper::$reason = $blockArray['isBlocekd'];
		

		if(!$Security->checkChars($username))
		{
			JSONHelper::$status = "Failed";
			JSONHelper::$reason = "invalid symbol.";
		}
		
		else
		{
			if($blockArray['isBlocekd'] == "Yes")
			{
				JSONHelper::$status = "Failed";
				JSONHelper::$reason = "You blocked ".$blockArray['TimeEnd']." Reason: ".$blockArray['Reason']."";
			}
			else
			{
				if(SqlManager::NumRows("SELECT * FROM Tb_User WHERE StrUserID = '$username' AND Password = '$password'") > 0)
				{
					$_SESSION['username'] = $username;
					JSONHelper::$status = "Successful";
				}
				else
				{
					JSONHelper::$status = "Failed";
					JSONHelper::$reason = "username or password not correct";
				}
			}
		}		
		return JSONHelper::GetJSON();
	}
	#endregion
	
	#region check register inputs
	public function checkregisterinputs()
	{
		global $Security;
		
		$username = empty($_POST['username']) ? "" : $Security->secure($_POST['username']);
		$password = empty($_POST['password']) ? "" : $Security->secure($_POST['password']);
		$password2 = empty($_POST['password2']) ? "" : $_POST['password2'];
		$email = empty($_POST['email']) ? "" : $Security->secure($_POST['email']);
		$email2 = empty($_POST['email2']) ? "" : $_POST['email2'];
		$secret = empty($_POST['secretcode']) ? "" : $_POST['secretcode'];
		$code = empty($_POST['code']) ? "" : $_POST['code'];	
		$session_code = isset($_SESSION['captcha']['code']) ? $_SESSION['captcha']['code'] : "YOUWILLNOTGETIT";
		

		$username_exist = $this->getUserJID($username);
		
		$email_exist = $this->getUserEmail($username);		
			
		if(!$Security->checkChars($username) || !$Security->checkChars($password)) JSONHelper::$reason[] = "Fields contains forbidden. (make sure to use letters and numbers only)";
		if(strlen($username) < 3) JSONHelper::$reason[] = "Your username is shorter than 3 characters.";
		if(strlen($username) > 16) JSONHelper::$reason[] = "Your username is longer than 16 characters.";
		if(strlen($password) < 6) JSONHelper::$reason[] = "Your password is shorter than 6 characters.";
		if(strlen($password) > 16) JSONHelper::$reason[] = "Your password is longer than 16 characters.";
		if(strlen($secret) < 3) JSONHelper::$reason[] = "Your secret code is shorter than 3 characters.";
		if(strlen($secret) > 10) JSONHelper::$reason[] = "Your secret code is longer than 10 characters.";
		if($username_exist > 0) JSONHelper::$reason[] = "Your username is already exists.";
		if(strlen($email_exist) > 0) JSONHelper::$reason[] = "Your email address is already exists.";
		if($password != $password2) JSONHelper::$reason[] = "Passwords are not the same.";
		if($email != $email2) JSONHelper::$reason[] = "Emails are not the same.";
		if(!$Security->checkEmail($email)) JSONHelper::$reason[] = "Invalid email address";
		if(strtolower($code) != strtolower($session_code)) JSONHelper::$reason[] = "Robot verification failed, please try again.";
		
		if(!empty(JSONHelper::$reason))
		{
			JSONHelper::$status = "Failed";
		}
		else
		{
			JSONHelper::$status = "Successfully";
			$this->createrUser($username,$password,$email,$secret);
			JSONHelper::$message = "Registration completed successfully.";
		}
		
		return JSONHelper::GetJSON();
	}
	#endregion
	
	public function changegamepassword()
	{
		global $Security;
		
		$username = (isset($_SESSION['username'])) ? $_SESSION['username'] : "";
		$curpw = empty($_POST['curpw']) ? "" : $Security->secure($_POST['curpw']);
		$newpw = empty($_POST['password']) ? "" : $Security->secure($_POST['password']);
		$newpw2 = empty($_POST['password2']) ? "" : $Security->secure($_POST['password2']);
		
		
		if(strlen($newpw) < 6) JSONHelper::$reason[] = "Your password is shorter than 6 characters.";
		if(strlen($newpw) > 16) JSONHelper::$reason[] = "Your password is longer than 16 characters.";
		if($newpw != $newpw2) JSONHelper::$reason[] = "Passwords are not the same.";
		if(strtolower($this->getUserPassword($_SESSION['username'])) != strtolower(md5($curpw))) JSONHelper::$reason[] = "current password not correct.";
		
		if(!empty(JSONHelper::$reason))
		{
			JSONHelper::$status = "Failed";

		}
		
		else
		{
			JSONHelper::$status = "Successfully";
			JSONHelper::$message = "Password has been changed successfully";
			
			$this->UpdateUserPassword($username, md5($newpw));
			
		}
		
		return JSONHelper::GetJSON();
			
	}
	
}