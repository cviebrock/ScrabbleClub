<h1>Players</h1>

<table class="tablesorter">
	<thead>
		<tr>
			<th>Name</th>
			<th>Record</th>
			<th>Ratio</th>
			<th>Average Score</th>
			<th>Best Score</th>
			<th>Average Spread</th>
		</tr>
	</thead>
	<tbody>
<?php
foreach ($players as $player) {

		$numerator = $player->wins + ($player->ties / 2 );

		if ($player->games_played == 0) {
			$ratio = null;
		} else {
			$ratio = $numerator / $player->games_played;
		}

		echo '<tr>';
		echo '<td>' . $player->fullname . '</td>';
		echo '<td>' . sprintf('%.1f%s%.1f',
				$numerator,
				' &ndash; ',
				$player->losses
			) . '</td>';
		echo '<td>' .
			( $ratio ? sprintf('%1.3f', $ratio) : '' ) .
			'</td>';
		echo "<tr>\n";
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

	$('table.tablesorter').tablesorter({
		headers: {
			1: { sorter: 'sc_record' }
		},
		sortList: [[1,1]]
	});
});
</script>
