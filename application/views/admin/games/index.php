<div class="page-header">
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
foreach ($games as $game) {
		$class = $game->unmatched_games ? 'unmatched' : '';
		echo '<tr>';
		echo '<td>' . App::format_date($game->date) . '</td>';
		echo '<td class="numeric">' . $game->players . '</td>';
		echo '<td class="numeric">' . $game->complete_games . '</td>';
		echo '<td class="numeric ' . $class . '">' . $game->unmatched_games . '</td>';
		echo '<td><ul class="sc_actions">' .
			'<li>' . App::action_link_to_route('admin_games_list', 'View', array($game->date), 'small|search' ) . '</li>' .
			'</td>';
		echo "<tr>\n";
}
?>
	</tbody>
</table>

<?php echo App::action_link_to_route('admin_games_new', 'Add new games', null, 'plus|white', array('class'=>'btn btn-primary')); ?>

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