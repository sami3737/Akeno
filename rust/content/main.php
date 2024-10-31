			<anchor id="about"></anchor>
            <section class="text-center">
                <div class="container">
                    <div class="row">
						<h1 style="text-align:center;padding-bottom:20px;">About us</h1>
                        <div class="col-sm-12">
							Black Lagoon Gaming has grown into one of the biggest forum communities in the world. Today, Black Lagoon Gaming is the best place on the web to discuss games and fantasy thing you can imagine. Plus, there are tons of other free features to keep BLG members permanently amused. Black Lagoon Gaming is a one of Silkroad that we have improved and made it test your skills and your abilities to score as many points as possible. You will be rewarded as a prize for winning. In order to make you and everyone else's experience on Black Lagoon Gaming a pleasant one, we have designed rules in order to minimize conflict and maximize your enjoyment of the site. Black Lagoon Gaming based on Current Silkroad and for more entertainment for all the players that will join us.
						<a href="https://drive.google.com/drive/folders/0B6E0EfBlP6UqYU1hTVZVTElLVTQ" target="_self">
							<img style="text-align: center;border-radius:0%;margin: auto;" src="./img/V2/Download.png" class="img-responsive">
						</a>
						</div>
                    </div>
                </div>
            </section>
			<anchor id="info"></anchor>
            <section style="border-top: 1px solid #ebebeb;" class="bg--secondary">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-4">
							<div class="title" style="font-size:15px;">Server info</div>
								<div class="divider"></div>
								<ul class="blog_archieve">
									<div class="clearfix margin-bottom-15"></div>
									<?php
									global $Socket;
									global $config;
									if($Socket->checkState($config->ServerIP,$config->LoginPort))
									{
										echo '<li style="font-weight:bold;">Server Status: <font color="green">Online</font></li>';
									}
									else
										echo '<li style="font-weight:bold;">Server Status: <font color="red">Offline</font></li>';
									?>					
									<li> Player Online					
									<?php
										$userCount = 0;
										try
										{
											$userCount = SqlManager::ReadSingle("Select Top 1 nUserCount From _ShardCurrentUser Where nShardID = 64 ORDER BY nID desc");
										}
										catch(Exception $e){
										}
					
										echo $userCount.' / 1000';
									?></li>
									
									<div class="clearfix margin-bottom-15"></div>
									<li><i class="fa fa-server"></i> Server: <?php echo $config->Title?></li>
									<li><i class="fa fa-users"></i> Cap <?php echo $config->ServerCap?></li>
									<li><i class="fa fa-venus-mars"></i> Race :  <?php echo $config->ServerRace?></li>
									<li><i class="fa fa-arrow-circle-o-up"></i> Exp/Sp  <?php echo $config->ServerExp?></li>
									<li><i class="fa fa-arrow-circle-o-up"></i> Exp/Sp Party  <?php echo $config->ServerExpParty?></li>
									<li><i class="fa fa-exchange"></i> Gold Drop  <?php echo $config->ServerGoldDrop?></li>
									<li><i class="fa fa-exchange"></i> Item Drop  <?php echo $config->ServerItemDrop?></li>
									<li><i class="fa fa-exchange"></i> Tradegoods  <?php echo $config->ServerTradeGoods?></li>
									<li><i class="fa fa-plus-circle"></i> Max Plus  <?php echo $config->ServerMaxPlus?></li>
									<li><i class="fa fa-desktop"></i> PC Limit  <?php echo $config->ServerPclimit?></li>
								</ul>
                        </div>
                        <div class="col-sm-4">
							<div class="title" style="font-size:15px;">Server Times</div>
							<div class="divider"></div>
							<ul class="blog_archieve">	
							<div class="clearfix margin-bottom-15"></div>
								<li><i class="fa fa-clock-o"></i> <strong>Time:</strong> <span id="timerClock">00:00:00</span></li>
								<li><i class="fa fa-shield"></i> <strong>Fortress:</strong> <span id="castleTimer">00:00:00</span></li>
								<li><i class="glyphicon glyphicon-pencil"></i> <strong>Register:</strong> <small>24/7 Except during fortress war time</small></li>
								<li><i class="fa fa-flag-o"></i> <strong>CTF:</strong> <small><span id="ctfTimer">00:00:00</span></small></li>
							</ul>
                        </div>
                        <div class="col-sm-4">
							<?php include ('./content/job.php'); ?>
                        </div>
                    </div>
                </div>
            </section>
            <section style="border-bottom: 1px solid #ebebeb;" class="bg--secondary">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="pricing pricing-2 boxed boxed--border boxed--lg">
								<?php include('content/LastUnique.php'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
			<anchor id="news"></anchor>
            <section style="border-bottom: 1px solid #ebebeb;" class="bg--secondary">
                <div class="container">
                    <div class="row">
						<div class="col-md-12 col-sm-10">
							<div class="process-1">
								<?php include('content/news.php'); ?>
							</div>
						</div>
                    </div>
                </div>
            </section>
			<anchor id="staff"></anchor>
            <section class="text-center">
                <div class="container">
                    <div class="row">
						<h1 style="text-align:center;padding-bottom:20px;">Staff</h1>
						<style>
								img {
									border-radius: 50%;
								}
						</style>
                        <div class="col-sm-4">
                            <div class="feature feature-8"> <img alt="Image" src="https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/79/79f7e340736919baf0cb61fc9cd3175d0c760252_full.jpg">
                                <h5>Tommy_vr</h5> <span>Founder &amp; CEO</span> </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="feature feature-8"> <img alt="Image" src="https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/7c/7c43292c0f9308663a23207a349ab0508e8f9122_full.jpg">
                                <h5>Ins3rt<br></h5> <span>COO</span> </div>
                        </div>
						<div class="col-sm-4">
                            <div class="feature feature-8"> <img alt="Image" src="https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/2a/2a629f5bd71069d7d1d48318ce925794b13c6c67_full.jpg">
                                <h5>Mr. Bread<br></h5> <span>Mod</span> </div>
                        </div>
                    </div>
                </div>
            </section>
