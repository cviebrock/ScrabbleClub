<?php
$hide_date = isset($hide_date);
$pspan = $hide_date ? 'span4' : 'span3';
?>

<table id="<?php echo $id; ?>" class="table table-striped table-bordered sortable">
	<thead <?php echo ($small_head ? 'class="small"' : ''); ?>>
		<tr>
			<?php if (!$hide_date): ?><th class="span2">Date</th><?php endif; ?>
			<th class="<?php echo $pspan; ?>">Player</th>
			<th class="span1">Score</th>
			<th class="<?php echo $pspan; ?>">Opponent</th>
			<th class="span1">O.Score</th>
			<th class="span1"><?php echo isset($combined) ? 'Combined' : 'Spread'; ?></th>
		</tr>
	</thead>
	<tbody>
<?php

if (!is_array($games)) {
	$games = array($games);
}

foreach ($games as $game):

	$p_class = $o_class = '';
	if ($mark_winners) {
		if ($game->spread > 0) {
			$p_class = ' winning_game';
			$o_class = ' losing_game';
		} else if ($game->spread < 0) {
			$p_class = ' losing_game';
			$o_class = ' winning_game';
		} else {
			$p_class = $o_class = ' tie_game';
		}
	}

?>
		<tr>
			<?php if (!$hide_date): ?><td><?php echo App::format_date($game->date); ?></td><?php endif; ?>
			<td class="<?php echo $p_class; ?>"><?php echo $game->player->fullname(); ?></td>
			<td class="numeric<?php echo $p_class; ?>"><?php echo $game->player_score; ?></td>
			<td class="<?php echo $o_class; ?>"><?php echo $game->opponent->fullname(); ?></td>
			<td class="numeric<?php echo $o_class; ?>"><?php echo $game->opponent_score; ?></td>
			<td class="numeric"><?php echo isset($combined) ? $game->player_score + $game->opponent_score : $game->spread; ?></td>
		</tr>
<?php
endforeach;
?>
	</tbody>
</table>