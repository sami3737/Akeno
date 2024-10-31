<?php
	global $config;
	$paypal_mail = $config->paypal_email;

	ini_set('log_errors', true);
	ini_set('error_log', dirname(__FILE__).'/ipn_errors.log');
	
// tell the IPN listener to use the PayPal test sandbox
	$use_sandbox = false;
// try to process the IPN POST
if($_POST)
{
	
	if($use_sandbox) {
		$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
	} else {
		$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
	}

	$raw_post_data = file_get_contents('php://input');
	$raw_post_array = explode('&', $raw_post_data);
	
	foreach($raw_post_array as $keyval)
	{
		$keyval = explode('=', $keyval);
		if (count($keyval) == 2) $_POST[$keyval[0]] = urldecode($keyval[1]);
	}
	
	// read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
	
	$req = 'cmd=_notify-validate';
	
	if (function_exists('get_magic_quotes_gpc'))
	{
		$get_magic_quotes_exists = true;
	}
	
	foreach($_POST as $key => $value)
	{
		if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1)
		{
			$value = urlencode(stripslashes($value));
		}
		else
		{
			$value = urlencode($value);
		}
		//echo"Key: $key Value: $value";
		$req.= "&$key=$value";
	}
	$ch = curl_init($paypal_url);
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
	curl_setopt($ch, CURLOPT_SSLVERSION,6);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,10);
	curl_setopt($ch, CURLOPT_TIMEOUT,60);
	curl_setopt($ch, CURLOPT_USERAGENT, 'PayPal-PHP-SDK');
	curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST,'TLSv1');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
	curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
	$res = curl_exec($ch);
	$respone = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	if ($res == "VERIFIED")
        {
            $transaction_id = $_POST['txn_id'];
            $payerid = $_POST['payer_id'];
            $custom = $_POST['custom'];
            $firstname = $_POST['first_name'];
            $lastname = $_POST['last_name'];
            $payeremail = $_POST['payer_email'];
            $recieveremail = $_POST['receiver_email'];
            $paymentdate = $_POST['payment_date'];
            $paymentstatus = $_POST['payment_status'];
            $mc_gross = $_POST['mc_gross'];
            $mdate= date('Y-m-d h:i:s',strtotime($paymentdate));
			
			if ($paymentstatus != 'Completed') { 
				error_log("not compelete");
				// simply ignore any IPN that is not completed
				exit(0); 
			}
			
			if (strtolower($recieveremail) != strtolower($paypal_mail)) {
				error_log("email doesn't match");
				exit(0);
			}
			
			if ($paymentstatus == 'Completed') 
			{ 			
				// payment compelete
				///////////////////
				///////////////////
				//
				SqlManager::Exec("EXEC SILKROAD_CMS.._Donate_Action '$custom','Paypal',$mc_gross");
				SqlManager::Exec("INSERT INTO SILKROAD_CMS.._PayPal_IPN VALUES ('$transaction_id','$payerid','$firstname','$lastname','$payeremail',GETDATE(),'$paymentstatus','$mc_gross','$custom')");
			}

		}

}
	else {
    // manually investigate the invalid IPN
		echo'<meta http-equiv="refresh" content="0.1;url=/">';
	}
?>