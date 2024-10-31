<?php
	#--- Error reporting ---#
	ini_set('log_errors', true);
	ini_set('error_log', dirname(__FILE__).'/superipn.log');

	#--- Include settings ---#
	global $config;
	#--- define secret key ---#
	$SECREYKEY = $config->sr_secretkey;
	
	
	$transaction_id = $_REQUEST['id'];
	$user_id        = $_REQUEST['uid'];
	$offer_id       = $_REQUEST['oid']; // completed offer or payment method
	$new_currency   = $_REQUEST['new'];
	$total = $_REQUEST['total']; // Total number of in-game currency your user has earned on this App.
	$hash_signature = $_REQUEST['sig'];

	if(!(is_numeric($transaction_id) && is_numeric($offer_id) && is_numeric($new_currency) && is_numeric($total))) {
    exit('0'); // Fail.
	}
	
	$generatedKey = sprintf(
    '%s:%s:%s:%s',
    $transaction_id,
    $new_currency,
    $user_id,
    $SECREYKEY
	);

	if (md5($generatedKey) == $hash_signature) {
		
	#--- Filter ---#

	error_log("User $user_id purchased $new_currency coins using $offer_id (txn: $transaction_id)\n");	
	#--- Log payment ---#
	
	SqlManager::Exec("INSERT INTO ".Config::$CMS_DB.".._SuperRewards_IPN (Username, Amount, Date) VALUES('$user_id', '$new_currency', GETDATE())");
	SqlManager::Exec("EXEC SRO_NAVIFILTER.._SilkSystem '$user_id','Silk',$new_currency");

	 die('1');
	}

die('0');
?>