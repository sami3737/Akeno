<?php

if(isset($_SESSION['T2SteamID64']) && !empty($_SESSION['T2SteamID64']) && $_SESSION['T2SteamID64'] != '')
{
	$query = "SELECT * FROM users WHERE SteamID = '".$_SESSION['T2SteamID64']."'";
	$account = MYSQLManager::MysqliReadAll($query);
	
	if(count($account) > 0)
	{
		$_SESSION['username'] = $account[0]['username'];
		$_SESSION['email'] = $account[0]['email'];	
		$_SESSION['silkroad'] = $account[0]['SilkroadAccount'];
	}
}
?>
