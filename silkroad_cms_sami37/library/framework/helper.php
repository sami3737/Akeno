<?php
class Helper
{
	public static $status;
	public static $reason;
	public static $message;
	
	public static function GETJSON()
	{
		return json_encode(["status"=>self::$status,"reason"=>self::$reason,"message"=>self::$message]);
	}
	
	public static function getIP()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        return $_SERVER['REMOTE_ADDR'];
    }
	public static function genCode(int $length = 32)
	{
		$String = '';
        for ($i = 1; $i < $length; $i++) {
            $String .= substr('1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', rand(0, 61), 1);
        }
        return $String;
	}
	
	public static function MsgFORM($username,$activationcode)
	{
		$text = '<div style="font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;width:100%!important;height:100%;line-height:1.6;background-color:#f2f2f2;margin:0" bgcolor="#f2f2f2">

<table style="font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;width:100%;background-color:#f2f2f2;margin:0" bgcolor="#f2f2f2">
	<tbody>
		<tr style="font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;margin:0">
			<td style="font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;vertical-align:top;margin:0" valign="top"></td>
			<td width="600" style="font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;vertical-align:top;display:block!important;max-width:600px!important;clear:both!important;width:100%!important;margin:0 auto" valign="top">
				<div style="font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;max-width:600px;display:block;margin:0 auto;padding:10px">
					<span class="HOEnZb"><font color="#888888"></font></span><table width="100%" cellpadding="0" cellspacing="0" style="font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;border-radius:3px;color:#606060!important;background-color:#fff;margin:0;border:1px solid #e9e9e9" bgcolor="#fff"><tbody><tr style="font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;margin:0"><td style="font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;vertical-align:top;margin:0;padding:10px" valign="top">
							<span class="HOEnZb"><font color="#888888">
								</font></span><table width="100%" cellpadding="0" cellspacing="0" style="font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;margin:0"><tbody><tr style="font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;margin:0"><td style="font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;vertical-align:top;margin:0;padding:0 0 20px" valign="top">
										<h1 style="font-family:Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:22px!important;color:#606060!important;line-height:115%;font-weight:600!important;letter-spacing:-1px;text-align:center;margin:20px 0 5px;padding:0" align="center">Just one more step...</h1>
									</td>
								</tr><tr style="font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;margin:0"><td style="font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;vertical-align:top;margin:0;padding:0 0 20px" valign="top">
										<h2 style="font-family:Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:18px!important;color:#606060!important;line-height:115%;font-weight:600!important;letter-spacing:-.5px;text-align:center;margin:20px 0 5px;padding:0" align="center">
										'.$username.'
										</h2>
									</td>
								</tr><tr style="text-align:center;font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;margin:0" align="center"><td style="font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;vertical-align:top;margin:0;padding:0 0 20px" valign="top">
										<a href="http://clash-online.net/register.php?code='.$activationcode.'" style="font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;color:#fff;text-decoration:none;line-height:2;font-weight:bold;text-align:center;display:inline-block;border-radius:5px;text-transform:capitalize;background-color:#6dc6dd;margin:0;border-color:#6dc6dd;border-style:solid;border-width:10px 20px" target="_blank">Activate Your Account</a>
									</td>
								</tr><tr style="text-align:center;font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;margin:0" align="center"><td style="font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;vertical-align:top;margin:0;padding:0 0 20px" valign="top">
										<h3 style="font-family:Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:16px!important;color:#606060!important;line-height:115%;font-weight:600!important;letter-spacing:-.5px;text-align:center;margin:20px 0 5px;padding:0" align="center">
										Your activation code is</h3> 
										'.$activationcode.'
									</td></tr></tbody></table></td></tr></tbody></table>
									<span class="HOEnZb"><font color="#888888"><div style="font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;width:100%;clear:both;color:#999;margin:0">
					<table width="100%" style="font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;margin:0"><tbody style="font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;margin:0"><tr style="font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;margin:0"><td style="font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:12px;vertical-align:top;text-align:center;margin:0;padding:0 0 20px" align="center" valign="top"> 
					<a href="http://clash-online.net/" style="font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:12px;color:#999;text-decoration:underline;margin:0" target="_blank" data-saferedirecturl="https://www.google.com/url?hl=en-GB&amp;q=https://leakforums.org&amp;source=gmail&amp;ust=1493240120706000&amp;usg=AFQjCNF2uijkn6N8ktCKWfHu2rDjyUxiGQ">Clash Online</a></td>
						</tr></tbody></table></div>
		</font></span></td>
		<td style="font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;vertical-align:top;margin:0" valign="top"></td>
	</tr>
</tbody></table></div>';
	return $text;
	}
}