<div class="page-header">
	<h1>Player Statistics
		<span class="subhead">
			As of <?php echo format_date($lastgame->date); ?> /
			Minimum <?php echo $min_games_played; ?> games played
		</span>
	</h1>
</div>

<table class="table table-striped table-bordered sortable">
	<thead class="small">
		<tr>
			<th class="span3">Name</th>
			<th class="span1">Club Rating</th>
			<th class="span1">Games Played</th>
			<th class="span1">Record</th>
			<th class="span1">Win %</th>
			<th class="span1">Avg. Score</th>
			<th class="span1">Avg. Against</th>
			<th class="span1">Avg. Spread</th>
			<th class="span1">High Score</th>
			<th class="span1">Bingos /Game</th>
			<th class="span1">Bingo Phoniness</th>
		</tr>
	</thead>
	<tbody>
<?php

$sortable = $rank = true;

$rankings = array(
	'rating'        => array(),
	'ratio'         => array(),
	'average_score' => array(),
	'best_score'    => array(),
	'bingos'        => array(),
	'phoniness'     => array(),
);


foreach ($players as $player) {

		if (array_key_exists($player->id, $bingos)) {
			$bingo = $bingos[ $player->id ];
		} else {
			$bingo = false;
		}

		if ($sortable && $player->games_played<$min_games_played) {
			echo "</tbody>\n<tbody class=\"nosort\">\n";
			$sortable = false;
			$rank = false;
		}

		$numerator = $player->wins + ($player->ties / 2 );
		$ratio = ($player->games_played ? $numerator*100/$player->games_played : 0);
		$rating = array_key_exists($player->id, $ratings) ? $ratings[ $player->id ]->ending_rating : '&mdash;';

		if ($rank) {
			$rankings['rating'][$player->id] = $rating;
			$rankings['ratio'][$player->id] = $ratio;
			$rankings['average_score'][$player->id] = $player->average_score;
			$rankings['best_score'][$player->id] = $player->best_score;
			$rankings['bingos'][$player->id] = $bingo ? $bingo->num_played / $player->games_played : 0;
			$rankings['phoniness'][$player->id] = $bingo ? $bingo->phoniness : 0;
		}

		echo '<tr id="row_' . $player->id . '">';

		echo '<td class="nowrap">' .
			HTML::link_to_action('players@details', $player->fullname, array($player->id) ) .
			'</td>';
		echo '<td class="numeric r_rating">' . $rating . '</td>';
		echo '<td class="numeric">' . $player->games_played . '</td>';
		echo '<td class="nowrap">' . sprintf("%.1f-%.1f",
				$numerator,
				$player->losses
			) . '</td>';
		echo '<td class="numeric r_ratio">' . sprintf('%.1f%%', $ratio) . '</td>';
		echo '<td class="numeric r_average_score">' . $player->average_score . '</td>';
		echo '<td class="numeric">' . $player->average_opponent_score . '</td>';
		echo '<td class="numeric">' . $player->average_spread . '</td>';
		echo '<td class="numeric r_best_score">' . $player->best_score . '</td>';
		// echo '<td class="numeric">' . $player->best_spread . '</td>';

		echo '<td class="numeric r_bingos">' . ($bingo ? sprintf('%.1f', $bingo->num_played / $player->games_played) : '&mdash;') . '</td>';
		echo '<td class="numeric r_phoniness">' . ($bingo ? sprintf('%.1f%%',100*$bingo->phoniness) : '&mdash;') . '</td>';

		echo "</tr>\n";
}
?>
	</tbody>
</table>

<script>

$(function() {

	$('table.sortable').tablesorter({
		headers: {
			3: { sorter: 'sc_record' }
		},
		// sortList: [[4,1], [2,1]]
		sortList: [[1,1]]
	});

<?php

foreach($rankings as $what=>$values) {
	arsort($values);
	$top_five = array_slice($values, 0, 5, true);

	$c = 1;
	foreach($top_five as $pid=>$value) {
		echo "$('#row_{$pid} td.r_{$what}').append('<span class=\"tinybadges p{$c}\">{$c}</span>');\n";
		$c++;
	}

}


?>


});
</script>
