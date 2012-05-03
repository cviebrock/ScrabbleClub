<div class="page-header">
	<h1>Club Statistics</h1>
	<span class="subhead">As of <?php echo App::format_date($lastgame->date); ?></span>
</div>


<table class="table table-condensed">
	<tbody>
		<tr>
			<th class="span3 horizontal-header">Dates Played</th>
			<td class="span4"><?php echo (int)$overall['total_dates']; ?></td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Players per Date</th>
			<td class="span4"><?php echo round($overall['players_per_date']); ?></td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Total Games Played</th>
			<td class="span4"><?php echo (int)$overall['total_games']; ?></td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Average Score</th>
			<td class="span4"><?php echo round($overall['average_score']); ?></td>
		</tr>
	</tbody>
</table>



<h2>Highest Scores</h2>
<?php
	echo View::make('partials.game_listing')
		->with('games', $high_scores)
		->with('id', 'high_score')
		->with('mark_winners', true)
		->with('small_head', true)
		->render();
?>


<h2>Closest Games</h2>
<?php
	echo View::make('partials.game_listing')
		->with('games', $closest_games)
		->with('id', 'high_score')
		->with('mark_winners', true)
		->with('small_head', true)
		->render();
?>


<h2>Blowouts</h2>
<?php
	echo View::make('partials.game_listing')
		->with('games', $blowouts)
		->with('id', 'high_score')
		->with('mark_winners', true)
		->with('small_head', true)
		->render();
?>