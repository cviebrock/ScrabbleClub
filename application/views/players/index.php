<div class="page-header">
	<h1>Players</h1>
</div>

<table class="table table-striped table-bordered sortable">
	<thead>
		<tr>
			<th class="span3">Name</th>
			<th class="span1">Games Played</th>
			<th class="span2">Record</th>
			<th class="span1">Winning %</th>
			<th class="span1">Avg. Score</th>
			<th class="span1">Avg. Against</th>
			<th class="span1">Avg. Spread</th>
			<th class="span1">High Score</th>
			<th class="span1">High Spread</th>
		</tr>
	</thead>
	<tbody>
<?php

$sortable = true;

foreach ($players as $player) {

		if ($sortable && $player->games_played==0) {
			echo "</tbody>\n<tbody class=\"nosort\">\n";
			$sortable = false;
		}


		$numerator = $player->wins + ($player->ties / 2 );

		echo '<tr>';

		echo '<td class="nowrap">' .
			App::link_to_route('player_details', $player->fullname, array($player->id) ) .
			'</td>';
		echo '<td class="numeric">' . $player->games_played . '</td>';
		echo '<td class="nowrap">' . sprintf('%.1f%s%.1f',
				$numerator,
				' &ndash; ',
				$player->losses
			) . '</td>';
		echo '<td class="numeric">';
		if ($player->games_played) {
			printf('%.1f', $numerator * 100 / $player->games_played );
		}
		echo '</td>';

		echo '<td class="numeric">' . $player->average_score . '</td>';
		echo '<td class="numeric">' . $player->average_opponent_score . '</td>';
		echo '<td class="numeric">' . $player->average_spread . '</td>';
		echo '<td class="numeric">' . $player->best_score . '</td>';
		echo '<td class="numeric">' . $player->best_spread . '</td>';

		echo "</tr>\n";
}
?>
	</tbody>
</table>

<script>
$(document).ready( function() {

	$.tablesorter.addParser({
		// set a unique id
		id: 'sc_record',
		is: function(s) {
			// return false so this parser is not auto detected
			return false;
		},
		format: function(s) {
			// format your data for normalization
			var p = s.split(' ');
			if (p.length==3) {
				var n = parseFloat(p[0]);
				var d = parseFloat(p[2]);
				if (n+d == 0)  return -1;
				return n/(n+d);
			}
			return -1;
		},
		// set type, either numeric or text
		type: 'numeric'
	});


	$('table.sortable').tablesorter({
		headers: {
			2: { sorter: 'sc_record' }
		},
		sortList: [[2,1], [1,1]]
	});


});
</script>
