<?php $admin = isset($admin); ?>
<table class="table table-striped table-bordered sortable">
	<thead class="small">
		<tr>
			<th class="span2">Date</th>
			<th class="span3">Player</th>
			<th class="span3">Bingo</th>
			<th class="span1">Score</th>
<?php if ($admin): ?>
			<th class="span2">Actions</th>
<?php else: ?>
			<th class="span1">Playability</th>
<?php endif; ?>
		</tr>
	</thead>
	<tbody>
<?php if ( count($bingos) ): ?>
<?php foreach ($bingos as $bingo): ?>
		<tr <?php echo ($bingo->valid ? '' : ' class="phoney"'); ?>>
			<td><?php echo format_date($bingo->date); ?></td>
			<td><?php echo $bingo->player->fullname; ?></td>
			<td>
				<i class="<?php echo ($bingo->valid ? 'icon-ok' : 'icon-remove'); ?> icon-fade"></i>
				<?php echo $bingo->word; ?>
			</td>
			<td class="numeric"><?php echo ($bingo->score ? $bingo->score : '&mdash;'); ?></td>
<?php if ($admin): ?>
			<td><ul class="sc_actions">
				<li><?php echo action_link_to_route('admin.bingos@edit', 'Edit', array($bingo->id), 'small|pencil' ); ?></li>
				<li><?php echo action_link_to_route('admin.bingos@delete', 'Delete', array($bingo->id), 'small|remove' ); ?></li>
		 	</ul></td>
<?php else: ?>
			<td class="numeric"><?php echo ($bingo->playability ? $bingo->playability : '&mdash;'); ?></td>
<?php endif; ?>
		</tr>
<?php endforeach; ?>
<?php else: ?>
		<tr><td colspan="5">No bingos</td></tr>
<?php endif; ?>
	</tbody>
</table>
