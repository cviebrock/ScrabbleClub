<div class="page-header">
	<?php echo App::action_link_to_route('admin.games@new', '', array(), 'plus|white', array('class'=>'btn btn-primary pull-right')); ?>
	<h1>Games</h1>
</div>


<table class="table table-striped table-bordered sortable">
	<thead>
		<tr>
			<th class="span2">Date</th>
			<th class="span1">Players</th>
			<th class="span1">Complete Games</th>
			<th class="span1">Unmatched Games</th>
			<th class="span3">Actions</th>
		</tr>
	</thead>
	<tbody>
<?php

$shown_update = false;

foreach ($games as $game) {
		$class = $game->unmatched_games ? 'unmatched' : '';
		echo '<tr>';
		echo '<td>' . App::format_date($game->date) . '</td>';
		echo '<td class="numeric">' . $game->players . '</td>';
		echo '<td class="numeric">' . $game->complete_games . '</td>';
		echo '<td class="numeric ' . $class . '">' . $game->unmatched_games . '</td>';
		echo '<td><ul class="sc_actions">';
		echo '<li>' . App::action_link_to_route('admin.games@bydate', 'View', array($game->date), 'small|search' ) . '</li>';
		if (!$game->unmatched_games) {
			if (array_key_exists($game->date, $ratings) &&
				$ratings[$game->date]->players == $game->players &&
				$ratings[$game->date]->games_played == 2*$game->complete_games
			) {
				echo '<li>' . App::action_link_to_route('admin.games@ratings', 'View Ratings', array($game->date), 'small|signal' ) . '</li>';
			} else if (!$shown_update) {
				echo '<li>' . App::action_link_to_route('admin.games@update_ratings', 'Update Ratings', array($game->date), 'small|signal' ) . '</li>';
				$shown_update = true;
			}
		}

		echo '</ul></td>';
		echo "<tr>\n";
}
?>
	</tbody>
</table>

<?php echo App::action_link_to_route('admin.games@new', 'Add new games', array(), 'plus|white', array('class'=>'btn btn-primary pull-right')); ?>


<script>
$(document).ready( function() {

	$('table.sortable').tablesorter({
		sortList: [[0,1]],
		headers: {
			0: { sorter: 'sc_date' },
			4: { sorter: false }
		}
	});

});
</script>

