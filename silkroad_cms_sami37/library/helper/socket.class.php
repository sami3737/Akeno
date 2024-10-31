<?php
class Socket
{
	
	public function checkState($hostname,$port) 
	{
		$timeout = 0.1;
		$socket = @fsockopen($hostname,$port,$errno,$errstr,$timeout);
		if($socket) return true;
		return false;
	}
}
	
?>