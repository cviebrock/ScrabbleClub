<?php echo View::make('partials.year_picker')->with('year', $year)->render(); ?>

<div class="page-header">
	<h1>Bingo Statistics
		<span class="subhead"><?php echo $year ? 'For '.$year : 'All games'; ?></span>
	</h1>
</div>

<?php if($all_bingos->total): ?>

<h2>All Bingos</h2>

<table class="table table-condensed table-auto">
	<tbody>
		<tr>
			<th class="span3 horizontal-header">Total Bingos Played</th>
			<td class="span4"><?php echo $all_bingos->total; ?></td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Average Score</th>
			<td class="span4"><?php echo round($all_bingos->average_score); ?></td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Phonies</th>
			<td class="span4">
				<?php echo $all_bingos->phonies; ?> (<?php printf('%.02f', $all_bingos->phoniness); ?>%)
			</td>
		</tr>
	</tbody>
</table>


<h2>Best Bingos</h2>
<?php echo render('partials.bingo_listing', array('bingos'=>$bingos)); ?>


<hr>

<div class="row">
	<div class="span6">

		<h2>Common Bingos</h2>
		<table class="table table-striped table-bordered sortable">
			<thead class="small">
				<tr>
					<th class="span3">Bingo</th>
					<th class="span1">Times Played</th>
					<th class="span1">Average Score</th>
					<th class="span1">Playability</th>
				</tr>
			</thead>
			<tbody>
		<?php foreach ($commonest as $bingo): ?>
				<tr <?php echo ($bingo->valid ? '' : ' class="phoney"'); ?>>
					<td>
						<i class="<?php echo $bingo->valid ? 'icon-ok' : 'icon-remove'; ?> icon-fade"></i>
						<?php echo strtoupper($bingo->word); ?>
					</td>
					<td class="numeric"><?php echo ($bingo->times_played ? $bingo->times_played : '&mdash;'); ?></td>
					<td class="numeric"><?php echo ($bingo->average_score ? $bingo->average_score : '&mdash;'); ?></td>
					<td class="numeric"><?php echo ($bingo->playability ? $bingo->playability : '&mdash;'); ?></td>
				</tr>
		<?php endforeach; ?>
			</tbody>
		</table>

	</div>

	<div class="span6">

		<h2>Common Alphagrams</h2>
		<table class="table table-striped table-bordered sortable">
			<thead class="small">
				<tr>
					<th class="span4">Alphagram</th>
					<th class="span1">Times Played</th>
					<th class="span1">Average Score</th>
				</tr>
			</thead>
			<tbody>
		<?php foreach ($subwords[7] as $alpha): ?>
				<tr>
					<td><?php echo $alpha->word; ?></td>
					<td class="numeric"><?php echo ($alpha->count ? $alpha->count : '&mdash;'); ?></td>
					<td class="numeric"><?php echo ($alpha->average_score ? $alpha->average_score : '&mdash;'); ?></td>
				</tr>
		<?php endforeach; ?>
			</tbody>
		</table>

	</div>
</div>


<h2>Common Stems</h2>
<div class="row">

<?php foreach( array(4,5,6) as $len): ?>

	<div class="span4">
		<h3><?php echo $len; ?>&ndash;Letter</h3>
		<table class="table table-striped table-bordered sortable">
			<thead class="small">
				<tr>
					<th class="span2">Stem</th>
					<th class="span1">Times Played</th>
					<th class="span1">Average Score</th>
				</tr>
			</thead>
			<tbody>
		<?php foreach ($subwords[$len] as $alpha): ?>
				<tr>
					<td><?php echo $alpha->word; ?></td>
					<td class="numeric"><?php echo ($alpha->count ? $alpha->count : '&mdash;'); ?></td>
					<td class="numeric"><?php echo ($alpha->average_score ? $alpha->average_score : '&mdash;'); ?></td>
				</tr>
		<?php endforeach; ?>
			</tbody>
		</table>
	</div>

<?php endforeach; ?>

</div>


<hr>


<h2>Letter Frequency</h2>
<div id="graph_freq" style="width: 940px; height: 350px;"></div>

<h2>Common Endings</h2>
<div id="graph_tails" style="width: 940px; height: 700px;"></div>


<script type="text/javascript">
$(function() {

	var chart = new Highcharts.Chart({
		chart: {
			renderTo: 'graph_freq',
			type: 'column'
		},
		credits: {
			enabled: false
		},
		plotOptions: {
			series: {
				fillOpacity: 0.5,
				borderWidth: 0,
				shadow: false
			}
		},
		title: {
			text: null
		},
		xAxis: {
		 	categories: <?php echo strtoupper(json_encode(array_keys($letter_freq))); ?>
		},
		yAxis: {
			min: 0,
			title: {
				text: 'Frequency (%)',
				style: {
					color: '#999'
				}
			}
		},
		legend: {
			enabled: null
		},
		tooltip: {
			shared: true,
			formatter: function() {
				return '<strong>' + this.x + '</strong>' +
					'<br>' + this.points[1].series.name + ': ' + this.points[1].y +
					'<br>' + this.points[0].series.name + ': ' + this.points[0].y.toFixed(1) + '%';
			}
		},
		series: [
			{
				name: 'Bingo Letter Frequency',
				data: <?php echo json_encode(array_values($letter_freq),JSON_NUMERIC_CHECK); ?>,
			},
			{
				name: 'Tile Distribution',
				type: 'line',
				color: 'orange',
				lineWidth: 1,
				dashStyle: 'dash',
				marker: {
					enabled: false
				},
				data: [ 9, 2, 2, 4, 12, 2, 3, 2, 9, 1, 1, 4, 2, 6, 8, 2, 1, 6, 4, 6, 4, 2, 2, 1, 2, 1 ]
			}
		]
	});

	var chart2 = new Highcharts.Chart({
		chart: {
			renderTo: 'graph_tails',
			type: 'pie'
		},
		credits: {
			enabled: false
		},
		plotOptions: {
			pie: {
				shadow: false,
				dataLabels: {
					formatter: function() {
						return (this.point.y / <?php echo $tail_sum; ?> > 0.01) ? this.point.name : null;
					},
					color: 'white',
					distance: -40
				},
			}
		},
		title: {
			text: null
		},
		legend: {
			enabled: null
		},
		tooltip: {
			formatter: function() {
				console.log(this);
				return '<strong>' + this.point.name + '</strong> stems: ' + this.y +
					' (' + (100*this.y/<?php echo $tail_sum; ?>).toFixed(2) + '%)';
			}
		},
		series: [
			{
				name: '1-Letter Stem',
				size: '30%',
				data: <?php echo json_encode(array_values($tails[0]),JSON_NUMERIC_CHECK); ?>

			},
			{
				name: '2-Letter Stem',
				size: '60%',
				innerSize: '30%',
				data: <?php echo json_encode(array_values($tails[1]),JSON_NUMERIC_CHECK); ?>

			},
			{
				name: '3-Letter Stem',
				size: '100%',
				innerSize: '60%',
				data: <?php echo json_encode(array_values($tails[2]),JSON_NUMERIC_CHECK); ?>

			},
		]
	});


});

</script>


<?php else: ?>

<p>
	There are no statistics available for this time period.
</p>

<?php endif; ?>
