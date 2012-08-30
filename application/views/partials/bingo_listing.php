<table class="table table-striped table-bordered sortable">
	<thead class="small">
		<tr>
			<th class="span2">Date</th>
			<th class="span3">Player</th>
			<th class="span3">Bingo</th>
			<th class="span1">Score</th>
			<th class="span1">Playability</th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($bingos as $bingo): ?>
		<tr <?php echo ($bingo->valid ? '' : ' class="phoney"'); ?>>
			<td><?php echo format_date($bingo->date); ?></td>
			<td><?php echo $bingo->player->fullname; ?></td>
			<td>
				<i class="<?php echo ($bingo->valid ? 'icon-ok' : 'icon-remove'); ?> icon-fade"></i>
				<?php echo $bingo->word; ?>
			</td>
			<td class="numeric"><?php echo ($bingo->score ? $bingo->score : '&mdash;'); ?></td>
			<td class="numeric"><?php echo ($bingo->playability ? $bingo->playability : '&mdash;'); ?></td>
		</tr>
<?php endforeach; ?>
	</tbody>
</table>