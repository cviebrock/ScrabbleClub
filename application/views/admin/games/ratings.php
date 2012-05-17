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
		echo '<td>' . $rating->player->fullname() . '</td>';
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

<?php echo App::action_link_to_route('admin.games@index', 'Back to Games List', array(), 'arrow-left'); ?>



<script>
$(document).ready( function() {

	$('table.sortable').tablesorter({
		sortList: [[8,1]],
	});

});
</script>

