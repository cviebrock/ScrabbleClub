<div class="page-header">
	<h1><?php echo $player->fullname(); ?></h1>
</div>


<h3>Club Statistics</h3>

<?php

	$numerator = $club_details->wins + ($club_details->ties / 2 );
	$record = sprintf('%.1f%s%.1f',
		$numerator,
		' &ndash; ',
		$club_details->losses
	);
	$percentage = ( $club_details->games_played ?
		sprintf('%.1f%%', $numerator * 100 / $club_details->games_played ) :
		'&mdash;'
	);

?>

<table class="table table-condensed">
	<tbody>
		<tr>
			<td class="span3 horizontal-header">Games Played</th>
			<td class="span4"><?php echo $club_details->games_played; ?></td>
		</tr>
		<tr>
			<td class="span3 horizontal-header">Record</th>
			<td class="span4"><?php echo $record; ?></td>
		</tr>
		<tr>
			<td class="span3 horizontal-header">Winning Percentage</th>
			<td class="span4"><?php echo $percentage; ?></td>
		</tr>
		<tr>
			<td class="span3 horizontal-header">Wins</th>
			<td class="span4"><?php echo $club_details->wins; ?></td>
		</tr>
		<tr>
			<td class="span3 horizontal-header">Losses</th>
			<td class="span4"><?php echo $club_details->losses; ?></td>
		</tr>
		<tr>
			<td class="span3 horizontal-header">Ties</th>
			<td class="span4"><?php echo $club_details->ties; ?></td>
		</tr>
		<tr>
			<td class="span3 horizontal-header">Average Score</th>
			<td class="span4"><?php echo $club_details->average_score; ?></td>
		</tr>
		<tr>
			<td class="span3 horizontal-header">Average Opp. Score</th>
			<td class="span4"><?php echo $club_details->average_opponent_score; ?></td>
		</tr>
		<tr>
			<td class="span3 horizontal-header">Average Spread</th>
			<td class="span4"><?php echo $club_details->average_spread; ?></td>
		</tr>
	</tbody>
</table>


<h3>Bingos</h3>

<table class="table table-condensed">
	<tbody>
		<tr>
			<td class="span3 horizontal-header">Bingos Played</th>
			<td class="span4"><?php echo $bingos['count']; ?></td>
		</tr>
		<tr>
			<td class="span3 horizontal-header">Average Bingos/Game</th>
			<td class="span4"><?php echo ($club_details->games_played ?
				sprintf('%.1f', $bingos['count'] / $club_details->games_played) :
				'&mdash;'
				); ?></td>
		</tr>
		<tr>
			<td class="span3 horizontal-header">Phoney Frequency</th>
			<td class="span4"><?php echo ($bingos['count'] ?
				sprintf('%.0f%%', 100 * $bingos['phoney'] / $bingos['count']) :
				'&mdash;'
				); ?></td>
		</tr>
		<tr>
			<td class="span3 horizontal-header">Best Bingo</th>
			<td class="span4"><?php echo $bingos['best']->word_and_score(); ?></td>
		</tr>

	</tbody>
</table>

<?php print_r($bingos); ?>

<h3>High / Low Scores</h3>
<?php
	echo View::make('partials.game_listing')
		->with('games', $best_scores)
		->with('id', 'best_scores')
		->with('mark_winners', true)
		->render();
?>


<h3>Best Win / Worst Loss</h3>
<?php
	echo View::make('partials.game_listing')
		->with('games', $best_spreads)
		->with('id', 'best_spreads')
		->with('mark_winners', true)
		->render();
?>


<h3>NASPA Details</h3>

<table class="table table-condensed">
	<tbody>
		<tr>
			<td class="span3 horizontal-header">Number</th>
			<td class="span4"><?php echo $player->naspa_id; ?></td>
		</tr>
		<tr>
			<td class="horizontal-header">Rating</th>
			<td><?php echo ($player->naspa_rating ? $player->naspa_rating : '&mdash;'); ?></td>
		</tr>
	</tbody>
</table>
