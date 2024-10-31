<?php
return [
			'home'=>[LIBRARY_PATH.'\controller\home\home.php','pageTitle'=>' | Home',"Method" => "index", "is_Post"=>false],
			'news'=>[LIBRARY_PATH.'\controller\home\news.php','pageTitle'=>' | Home',"Method" => "index", "is_Post"=>true],
			'auth'=>[LIBRARY_PATH.'\controller\user\login\auth.php','pageTitle'=>' | Login', "Method" => "index", "is_Post"=>true],
			'panel'=>[LIBRARY_PATH.'\controller\user\login\panel.php','pageTitle'=>' | Panel', "Method" => "index", "is_Post"=>false],
			'download'=>[LIBRARY_PATH.'\controller\download.php','pageTitle'=>' | Downloads', "Method" => "index", "is_Post"=>false],
			'newso'=>[LIBRARY_PATH.'\controller\news.php','pageTitle'=>' | Newso', "Method" => "index", "is_Post"=>false],
			'panel/changepassword'=>[LIBRARY_PATH.'\controller\user\login\panel.php','pageTitle'=>' | ChangePassword', "Method" => "index", "is_Post"=>true],
			'user'=>[LIBRARY_PATH.'\controller\user\user.php','pageTitle'=>' | User', "Method" => "index", "is_Post"=>false],
			'panel/superrewardipn'=>[LIBRARY_PATH.'\controller\user\login\panel.php','pageTitle'=>' | Home', "Method" => "index", "is_Post"=>true],
			'panel/paymentwallipn'=>[LIBRARY_PATH.'\controller\user\login\panel.php','pageTitle'=>' | Home', "Method" => "index", "is_Post"=>true],
			'panel/paypalipn'=>[LIBRARY_PATH.'\controller\user\login\panel.php','pageTitle'=>' | Home', "Method" => "index", "is_Post"=>true],
			'captcha'=>[LIBRARY_PATH.'\controller\captcha.php','pageTitle'=>' | Home', "Method" => "index", "is_Post"=>true],
			'captcha/gen'=>[LIBRARY_PATH.'\controller\captcha.php','pageTitle'=>' | Home', "Method" => "index", "is_Post"=>true],
			'user/checkregisterinputs'=>[LIBRARY_PATH.'\controller\user\user.php','pageTitle'=>' | Home', "Method" => "index", "is_Post"=>true],
			'ranking'=>[LIBRARY_PATH.'\controller\ranking\ranking.php','pageTitle'=>' | Ranking', "Method" => "index", "is_Post"=>false],
			'ranking/search'=>[LIBRARY_PATH.'\controller\ranking\ranking.php','pageTitle'=>' | Home', "Method" => "index", "is_Post"=>true],
			'ranking/topPlayers'=>[LIBRARY_PATH.'\controller\ranking\ranking.php','pageTitle'=>' | Home', "Method" => "index", "is_Post"=>true],
			'ranking/topGuilds'=>[LIBRARY_PATH.'\controller\ranking\ranking.php','pageTitle'=>' | Home', "Method" => "index", "is_Post"=>true],
			'support'=>[LIBRARY_PATH.'\controller\support.php','pageTitle'=>' | Support', "Method" => "index", "is_Post"=>false],
			'staff'=>[LIBRARY_PATH.'\controller\staff.php','pageTitle'=>' | Staff', "Method" => "index", "is_Post"=>false],
			'about'=>[LIBRARY_PATH.'\controller\about.php','pageTitle'=>' | About Us', "Method" => "index", "is_Post"=>false]


		  ];