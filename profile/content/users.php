<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="./css/pm.css" rel="stylesheet" title="Style" />
        <title>List of users</title>
    </head>
    <body>
    	<div class="header">
        	<a href="index.php"><img src="./img/logo.png" alt="Members Area" /></a>
	    </div>
        <div class="content">
This is the list of members:
<table>
    <tr>
    	<th>Id</th>
    	<th>Username</th>
    	<th>Email</th>
    </tr>
<?php
//We get the IDs, usernames and emails of users
$req = MYSQLManager::MysqliReadAll('select id, username, email from users where id != '.$_SESSION['userid']);
for($i = 0; $i < count($req); $i++)
{
?>
	<tr>
    	<td><?php echo $req[$i]['id']; ?></td>
    	<td><a href="index.php?page=profile&id=<?php echo $req[$i]['id']; ?>"><?php echo htmlentities($req[$i]['username'], ENT_QUOTES, 'UTF-8'); ?></a></td>
    	<td><?php echo htmlentities($req[$i]['email'], ENT_QUOTES, 'UTF-8'); ?></td>
    </tr>
<?php
}
?>
</table>
		</div>
		<div class="foot"><a href="index.php?page=panel">Go Home</a></div>
	</body>
</html>