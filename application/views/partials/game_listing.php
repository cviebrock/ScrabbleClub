<?php if ($games): ?>

<?php
$hide_date = isset($hide_date);
$pspan = $hide_date ? 'span4' : 'span3';
?>

<?php if (isset($summary)): ?>

<h3>Summary</h3>

<table class="table table-condensed table-auto">
	<tbody>

		<tr>
			<th class="span3 horizontal-header">Games Played</th>
			<td class="span4"><?php echo $summary->games_played; ?></td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Record</th>
			<td class="span4"><?php echo $summary->record; ?></td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Winning Percentage</th>
			<td class="span4"><?php echo $summary->percentage; ?></td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Wins</th>
			<td class="span4"><?php echo $summary->wins; ?></td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Losses</th>
			<td class="span4"><?php echo $summary->losses; ?></td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Ties</th>
			<td class="span4"><?php echo $summary->ties; ?></td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Average Score</th>
			<td class="span4"><?php echo $summary->average_score; ?></td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Average Opp. Score</th>
			<td class="span4"><?php echo $summary->average_opponent_score; ?></td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Average Spread</th>
			<td class="span4"><?php echo $summary->average_spread; ?></td>
		</tr>
	</tbody>
</table>

<?php endif; /* summary */ ?>

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
			<?php if (!$hide_date): ?><td><?php echo format_date($game->date); ?></td><?php endif; ?>
			<td class="<?php echo $p_class; ?>"><?php echo $game->player->fullname; ?></td>
			<td class="numeric<?php echo $p_class; ?>"><?php echo $game->player_score; ?></td>
			<td class="<?php echo $o_class; ?>"><?php echo $game->opponent->fullname; ?></td>
			<td class="numeric<?php echo $o_class; ?>"><?php echo $game->opponent_score; ?></td>
			<td class="numeric"><?php echo isset($combined) ? $game->player_score + $game->opponent_score : $game->spread; ?></td>
		</tr>
<?php
endforeach;
?>
	</tbody>
</table>

<?php else:  //  i.e. !$games ?>
	<p class="no-games">
		No games to display.
	</p>
<?php endif; ?>
