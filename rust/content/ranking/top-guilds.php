<div class="headline"><h3>Top Guilds</h3></div>
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
	<?=Paginator::getPagination($totalRecords,$page,$totalPage,"ranking/topPlayers")?>
	<? } else {echo "No character found";} ?>
		