<?php
	require_once("framework\init.php");
	
	require 'phpmailer/PHPMailerAutoload.php';
	
	$mail = new PHPMailer;


				$activation_code = Helper::genCode();
				$mail->Host       = "mail.privateemail.com";
				$mail->Port       = 587;
				$mail->SMTPSecure = "tls";
				$mail->SMTPAuth   = true;
				$mail->Username   = "admin@clash-online.net";
				$mail->Password   = "pw";
				$mail->IsSMTP();
				$mail->SMTPDebug  = 0;
				$mail->SetFrom('admin@clash-online.net', 'Clash Online');

				$mail->Subject    = "Confirm Account";
				
				
				
				$mail->MsgHTML(Helper::MsgFORM("$username","$activation_code"));
				

				$mail->AddAddress($email, $username);
				
				if(!$mail->Send()) {
				echo "Mailer Error: " . $mail->ErrorInfo;
				Helper::$status = "Failed";
				} else {
					$userip = Helper::getIP();

				}


			}
			
			echo Helper::GETJSON();
