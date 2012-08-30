<div class="page-header">
	<h1>Ratings for <?php echo $player->fullname; ?></h1>
</div>

<table class="table table-striped table-bordered sortable">
	<thead class="small">
		<tr>
			<th class="span2">Date</th>
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
		echo '<td>' . format_date($rating->date) . '</td>';
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

<?php echo action_link_to_route('players@details', 'Back to Player Details', array($player->id), 'arrow-left', array('class'=>'btn')); ?>



<script>
$(document).ready( function() {

	$('table.sortable').tablesorter({
		sortList: [[0,0]],
		headers: {
			0: { sorter: 'sc_date' },
		}
	});

});
</script>
