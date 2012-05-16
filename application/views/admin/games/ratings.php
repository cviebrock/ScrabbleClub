<div class="page-header">
	<h1>Club Ratings</h1>
	<span class="subhead">Changes on <?php echo App::format_date($date); ?></span>
</div>

<table class="table table-striped table-bordered sortable">
	<thead>
		<tr>
			<th class="span2">Player</th>
			<th class="span1">Initial Rating</th>
			<th class="span1">Games Played</th>
			<th class="span1">Avg. Opp. Rating</th>
			<th class="span1">Expected Wins</th>
			<th class="span1">Wins</th>
			<th class="span1">Perf. Rating</th>
			<th class="span1">Change</th>
			<th class="span1">Final Rating</th>
		</tr>
	</thead>
	<tbody>
<?php
foreach ($ratings as $rating) {
		echo '<tr>';
		echo '<td>' . $rating->player->fullname() . '</td>';
		echo '<td class="numeric">' . $rating->starting_rating . '</td>';
		echo '<td class="numeric">' . $rating->games_played . '</td>';
		echo '<td class="numeric">' . round($rating->total_opp_ratings / $rating->games_played) . '</td>';
		echo '<td class="numeric">' . $rating->expected_wins . '</td>';
		echo '<td class="numeric">' . $rating->games_won . '</td>';
		echo '<td class="numeric">' . $rating->performance_rating . '</td>';
		echo '<td class="numeric">' . ($rating->ending_rating - $rating->starting_rating) . '</td>';
		echo '<td class="numeric">' . $rating->ending_rating . '</td>';
		echo "<tr>\n";
}
?>
	</tbody>
</table>

<script>
$(document).ready( function() {

	$('table.sortable').tablesorter({
		sortList: [[7,1]],
	});

});
</script>

