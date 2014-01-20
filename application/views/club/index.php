<?php echo View::make('partials.year_picker')->with('year', $year)->render(); ?>

<div class="page-header">
	<h1>Club Statistics
		<span class="subhead"><?php echo $year ? 'For '.$year : 'All games'; ?></span>
	</h1>
</div>

<h2>Overall Statistics</h2>

<table class="table table-auto">
	<tbody>
		<tr>
			<th class="span3 horizontal-header">Dates Played</th>
			<td class="span1 numeric"><?php echo (int)$overall['total_dates']; ?></td>
			<td class="span3"></td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Average Players per Date</th>
			<td class="span1 numeric"><?php echo round($overall['players_per_date']); ?></td>
			<td class="span3 spark">
				<div id="graph_attendance" class="sparkline" style="height: 30px; width: 250px;">
			</td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Total Games Played</th>
			<td class="span1 numeric"><?php echo (int)$overall['total_games']; ?></td>
			<td class="span3"></td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Average Games/Night</th>
			<td class="span1 numeric"><?php
if ((int)$overall['total_dates']) {
	echo round($overall['total_games']/$overall['total_dates']);
} else {
	echo '&mdash;';
} ?></td>
			<td class="span3"></td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Average Score</th>
			<td class="span1 numeric"><?php echo round($overall['average_score']); ?></td>
			<td class="span3"></td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Bingos Played</th>
			<td class="span1 numeric"><?php echo $overall['total_bingos']; ?></td>
			<td class="span3"></td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Average Bingos/Game</th>
			<td class="span1 numeric"><?php
	if ((int)$overall['total_games']) {
		printf('%.1f', $overall['total_bingos']/$overall['total_games'] );
	} else {
		echo '&mdash;';
	} ?></td>
			<td class="span3 help-text">both players combined</td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Bingo Phoniness</th>
			<td class="span1 numeric"><?php
if ((int)$overall['total_bingos']) {
	printf('%.1f%%', 100*(1-$overall['valid_bingos']/$overall['total_bingos']) );
} else {
	echo '&mdash;';
} ?></td>
			<td class="span3"></td>
		</tr>
	</tbody>
</table>

<h2>Highest Individual Scores</h2>
<?php
	echo View::make('partials.game_listing')
		->with('games', $high_scores)
		->with('id', 'high_score')
		->with('mark_winners', true)
		->with('small_head', true)
		->render();
?>

<h2>Highest Losses</h2>
<?php
	echo View::make('partials.game_listing')
		->with('games', $high_losses)
		->with('id', 'high_losses')
		->with('mark_winners', true)
		->with('small_head', true)
		->render();
?>

<hr>

<h2>Biggest Spreads</h2>
<?php
	echo View::make('partials.game_listing')
		->with('games', $blowouts)
		->with('id', 'high_score')
		->with('mark_winners', true)
		->with('small_head', true)
		->render();
?>

<h2>Highest Combined Scores</h2>
<?php
	echo View::make('partials.game_listing')
		->with('games', $combined)
		->with('id', 'high_score')
		->with('mark_winners', true)
		->with('small_head', true)
		->with('combined', true)
		->render();
?>

<h2>Best Bingos</h2>
<table class="table table-striped table-bordered sortable">
	<thead class="small">
		<tr>
			<th class="span2">Date</th>
			<th class="span3">Player</th>
			<th class="span3">Bingo</th>
			<th class="span1">Score</th>
			<th class="span1">Playability</th>
			<th class="span1">Valid</th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($bingos as $bingo): ?>
		<tr <?php echo ($bingo->valid ? '' : ' class="phoney"'); ?>>
			<td><?php echo format_date($bingo->date); ?></td>
			<td><?php echo $bingo->player->fullname; ?></td>
			<td><?php echo $bingo->word; ?></td>
			<td class="numeric"><?php echo ($bingo->score ? $bingo->score : '&mdash;'); ?></td>
			<td class="numeric"><?php echo ($bingo->playability ? $bingo->playability : '&mdash;'); ?></td>
			<td class="center"><?php echo ($bingo->valid ? '<i class="icon-ok hide-text">1</i>' : '<i class="icon-remove hide-text">0</i>'); ?></td>
		</tr>
<?php endforeach; ?>
	</tbody>
</table>



<script type="text/javascript">
$(function() {

	var chart = new Highcharts.Chart({
		chart: {
			renderTo: 'graph_attendance',
			defaultSeriesType: 'areaspline',
			margin: [0,0,0,0]
		},
		credits: {
			enabled: false
		},
		plotOptions: {
			series: {
				lineWidth: 2,
				shadow: false,
				states: {
					hover: {
						lineWidth: 2
					}
				},
				fillOpacity: 0.5,
				marker: {
					radius: 1,
					states: {
						hover: {
							radius: 2
						}
					}
				}
			}
		},
		title: {
			text: null
		},
		xAxis: {
<?php
$first = reset($attendance);
$date = new DateTime($first->date);
$year = $date->format('Y');
?>
			min: Date.UTC(<?php echo $year; ?>,0,1),
			max: Date.UTC(<?php echo $year+1; ?>,0,1),
			minRange: 1000*60*60*24*365  // one year
		},
		yAxis: {
			maxPadding: 0.2,
			minPadding: 0,
			endOnTick: false,
			min: 0
		},
		legend: {
			enabled: false
		},
		tooltip: {
			formatter: function() {
				return Highcharts.dateFormat('%e-%b-%Y', this.x) +': '+ this.y;
			},
			borderWidth: 1,
			style: {
				fontSize: '10px',
				padding: '2px',
			}
		},
		series: [{
			name: 'Attendance',
			data: [
<?php
	foreach ($attendance as $a) {
		$date = new DateTime($a->date);
		printf("\t\t\t\t[ Date.UTC(%4d, %-2d, %-2d), %d ],\n",
			$date->format('Y'),
			$date->format('m')-1,
			$date->format('d'),
			$a->players
		);
	}
?>
			]
		}]
	});
});

</script>
