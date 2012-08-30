<div class="page-header">
	<h1>Bingos for <?php echo $player->fullname; ?></h1>
</div>

<table class="table table-striped table-bordered sortable">
	<thead class="small">
		<tr>
			<th class="span2">Date</th>
			<th class="span3">Bingo</th>
			<th class="span1">Score</th>
			<th class="span1">Playability</th>
			<th class="span1">Valid</th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($bingos as $bingo): ?>
		<tr <?php echo ($bingo->valid ? '' : ' class="phoney"'); ?>>
			<td><?php echo format_date($bingo->date); ?></td>
			<td><?php echo $bingo->word; ?></td>
			<td class="numeric"><?php echo ($bingo->score ? $bingo->score : '&mdash;'); ?></td>
			<td class="numeric"><?php echo ($bingo->playability ? $bingo->playability : '&mdash;'); ?></td>
			<td class="center"><?php echo ($bingo->valid ? '<i class="icon-ok hide-text">1</i>' : '<i class="icon-remove hide-text">0</i>'); ?></td>
		</tr>
<?php endforeach; ?>
	</tbody>
</table>

<?php echo action_link_to_route('players@details', 'Back to player details', array($player->id), 'arrow-left', array('class'=>'btn')); ?>


<script>
$(document).ready( function() {

	$('table.sortable').tablesorter({
		headers: {
			0: { sorter: 'sc_date' },
			2: { sorter: 'digit' }
		},
		sortList: [[0,1]]
	});

});
</script>