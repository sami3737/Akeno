<?php

require('./api/models/data.php');
require('./api/security/security.class.php');

$Security = new Security();
$modeldata = new dataModel();

function Player($searchValue = "")
{
	global $modeldata;
	if(!is_numeric($searchValue))
		die;
	
	return ["object"=>$modeldata->getPlayerData($searchValue),"inventory" => $modeldata->getPlayerItems($searchValue), "avatar" => $modeldata->getPlayerAvatars($searchValue)];
																		
}

$object = Player($_GET['char']);
$inventory = $object['inventory'];
$avatar = $object['avatar'];
$object = $object['object'];
?>

<div class="col-lg-9">
			<div class="row">
				<div class="col-sm-6">
				<?php if (count($object) > 0) { 
					echo '
					<h3>Statistic - '.$object[0]['Charname'].'</h3>
						<ul class="list-unstyled">
						<div style="float:right"><img src="./img/chars/'.$object[0]['ObjID'].'.gif"> </div>
						<p>Level '.$object[0]['Level'].'</p>
						<p>Str '.$object[0]['Str'].'</p>
						<p>Int '.$object[0]['Int'].'</p>
						<p>ItemPoints: '.$object[0]['Points'].'</p>';
						if ($object[0]['GuildID'] > 0) {
							echo '<p>Guild: <strong><a class="text-red" href="/ranking/guild/'.$object[0]['GuildID'].'"> '.$object[0]['GuildName'].' - <span class="descGray"> Level '.$object[0]['GuildLevel'].'</span></a></strong>';
						} else echo 'Guild : None </p>'; 
						echo '<p>JobType: '.$object[0]['JobType'].' </p>
						<p>Job Alias: '.$object[0]['JobName'].'</p>
						<p>Status: <strong style="color:green;">'.$object[0]['Status'].'</strong></p>
						<p>last refresh '.$object[0]['lastRefresh'].' </p>
						</ul>';
						?>
				</div>

	<div class="col-sm-6">
		<div id="idInventorySet" style="display: block;">
			<h3>Inventory</h3>
			<div class="bg-light">
			
					<div class="slots 6 left">
						<div class="slot">
							<div class="image" style="background:url('<?php echo $inventory['slot6']['image']; ?>');" 
							<?php if(count($inventory['slot6']['whitestate']) > 0) echo 'data-iteminfo="1"'; ?>>
								<?php if (strlen($inventory['slot6']['itemInfo']['sox']) > 0) echo '<span class="plus"></span>'?>	
							</div>
						</div>
						<div class="itemInfo">
						
						<?php echo $inventory['slot6']['ItemDesc'];?>
						
						
						</div>
		
						<div class="clear"></div>
					</div>
					
					<div class="slots 7 right">
						<div class="slot">
							<div class="image" style="background:url('<?php echo $inventory['slot7']['image'];?>');"
							<?php if(count($inventory['slot7']['whitestate']) > 0 || is_array($inventory['slot7']['ItemDesc'])) echo 'data-iteminfo="1"'; ?>>
	
								<span class="info">
								<?php
									if(is_array($inventory['slot7']['ItemDesc'])) 
										echo $inventory['slot7']['ItemDesc']['count'];
									else
									{
										 if (strlen($inventory['slot7']['itemInfo']['sox']) > 0) 
											 echo '<span class="plus"></span>';
									}
									
								?>
								</span>
							</div>
						</div>
						<div class="itemInfo">
							<?php
							if(is_array($inventory['slot7']['ItemDesc'])) 
							{
								echo $inventory['slot7']['ItemDesc']['name'];
							}
							else
								echo $inventory['slot7']['ItemDesc'];
							?>
						</div>
						<div class="clear"></div>
					</div>
					<div class="slots 0 left">
						<div class="slot">
							<div class="image" style="background:url('<?php echo $inventory['slot0']['image'];?>');"
							<?php if(count($inventory['slot0']['whitestate']) > 0) echo 'data-iteminfo="1"'; ?>>
							<?php if (strlen($inventory['slot0']['itemInfo']['sox']) > 0) echo '<span class="plus"></span>';?>
							</div>
						</div>
						<div class="itemInfo">

						<?php echo $inventory['slot0']['ItemDesc']; ?>
						
						</div>
						
						<div class="clear"></div>
					</div>
					
					<div class="slots 2 right">
						<div class="slot">
							<div class="image" style="background:url('<?php echo $inventory['slot2']['image'];?>');" 
							<?php if(count($inventory['slot2']['whitestate']) > 0) echo 'data-iteminfo="1"'; ?>>
							<?php if (strlen($inventory['slot2']['itemInfo']['sox']) > 0) echo '<span class="plus"></span>';?>
							</div>
						</div>
						<div class="itemInfo">
						
						<?php echo $inventory['slot2']['ItemDesc']; ?>

						</div>
						
						<div class="clear"></div>
					</div>
					
					<div class="slots 1 left">
						<div class="slot">
							<div class="image" style="background:url('<?php echo $inventory['slot1']['image'];?>');" 
							<?php if(count($inventory['slot1']['whitestate']) > 0) echo 'data-iteminfo="1"'; ?>>
							<?php if (strlen($inventory['slot1']['itemInfo']['sox']) > 0) echo '<span class="plus"></span>';?>
	
							</div>
						</div>
						<div class="itemInfo">
						
						<?php echo $inventory['slot1']['ItemDesc'];?>
						
						</div>
						
						<div class="clear"></div>
					</div>
					
					<div class="slots 3 right">
						<div class="slot">
							<div class="image" style="background:url('<?php echo $inventory['slot3']['image'];?>');" 
							<?php if(count($inventory['slot3']['whitestate']) > 0) echo 'data-iteminfo="1"'; ?>>
							<?php if (strlen($inventory['slot3']['itemInfo']['sox']) > 0) echo '<span class="plus"></span>';?>
							</div>
						</div>
						<div class="itemInfo">
						
						<?php echo $inventory['slot3']['ItemDesc'];?>
						
						</div>
						
						<div class="clear"></div>
					</div>
					
					<div class="slots 4 left">
						<div class="slot">
							<div class="image" style="background:url('<?php echo $inventory['slot4']['image'];?>');" 
							<?php if(count($inventory['slot4']['whitestate']) > 0) echo 'data-iteminfo="1"'; ?>>
							<?php if (strlen($inventory['slot4']['itemInfo']['sox']) > 0) echo '<span class="plus"></span>';?>
	
							</div>
						</div>
						<div class="itemInfo">
						
						<?php echo $inventory['slot4']['ItemDesc'];?>
						
						</div>
						
						<div class="clear"></div>
					</div>
					
					<div class="slots 5 right">
						<div class="slot">
							<div class="image" style="background:url('<?php echo $inventory['slot5']['image'];?>');" 
							<?php if(count($inventory['slot5']['whitestate']) > 0) echo 'data-iteminfo="1"'; ?>>
							<?php if (strlen($inventory['slot5']['itemInfo']['sox']) > 0) echo '<span class="plus"></span>';?>
	

							</div>
						</div>
						<div class="itemInfo">
						
						<?php echo $inventory['slot5']['ItemDesc'];?>
						
						</div>
						
						<div class="clear"></div>
					</div>
					
					
					<div class="slots 9 left">
						<div class="slot">
							<div class="image" style="background:url('<?php echo $inventory['slot9']['image'];?>');" 
							<?php if(count($inventory['slot9']['whitestate']) > 0) echo 'data-iteminfo="1"'; ?>>
							<?php if (strlen($inventory['slot9']['itemInfo']['sox']) > 0) echo '<span class="plus"></span>';?>
	

							</div>
						</div>
						<div class="itemInfo">
						
						<?php echo $inventory['slot9']['ItemDesc'];?>
						
						</div>
						
						<div class="clear"></div>
					</div>
					
					<div class="slots 10 right">
						<div class="slot">
							<div class="image" style="background:url('<?php echo $inventory['slot10']['image'];?>');" 
							<?php if(count($inventory['slot10']['whitestate']) > 0) echo 'data-iteminfo="1"'; ?>>
							<?php if (strlen($inventory['slot10']['itemInfo']['sox']) > 0) echo '<span class="plus"></span>';?>
	

							</div>
						</div>
						<div class="itemInfo">
						
						<?php echo $inventory['slot10']['ItemDesc'];?>
						
						</div>
						
						<div class="clear"></div>
					</div>
					
					<div class="slots 11 left">
						<div class="slot">
							<div class="image" style="background:url('<?php echo $inventory['slot11']['image'];?>');" 
							<?php if(count($inventory['slot11']['whitestate']) > 0) echo 'data-iteminfo="1"'; ?>>
							
							<?php if (strlen($inventory['slot11']['itemInfo']['sox']) > 0) echo '<span class="plus"></span>'?>
	

							</div>
						</div>
						<div class="itemInfo">
						
						<?php echo $inventory['slot11']['ItemDesc']?>
						
						</div>
						
						<div class="clear"></div>
					</div>
					
					<div class="slots 12 right">
						<div class="slot">
							<div class="image" style="background:url('<?php echo $inventory['slot12']['image'];?>');"
							<?php if(count($inventory['slot12']['whitestate']) > 0) echo 'data-iteminfo="1"'; ?>>
							<?php if (strlen($inventory['slot12']['itemInfo']['sox']) > 0) echo '<span class="plus"></span>'?>
	

							</div>
						</div>
						<div class="itemInfo">
						
						<?php echo $inventory['slot12']['ItemDesc']?>
						
						</div>
						
						<div class="clear"></div>
					</div>
				<div class="clearfix"></div>
			</div>
		</div>
		
	<div id="idAvatarSet" class="" style="display: none;">
		<h3>Avatar</h3>
			<div class="bg-light">
				<div class="slots 1 left">
					<div class="slot">
						<div class="image" style="background:url('<?php echo $avatar['slot1']['image'];?>');" data-iteminfo="1"></div>
					</div>
					<div class="itemInfo">	
					<?php echo $avatar['slot1']['ItemDesc'];?>
					</div>
						<div class="clearfix"></div>
				</div>
					
					<div class="slots 4 right">
					
						<div class="slot">
							<div class="image" style="background:url('<?php echo $avatar['slot3']['image'];?>');" data-iteminfo="1">
							</div>
						</div>
						
						<div class="itemInfo">
						<?php echo $avatar['slot3']['ItemDesc']?>
						</div>
						<div class="clearfix"></div>
					</div>
					
					<div class="slots 0 left">
					
						<div class="slot">
							<div class="image" style="background:url('<?php echo $avatar['slot0']['image'];?>');" data-iteminfo="1">
							</div>
						</div>
						
						<div class="itemInfo">
						<?php echo $avatar['slot0']['ItemDesc']?>
						</div>
						<div class="clearfix"></div>
					</div>
					
					<div class="slots 2 right">
					
						<div class="slot">
							<div class="image" style="background:url('<?php echo $avatar['slot2']['image'];?>');" data-iteminfo="1">
							</div>
						</div>
						
						<div class="itemInfo">
						<?php echo $avatar['slot2']['ItemDesc']?>
						</div>
						<div class="clearfix"></div>
					</div>
					
					<div class="slots 4 left">
					
						<div class="slot">
							<div class="image" style="background:url('<?php echo $avatar['slot4']['image'];?>');" data-iteminfo="1"></div>
						</div>
						
						<div class="itemInfo">
						<?php echo $avatar['slot4']['ItemDesc']?>
						</div>
						<div class="clearfix"></div>
					</div>
					
					<div class="clearfix"></div>
				</div>
			</div>	
			<div id="idShowSet" class="margin-top-10">
				<button id="idInventoryButton" data-type="Avatar" class="btn-block btn btn-flat btn-primary" style="display: block;">
					Show Avatar
				</button>
				<button id="idAvatarButton" data-type="Inventory" class="btn-block btn btn-flat btn-primary" style="display: none;">
					Show Inventory
				</button>
			</div>
			<script type="text/javascript">
			jQuery("#idShowSet button").click(
			function(){
				sType=jQuery(this).data("type");
				jQuery("#idAvatarSet,#idAvatarButton,#idInventorySet,#idInventoryButton").hide("blind",
					function(){
						jQuery(this).removeClass("hidden")
					});
					jQuery("#id"+sType+"Set,#id"+sType+"Button").show("blind");
			});
			</script>
		</div>
	</div>
		
		</div>

				<?php
				}
				else
					{
						//echo '<script> window.location="/404"; </script>'; 
					}
				?>
