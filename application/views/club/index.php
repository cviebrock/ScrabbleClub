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
			<td class="span4"><?php echo round($overall['players_per_date']); ?>
				<div id="graph_attendance" class="sparkline" style="height: 30px; width: 250px;"></div>
			</td>
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


<h2>Squeakers</h2>
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


<script type="text/javascript">
$(function() {

	var chart = new Highcharts.Chart({
		chart: {
			renderTo: 'graph_attendance',
			defaultSeriesType: 'areaspline',
			margin: [0,0,1,0]
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