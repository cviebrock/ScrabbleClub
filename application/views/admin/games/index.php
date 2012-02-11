<h1>Games</h1>

<table class="tablesorter">
	<thead>
		<tr>
			<th>Date</th>
			<th>Players</th>
			<th>Complete Games</th>
			<th>Unmatched Games</th>
			<th>Actions</th>
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
		echo '<td class="actions">' .
			HTML::link_to_route('admin_games_list', 'view', array($game->date) ) .
		 '</td>';
		echo "<tr>\n";
}
?>
	</tbody>
</table>

<?php echo HTML::link_to_route('admin_games_new', 'Add new games', null, array('class'=>'btn')); ?>

<script>
$(document).ready( function() {
	$('table.tablesorter').tablesorter({
		sortList: [[0,1]],
		headers: { 4: { sorter: false } }
	});
});
</script>