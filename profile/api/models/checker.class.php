<?php

class ACC_Checker extends JSONHelper
{

	public function CheckRegisterInputs($username,$password,$password2,$email,$email2)
	{
		global $Security;
		
		$username = $Security->secure($username);
		$password = $Security->secure($password);
		
		if(!$Security->checkChars($username) || !$Security->checkChars($password)) $this->reason[] = "invalid symbols.";
		if(!$Security->checkEmail($email)) $this->reason[] = "Email is not valid.";
		if(strtolower($email) != strtolower($email2)) $this->reason[] = "Email not match.";
		if(strtolower($password) != strtolower($password2)) $this->reason[] = "Password not match.";
		
		if($this->reason != null)
		{
			$this->status = "Failed";
		}
		
		else
		{
			$this->status = "success";
			$this->message = "Register Successfully";
		}
		
		return $this->GetJSON();

	}
	
	public function CheckLogin($username,$password)
	{
		
	}

}