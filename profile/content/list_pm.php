<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="./css/pm.css" rel="stylesheet" title="Style" />
        <title>Personnal Messages</title>
    </head>
    <body>
    	<div class="header">
        	<a href="index.php"><img src="./img/logo.png" alt="Members Area" /></a>
	    </div>
        <div class="content">
<?php
//We check if the user is logged
if(isset($_SESSION['username']))
{
//We list his messages in a table
//Two queries are executes, one for the unread messages and another for read messages
$req1 = MYSQLManager::MysqliReadAll('select m1.id, m1.title, m1.timestamp, count(m2.id) as reps, users.id as userid, users.username from pm as m1, pm as m2,users where ((m1.user1="'.$_SESSION['userid'].'" and m1.user1read="no" and users.id=m1.user2) or (m1.user2="'.$_SESSION['userid'].'" and m1.user2read="no" and users.id=m1.user1)) and m1.id2="1" and m2.id=m1.id group by m1.id order by m1.id desc');
$req2 = MYSQLManager::MysqliReadAll('select m1.id, m1.title, m1.timestamp, count(m2.id) as reps, users.id as userid, users.username from pm as m1, pm as m2,users where ((m1.user1="'.$_SESSION['userid'].'" and m1.user1read="yes" and users.id=m1.user2) or (m1.user2="'.$_SESSION['userid'].'" and m1.user2read="yes" and users.id=m1.user1)) and m1.id2="1" and m2.id=m1.id group by m1.id order by m1.id desc');
?>
This is the list of your messages:<br />
<a href="index.php?page=new_pm" class="link_new_pm">New PM</a><br />
<h3>Unread Messages(<?php echo intval(count($req1)); ?>):</h3>
<table>
	<tr>
    	<th class="title_cell">Title</th>
        <th>Nb. Replies</th>
        <th>Participant</th>
        <th>Date of creation</th>
    </tr>
<?php
//We display the list of unread messages
for($i = 0; $i < count($req1); $i++)
{
?>
	<tr>
    	<td class="left"><a href="index.php?page=read_pm&id=<?php echo $req1[$i]['id']; ?>"><?php echo htmlentities($req1[$i]['title'], ENT_QUOTES, 'UTF-8'); ?></a></td>
    	<td><?php echo $req1[$i]['reps']-1; ?></td>
    	<td><a href="index.php?page=profile&id=<?php echo $req1[$i]['userid']; ?>"><?php echo htmlentities($req1[$i]['username'], ENT_QUOTES, 'UTF-8'); ?></a></td>
    	<td><?php echo date('Y/m/d H:i:s' ,$req1[$i]['timestamp']); ?></td>
    </tr>
<?php
}
//If there is no unread message we notice it
if(intval(count($req1))==0)
{
?>
	<tr>
    	<td colspan="4" class="center">You have no unread message.</td>
    </tr>
<?php
}
?>
</table>
<br />
<h3>Read Messages(<?php echo intval(count($req2)); ?>):</h3>
<table>
	<tr>
    	<th class="title_cell">Title</th>
        <th>Nb. Replies</th>
        <th>Participant</th>
        <th>Date or creation</th>
    </tr>
<?php
//We display the list of read messages
for($i = 0; $i < count($req2); $i++)
{
?>
	<tr>
    	<td class="left"><a href="index.php?page=read_pm&id=<?php echo $req2[$i]['id']; ?>"><?php echo htmlentities($req2[$i]['title'], ENT_QUOTES, 'UTF-8'); ?></a></td>
    	<td><?php echo $req2[$i]['reps']-1; ?></td>
    	<td><a href="index.php?page=profile&id=<?php echo $req2[$i]['userid']; ?>"><?php echo htmlentities($req2[$i]['username'], ENT_QUOTES, 'UTF-8'); ?></a></td>
    	<td><?php echo date('Y/m/d H:i:s' ,$req2[$i]['timestamp']); ?></td>
    </tr>
<?php
}
//If there is no read message we notice it
if(intval(count($req2))==0)
{
?>
	<tr>
    	<td colspan="4" class="center">You have no read message.</td>
    </tr>
<?php
}
?>
</table>
<?php
}
else
{
	echo 'You must be logged to access this page.';
}
?>
		</div>
		<div class="foot"><a href="index.php?page=panel">Go Home</a></div>
	</body>
</html>