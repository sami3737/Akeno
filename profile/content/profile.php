<?php
if(isset($_GET['id']))
if($_SESSION['userid'] == $_GET['id'])
	echo '<script type="text/javascript">
	window.location.href = "./index.php?page=panel";
	</script>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="./css/pm.css" rel="stylesheet" title="Style" />
        <title>New PM</title>
    </head>
    <body>
    	<div class="header">
        	<a href="index.php"><img src="./img/logo.png" alt="Members Area" /></a>
	    </div>
        <div class="content">
<?php
//We check if the users ID is defined
if(isset($_GET['id']) || (isset($_GET['page']) && $_GET['page'] == 'panel'))
{
	if(isset($_SESSION['userid']) && (isset($_GET['page']) && $_GET['page'] == 'panel'))
		$id = $_SESSION['userid'];
	else
		$id = intval($_GET['id']);
	//We check if the user exists
	$dn = MYSQLManager::MysqliReadAll('select username, Detail, email, avatar, signup_date from users where id="'.$id.'"');
	if(isset($dn[0]))
	{
		//We display the user datas
		?>
		<div class="profile">
		<table>
			<tr>
				<td><?php
					if($dn[0]['avatar']!='')
					{
						echo '<img src="'.htmlentities($dn[0]['avatar'], ENT_QUOTES, 'UTF-8').'" alt="Avatar" style="max-width:100px;max-height:100px;" /><br />';
						echo '<a style="text-align:center;">'.htmlentities($dn[0]['username'], ENT_QUOTES, 'UTF-8').'</a>';
					}
					else
					{
						echo 'This user dont have an avatar.';
					}
					?>
				</td>
				<td style="padding-top:0px;vertical-align:top;">Email: <?php echo htmlentities($dn[0]['email'], ENT_QUOTES, 'UTF-8'); ?><br />
					Member since: <?php echo date('Y/m/d',$dn[0]['signup_date']); ?>
				</td>
			</tr>
			<tr>
				<td colspan=2>
				<?php echo nl2br($dn[0]['Detail']); ?>
				</td>
			</tr>
			<tr>
				<td colspan=2>
				Put here social button
				</td>
			</tr>
		</table>
		</div>
		<?php if(isset($_SESSION['userid']) && (isset($_GET['page']) && $_GET['page'] == 'panel'))
		{
			?>
		<div class="profile1">
		<?php
		//We check if the user is logged
		if(isset($_SESSION['username']))
		{
			//We check if the form has been sent
			if(isset($_POST['username'], $_POST['password'], $_POST['passverif'], $_POST['email'], $_POST['avatar']))
			{
				//We remove slashes depending on the configuration
				if(get_magic_quotes_gpc())
				{
					$_POST['username'] = stripslashes($_POST['username']);
					$_POST['password'] = stripslashes($_POST['password']);
					$_POST['passverif'] = stripslashes($_POST['passverif']);
					$_POST['email'] = stripslashes($_POST['email']);
					$_POST['avatar'] = stripslashes($_POST['avatar']);
				}
				//We check if the two passwords are identical
				if($_POST['password']==$_POST['passverif'])
				{
					//We check if the password has 6 or more characters
					if(strlen($_POST['password'])>=6)
					{
						//We check if the email form is valid
						if(preg_match('#^(([a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+\.?)*[a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+)@(([a-z0-9-_]+\.?)*[a-z0-9-_]+)\.[a-z]{2,}$#i',$_POST['email']))
						{
							//We protect the variables
							$password = MYSQLManager::EscapeString($_POST['password']);
							$username = MYSQLManager::EscapeString($_POST['username']);
							$email = MYSQLManager::EscapeString($_POST['email']);
							$avatar = MYSQLManager::EscapeString($_POST['avatar']);
							$detail = MYSQLManager::EscapeString($_POST['detail']);
							//We check if there is no other user using the same username
							$result = MYSQLManager::MysqliReadAll('select count(*) as nb from users where username="'.$username.'"');
							$dn = $result[0];
							//We check if the username changed and if it is available
							if($dn['nb']==0 or $_POST['username']==$_SESSION['username'])
							{
								//We edit the user informations
								if(MYSQLManager::MysqliExecute('update users set detail="'.$detail.'", password="'.$password.'", email="'.$email.'", avatar="'.$avatar.'" where id="'.MYSQLManager::EscapeString($_SESSION['userid']).'"'))
								{
									//We dont display the form
									$form = false;
									//We delete the old sessions so the user need to log again
									
									echo '<div class="message">Your informations have successfuly been updated.<br /><div>';
								}
								else
								{
									//Otherwise, we say that an error occured
									$form = true;
									$message = 'An error occurred while updating your informations.';
								}
							}
							else
							{
								//Otherwise, we say the username is not available
								$form = true;
								$message = 'The username you want to use is not available, please choose another one.';
							}
						}
						else
						{
							//Otherwise, we say the email is not valid
							$form = true;
							$message = 'The email you entered is not valid.';
						}
					}
					else
					{
						//Otherwise, we say the password is too short
						$form = true;
						$message = 'Your password must contain at least 6 characters.';
					}
				}
				else
				{
					//Otherwise, we say the passwords are not identical
					$form = true;
					$message = 'The passwords you entered are not identical.';
				}
			}
			else
			{
				$form = true;
			}
			if($form)
			{
				//We display a message if necessary
				if(isset($message))
				{
					echo '<strong>'.$message.'</strong>';
				}
				//If the form has already been sent, we display the same values
				if(isset($_POST['username'],$_POST['password'],$_POST['email']))
				{
					$pseudo = htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8');
					if($_POST['password']==$_POST['passverif'])
					{
						$password = htmlentities($_POST['password'], ENT_QUOTES, 'UTF-8');
					}
					else
					{
						$password = '';
					}
					$email = htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8');
					$username = htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8');
					$avatar = htmlentities($_POST['avatar'], ENT_QUOTES, 'UTF-8');
					$detail = htmlentities($_POST['detail'], ENT_QUOTES, 'UTF-8');
				}
				else
				{
					//otherwise, we display the values of the database
					$dnn = MYSQLManager::MysqliReadAll('select username,password,email,detail,avatar from users where username="'.$_SESSION['username'].'"');
					$username = htmlentities($dnn[0]['username'], ENT_QUOTES, 'UTF-8');
					$password = htmlentities($dnn[0]['password'], ENT_QUOTES, 'UTF-8');
					$email = htmlentities($dnn[0]['email'], ENT_QUOTES, 'UTF-8');
					$detail = htmlentities($dnn[0]['detail'], ENT_QUOTES, 'UTF-8');
					$avatar = htmlentities($dnn[0]['avatar'], ENT_QUOTES, 'UTF-8');
				}
				//We display the form
		?>
		<div class="content">
			<form action="?page=panel" method="post">
				You can edit your informations:<br />
				<div class="center">
					<label for="username">Username</label><input type="text" name="username" id="username" value="<?php echo $username; ?>" readonly="readonly"/><br />
					<label for="password">Password<span class="small">(6 characters min.)</span></label><input type="password" name="password" id="password" value="<?php echo $password; ?>" /><br />
					<label for="passverif">Password<span class="small">(verification)</span></label><input type="password" name="passverif" id="passverif" value="<?php echo $password; ?>" /><br />
					<label for="email">Email</label><input type="text" name="email" id="email" value="<?php echo $email; ?>" /><br />
					<label for="detail">Personnal details</label><textarea type="text" name="detail" id="detail"><?php echo $detail; ?></textarea><br />
					<label for="avatar">Avatar<span class="small">(optional)</span></label><input type="text" name="avatar" id="avatar" value="<?php echo $avatar; ?>" /><br />
					<input type="submit" value="Send" />
				</div>
			</form>
		</div>
		<?php
			}
		}
		?>
		</div>
		<?php
		}
		else{
			echo '<div class="profile1"></div>';
		}
		//We add a link to send a pm to the user
		if(isset($_SESSION['username']) && !(isset($_GET['page']) && $_GET['page'] == 'panel'))
		{
		?>
		<br /><a href="index.php?page=new_pm&recip=<?php echo urlencode($dn[0]['username']); ?>" class="big">Send a PM to "<?php echo htmlentities($dn[0]['username'], ENT_QUOTES, 'UTF-8'); ?>"</a>
		<br /><a href="?page=users">Go to user list</a>

		<?php
		}
	}
	else
	{
		echo 'This user dont exists.';
	}
}
else
{
	if(isset($_SESSION['username']) && isset($_SESSION['userid']))
	{
		$nb_new_pm = MYSQLManager::MysqliReadAll('select count(*) as nb_new_pm from pm where ((user1="'.$_SESSION['userid'].'" and user1read="no") or (user2="'.$_SESSION['userid'].'" and user2read="no")) and id2="1"');

		$nb_new_pm = $nb_new_pm[0]['nb_new_pm'];
		?>
		<a href="?page=edit_infos">Edit my personnal informations</a><br />
		<a href="?page=list_pm">My personnal messages(<?php echo $nb_new_pm; ?> unread)</a><br />
		<?php
	}
}
?>
		</div>
