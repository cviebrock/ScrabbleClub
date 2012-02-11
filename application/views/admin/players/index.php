<h1>Players</h1>

<table class="tablesorter">
	<thead>
		<tr>
			<th>First Name</th>
			<th>Last Name</th>
			<th>NASPA #</th>
			<th>NASPA Rating</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
<?php
foreach ($players as $player) {
		echo '<tr>';
		echo '<td>' . $player->firstname . '</td>';
		echo '<td>' . $player->lastname . '</td>';
		echo '<td>' . ($player->naspa_id ? $player->naspa_id : '&mdash;') . '</td>';
		echo '<td>' . ($player->naspa_rating ? $player->naspa_rating : '&mdash;') . '</td>';
		echo '<td><ul class="actions">' .
			'<li>' . HTML::link_to_route('admin_player_edit', 'edit', array($player->id) ) . '</li>' .
			'<li>' . HTML::link_to_route('admin_player_delete', 'delete', array($player->id) ) . '</li>' .
		 '</ul></td>';
		echo "<tr>\n";
}
?>
	</tbody>
</table>

<?php echo HTML::link_to_route('admin_player_new', 'Add new player', null, array('class'=>'btn')); ?>

<script>
$(document).ready( function() {
	$('table.tablesorter').tablesorter({
		sortList: [[1,0], [0,0]],
//		widgets: ['zebra'],
		headers: { 4: { sorter: false } }
	});
});
</script>
