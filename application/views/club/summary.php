<div class="page-header">
	<h1>Game Night Summary</h1>
	<span class="subhead large"><?php echo format_date($date); ?></span>
</div>

<h2>Overall Statistics</h2>


<table class="table table-condensed table-auto">
	<tbody>
		<tr>
			<th class="span3 horizontal-header">Players</th>
			<td class="span4"><?php echo (int)$overall['total_players']; ?></td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Games Played</th>
			<td class="span4"><?php echo (int)$overall['total_games']; ?></td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Average Score</th>
			<td class="span4"><?php echo round($overall['average_score']); ?></td>
		</tr>
	</tbody>
</table>


<h2>Ratings</h2>

<table class="table table-striped table-bordered sortable" id="ratings">
	<thead class="small">
		<tr>
			<th class="span2">Player</th>
			<th class="span1">Initial Rating</th>
			<th class="span1">Games Played</th>
			<th class="span1">Avg. Opp. Rating</th>
			<th class="span1">Expected Wins</th>
			<th class="span1">Wins</th>
			<th class="span1">K</th>
			<th class="span1">Perf. Rating</th>
			<th class="span1">Change</th>
			<th class="span1">Final Rating</th>
		</tr>
	</thead>
	<tbody>
<?php
foreach ($ratings as $rating) {

		$delta = $rating->ending_rating - $rating->starting_rating;

		echo '<tr>';
		echo '<td>' . HTML::link_to_action('players@details', $rating->player->fullname, array($rating->player->id) ) . '</td>';
		echo '<td class="numeric">' . $rating->starting_rating . '</td>';
		echo '<td class="numeric">' . $rating->games_played . '</td>';
		echo '<td class="numeric">' . round($rating->total_opp_ratings / $rating->games_played) . '</td>';
		echo '<td class="numeric">' . $rating->expected_wins . '</td>';
		echo '<td class="numeric">' . $rating->games_won . '</td>';
		echo '<td class="numeric">' . $rating->kfactor . '</td>';
		echo '<td class="numeric">' . $rating->performance_rating . '</td>';
		echo '<td class="numeric">' . sprintf('%+d', $delta) . '</td>';
		echo '<td class="numeric">' . $rating->ending_rating . '</td>';
		echo "<tr>\n";
}
?>
	</tbody>
</table>


<h2>Best Bingos</h2>

<table class="table table-striped table-bordered sortable">
	<thead class="small">
		<tr>
			<th class="span3">Player</th>
			<th class="span3">Bingo</th>
			<th class="span1">Score</th>
			<th class="span1">Playability</th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($bingos as $bingo): ?>
		<tr <?php echo ($bingo->valid ? '' : ' class="phoney"'); ?>>
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

<hr>

<h2>All Games</h2>

<?php
	echo View::make('partials.game_listing')
		->with('games', $games)
		->with('id', 'all_games')
		->with('mark_winners', false)
		->with('small_head', true)
		->with('condensed',true)
		->with('hide_date', true)
		->render();
?>

<div class="actions">
<?php echo action_link_to_route('home', 'Back to Homepage', array(), 'arrow-left'); ?>
</div>


<script>
$(document).ready( function() {

	$('#ratings').tablesorter({
		sortList: [[8,1]],
	});

});
</script>