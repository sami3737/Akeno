<div class="headline"><h3>Top Players</h3></div>
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
	<?=Paginator::getPagination($totalRecords,$page,$totalPage,"ranking/topPlayers")?>
	<? } else {echo "No character found";} ?>
		