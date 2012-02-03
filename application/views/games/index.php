<h1>Games</h1>

<table class="tablesorter">
	<thead>
		<tr>
			<th>Date</th>
			<th>Games Recorded</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
<?php
foreach ($games as $game) {
		echo '<tr>';
		echo '<td>' . $game->date . '</td>';
		echo '<td>' . $game->count . '</td>';
		echo '<td class="actions">' .
#			HTML::link_to_route('new_games', 'add', array($game->date) ) .
		 '</td>';
		echo "<tr>\n";
}
?>
	</tbody>
</table>

<?php echo HTML::link_to_route('new_games', 'Add new games', null, array('class'=>'btn')); ?>

<script>
$(document).ready( function() {
	$('table.tablesorter').tablesorter({
		sortList: [[0,1]],
		headers: { 2: { sorter: false } }
	});
});
</script>