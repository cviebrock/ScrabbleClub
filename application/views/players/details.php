<div class="page-header">
	<h1><?php echo $player->fullname(); ?></h1>
</div>


<h2>Player Statistics</h2>

<?php

	$numerator = $club_details->wins + ($club_details->ties / 2 );
	$record = sprintf('%.1f-%.1f',
		$numerator,
		$club_details->losses
	);
	$percentage = ( $club_details->games_played ?
		sprintf('%.1f%%', $numerator * 100 / $club_details->games_played ) :
		'&mdash;'
	);

?>

<table class="table table-condensed table-auto">
	<tbody>
		<tr>
			<th class="span3 horizontal-header">Current Club Rating</th>
			<td class="span4"><?php echo $player->current_rating(); ?>
			<?php echo HTML::link_to_action('players@ratings',
				'<i class="icon-list icon-small" title="show all ratings calculations"></i>',
				array($player->id),
				array('class'=>'pull-right')
			); ?></td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Dates Played</th>
			<td class="span4"><?php echo $club_details->dates_played; ?></td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Games Played</th>
			<td class="span4"><?php echo $club_details->games_played; ?>
			<?php echo HTML::link_to_action('players@games',
				'<i class="icon-list icon-small" title="show all games"></i>',
				array($player->id),
				array('class'=>'pull-right')
			); ?>
			</td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Record</th>
			<td class="span4"><?php echo $record; ?></td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Winning Percentage</th>
			<td class="span4"><?php echo $percentage; ?></td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Wins</th>
			<td class="span4"><?php echo $club_details->wins; ?></td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Losses</th>
			<td class="span4"><?php echo $club_details->losses; ?></td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Ties</th>
			<td class="span4"><?php echo $club_details->ties; ?></td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Average Score</th>
			<td class="span4"><?php echo $club_details->average_score; ?></td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Average Opp. Score</th>
			<td class="span4"><?php echo $club_details->average_opponent_score; ?></td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Average Spread</th>
			<td class="span4"><?php echo $club_details->average_spread; ?></td>
		</tr>
	</tbody>
</table>

<h2>Club Rating</h2>

<div id="graph_rating" style="width: 940px; height: 200px;"></div>


<h2>Bingos</h2>


<table class="table table-condensed table-auto">
	<tbody>
		<tr>
			<th class="span3 horizontal-header">Bingos Played</th>
			<td class="span4"><?php echo $bingos['count']; ?>
				<?php echo HTML::link_to_action('players@bingos',
						'<i class="icon-list icon-small" title="show all bingos"></i>',
						array($player->id),
						array('class'=>'pull-right')
						); ?>
			</td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Average Bingos/Game</th>
			<td class="span4"><?php echo ($club_details->games_played ?
				sprintf('%.1f', $bingos['count'] / $club_details->games_played) :
				'&mdash;'
				); ?></td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Phoney Frequency</th>
			<td class="span4"><?php echo ($bingos['count'] ?
				sprintf('%.0f%%', 100 * $bingos['phoney'] / $bingos['count']) :
				'&mdash;'
				); ?></td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Best Bingo</th>
			<td class="span4"><?php echo $bingos['best'] ? $bingos['best']->word_and_score() : '&mdash;'; ?></td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Worst Bingo</th>
			<td class="span4"><?php echo $bingos['worst'] ? $bingos['worst']->word_and_score() : '&mdash;'; ?></td>
		</tr>
		<tr>
			<th class="span3 horizontal-header">Rarest Bingo</th>
			<td class="span4">
			<?php if ($bingos['rarest']): ?>
			<?php echo $bingos['rarest']->word_and_score(); ?> <small class="muted">Playability: <?php echo $bingos['rarest']->playability; ?></small>
			<?php else: ?>
			&mdash;
			<?php endif; ?>
		</td>
		</tr>
	</tbody>
</table>



<h2>NASPA Details</h2>

<table class="table table-condensed table-auto">
	<tbody>
		<tr>
			<th class="span3 horizontal-header">Number</th>
			<td class="span4"><?php echo $player->naspa_id; ?>
				<a href="http://www.cross-tables.com/players.php?query=<?php echo HTML::entities($player->fullname()); ?>"
					class="pull-right" target="_blank"><i class="icon-share-alt icon-small" title="search on cross-tables.com"></i></a>
			</td>
		</tr>
		<tr>
			<th class="horizontal-header">Rating</th>
			<td><?php echo ($player->naspa_rating ? $player->naspa_rating : '&mdash;'); ?></td>
		</tr>
	</tbody>
</table>



<h2>High Score</h2>
<?php
	echo View::make('partials.game_listing')
		->with('games', $high_score)
		->with('id', 'high_score')
		->with('mark_winners', true)
		->with('small_head', true)
		->render();
?>

<h2>Low Score</h2>
<?php
	echo View::make('partials.game_listing')
		->with('games', $low_score)
		->with('id', 'low_score')
		->with('mark_winners', true)
		->with('small_head', true)
		->render();
?>


<h2>Best Win (biggest positive spread)</h2>
<?php
	echo View::make('partials.game_listing')
		->with('games', $best_spread)
		->with('id', 'best_spread')
		->with('mark_winners', true)
		->with('small_head', true)
		->render();
?>


<h2>Worst Loss (biggest negative spread)</h2>
<?php
	echo View::make('partials.game_listing')
		->with('games', $worst_spread)
		->with('id', 'worst_spread')
		->with('mark_winners', true)
		->with('small_head', true)
		->render();
?>


<h2 id="one_on_one">One-on-One History versus
<?php echo Form::select('opponent', $all_players, null, array('id'=>'ooo_select','class'=>'span4')); ?>
<div id="ooo_loader"></div>
</h2>

<div id="ooo_results">
<p class="muted">
	Select a name above to load one-on-one history data.
</p>
</div>

<script>
$(function() {

	var ooo_url = '<?php echo URL::to_route('ajax_one_on_one',array($player->id)); ?>/';
	$('#ooo_loader').hide();

	$('#ooo_select').quickselect({
		minChars: 1,
		delay: 10,
		autoSelectFirst: true,
		exactMatch: true,
		onItemSelect: function() {

			var opp_id = this.additionalFields[0].value;

			if (opp_id) {

				$('#ooo_loader').show();
				$('#ooo_results').load(
					ooo_url+opp_id,
					function() {
						$('#ooo_loader').hide();
						$('#ooo_games').tablesorter({
							sortList: [[0,1]],
							headers: {
								0: { sorter: 'sc_date' },
								1: { sorter: false },
								3: { sorter: false }
							}
						});
					}
				);

			}

		}
	});

});
</script>

<script type="text/javascript">
$(function() {

	var chart = new Highcharts.Chart({
		chart: {
			renderTo: 'graph_rating',
			type: 'areaspline'
		},
		credits: {
			enabled: false
		},
		plotOptions: {
			series: {
				fillOpacity: 0.5
			}
		},
		title: {
			text: null
		},
		xAxis: {
			type: 'datetime',
			dateTimeLabelFormats: {
				month: '%b<br>%Y',
			},
<?php
$first = reset($ratings);
$date = new DateTime($first->date);
$year = $date->format('Y');
?>
			min: Date.UTC(<?php echo $year; ?>,0,1),
			max: Date.UTC(<?php echo $year+1; ?>,0,0),
			minRange: 1000*60*60*24*7  // one week
		},
		yAxis: {
			title: {
				text: null
			},
			formatter: function() {
				return this.value;
			}
		},
		legend: {
			enabled: false
		},
		tooltip: {
			formatter: function() {
				return Highcharts.dateFormat('%e-%b-%Y', this.x) +': '+ this.y;
			}
		},
		series: [{
			name: 'Club Rating',
			data: [
<?php
	foreach($ratings as $rating) {
		$date = new DateTime($rating->date);
		printf("\t\t\t\t[ Date.UTC(%4d, %-2d, %-2d), %d ],\n",
			$date->format('Y'),
			$date->format('m')-1,
			$date->format('d'),
			$rating->ending_rating
		);
	}
?>
			]
		}]
	});
});
</script>
