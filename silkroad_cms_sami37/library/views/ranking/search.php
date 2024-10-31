<?php
if($searchType == "Player")
{
?>

	<? if(count($object) > 0){ ?>
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>#</th>
					<th>Character / Guild</th>
					<th>Race</th>
					<th>Level</th>
					<th>Job</th>
					<th>Itempoints</th>
				</tr>
			</thead>
			<tbody>
				<? foreach($object as $result) {?>
				<tr>
				<td><?=$result['ID']?></td>
				<td><a href="/ranking/player/<?=$result['CharID']?>"><?=$result['Charname']?></a></td>
				<td><?=$result['Race']?></td>
				<td><?=$result['Level']?></td>
				<td><?=$result['Job']?></td>
				<td><?=$result['Points']?></td>
				</tr>
				<?}?>
			</tbody>

		</table>
	</div>
	<?} else {echo "No character found";} ?>
		
<?}?>
<?php
if($searchType == "Guild")
{
?>
	<? if(count($object) > 0){ ?>
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>#</th>
					<th>Guild</th>
					<th>Master</th>
					<th>Member</th>
					<th>Level</th>
					<th>itemPoints</th>
				</tr>
			</thead>
			<tbody>
			<? foreach($object as $result) {?>
				<tr>
					<td><?=$result["ID"]?></td>
					<td><a href="/ranking/guild/<?=$result["GuildID"]?>"><?=$result['Guildname']?></a></td>
					<td><a href="/ranking/player/<?=$result['MasterCharID']?>"><?=$result['Master']?></a></td>
					<td><?=$result['Memeber']?></td>
					<td><?=$result['Level']?></td>
					<td><?=$result['Points']?></td>
					</tr>
			<?}?>
			</tbody>
		</table>
	</div>
	<?} else {echo "No guild found";} ?>
<?}?>