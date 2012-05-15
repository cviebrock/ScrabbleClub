<div class="page-header">
	<h1>Player Statistics</h1>
	<span class="subhead">As of <?php echo App::format_date($lastgame->date); ?></span>
</div>

<table class="table table-striped table-bordered sortable">
	<thead class="small">
		<tr>
			<th class="span3">Name</th>
			<th class="span1">Games Played</th>
			<th class="span1">Record</th>
			<th class="span1">Win %</th>
			<th class="span1">Avg. Score</th>
			<th class="span1">Avg. Against</th>
			<th class="span1">Avg. Spread</th>
			<th class="span1">High Score</th>
<!--
			<th class="span1">High Spread</th>
-->
			<th class="span1">Bingos /Game</th>
			<th class="span1">Bingo Phoniness</th>
		</tr>
	</thead>
	<tbody>
<?php

$sortable = true;

foreach ($players as $player) {

		if (array_key_exists($player->id, $bingos)) {
			$bingo = $bingos[ $player->id ];
		} else {
			$bingo = false;
		}

		if ($sortable && $player->games_played==0) {
			echo "</tbody>\n<tbody class=\"nosort\">\n";
			$sortable = false;
		}

		$numerator = $player->wins + ($player->ties / 2 );
		$ratio = ($player->games_played ? $numerator*100/$player->games_played : 0);


		echo '<tr>';

		echo '<td class="nowrap">' .
			App::link_to_action('players@details', $player->fullname, array($player->id) ) .
			'</td>';
		echo '<td class="numeric">' . $player->games_played . '</td>';
		echo '<td class="nowrap">' . sprintf("%.1f-%.1f",
				$numerator,
				$player->losses
			) . '</td>';
		echo '<td class="numeric">' . sprintf('%.1f%%', $ratio) . '</td>';
		echo '<td class="numeric">' . $player->average_score . '</td>';
		echo '<td class="numeric">' . $player->average_opponent_score . '</td>';
		echo '<td class="numeric">' . $player->average_spread . '</td>';
		echo '<td class="numeric">' . $player->best_score . '</td>';
#		echo '<td class="numeric">' . $player->best_spread . '</td>';

		echo '<td class="numeric">' . ($bingo ? sprintf('%.1f', $bingo->num_played / $player->games_played) : '&mdash;') . '</td>';
		echo '<td class="numeric">' . ($bingo ? sprintf('%.1f%%',100*$bingo->phoniness) : '&mdash;') . '</td>';


		echo "</tr>\n";
}
?>
	</tbody>
</table>

<script>
$(document).ready( function() {

	$('table.sortable').tablesorter({
		headers: {
			2: { sorter: 'sc_record' }
		},
		sortList: [[3,1], [1,1]]
	});

});
</script>
