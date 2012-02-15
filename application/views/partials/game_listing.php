<table id="<?php echo id; ?>" class="table table-striped table-bordered sortable">
	<thead>
		<tr>
			<th class="span2">Date</th>
			<th class="span3">Player</th>
			<th class="span1">Score</th>
			<th class="span3">Opponent</th>
			<th class="span1">O.Score</th>
			<th class="span1">Spread</th>
		</tr>
	</thead>
	<tbody>
<?php

if (!is_array($games)) {
	$games = array($games);
}

foreach ($games as $game):

	if ($mark_winners) {
		$p_class = $o_class = '';
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
			<td><?php echo App::format_date($game->date); ?></td>
			<td class="<?php echo $p_class; ?>"><?php echo $game->player->fullname(); ?></td>
			<td class="numeric<?php echo $p_class; ?>"><?php echo $game->player_score; ?></td>
			<td class="<?php echo $o_class; ?>"><?php echo $game->opponent->fullname(); ?></td>
			<td class="numeric<?php echo $o_class; ?>"><?php echo $game->opponent_score; ?></td>
			<td class="numeric"><?php echo $game->spread; ?></td>
		</tr>
<?php
endforeach;
?>
	</tbody>
</table>