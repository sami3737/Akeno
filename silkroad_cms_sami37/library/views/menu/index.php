<?php

 if(isset($_SESSION['username'])) { ?>

<li><a href="http://www.blacklagoongaming.com" style="text-align: center; margin: 15 0 0 0;"><i class=""></i>Home</a></li>
<li><a href="http://silkroad.blacklagoongaming.com" style="text-align: center; margin: 15 0 0 0;"><i class=""></i>Silkroad Online</a></li>
<li><a href="http://rust.blacklagoongaming.com" style="text-align: center; margin: 15 0 0 0;"><i class=""></i>Rust Survival</a></li>
<li class="dropdown">
	<a href="#" style="text-align: center; margin: 15 0 0 0;" class="dropdown-toggle" data-toggle="dropdown"><?php echo $_SESSION['username']?> <i class="fa fa-angle-down"></i></a>
	<ul class="dropdown-menu">
		<li><a href="/panel/account">Accountpanel</a></li>
		<li><a href="/panel/donate">Donate</a></li>
        <li><a href="/auth/logout/">Logout</a></li>

	</ul>
</li>
<li><a href="/ranking" style="text-align: center; margin: 15 0 0 0;">Ranking</a></li>
<li><a href="/download" style="text-align: center; margin: 15 0 0 0;">Downloads</a></li>
<li><a href="/support" style="text-align: center; margin: 15 0 0 0;"><i class=""></i>Support</a></li>
<li><a href="/about" style="text-align: center; margin: 15 0 0 0;"><i class=""></i>About us</a></li>

<?php } ELSE { ?>
	
<li><a href="http://www.blacklagoongaming.com" style="text-align: center; margin: 15 0 0 0;"><i class=""></i>Home</a></li>
<li><a href="http://silkroad.blacklagoongaming.com" style="text-align: center; margin: 15 0 0 0;"><i class=""></i>Silkroad Online</a></li>
<li><a href="http://rust.blacklagoongaming.com" style="text-align: center; margin: 15 0 0 0;"><i class=""></i>Rust Survival</a></li>
<li><a onclick="showLogin();" href="#" style="text-align: center; margin: 15 0 0 0;" class="login-btn"><i class=""></i> Login</a></li>
<li><a href="/ranking" style="text-align: center; margin: 15 0 0 0;">Ranking</a></li>
<li><a href="/download" style="text-align: center; margin: 15 0 0 0;">Downloads</a></li>
<li><a href="/support" style="text-align: center; margin: 15 0 0 0;"><i class=""></i>Support</a></li>
<li><a href="/about" style="text-align: center; margin: 15 0 0 0;"><i class=""></i>About us</a></li>

<?php } ?>