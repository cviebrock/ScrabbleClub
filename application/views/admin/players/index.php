<div class="page-header">
	<?php echo action_link_to_route('admin.players@new', '', array(), 'plus|white', array('class'=>'btn btn-primary pull-right')); ?>
	<h1>Players</h1>
</div>


<table class="table table-striped table-bordered sortable">
	<thead>
		<tr>
			<th class="span2">First Name</th>
			<th class="span2">Last Name</th>
			<th class="span1">NASPA #</th>
			<th class="span1">NASPA Rating</th>
			<th class="span3">Actions</th>
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
		echo '<td><ul class="sc_actions">' .
			'<li>' . action_link_to_route('admin.players@edit', 'Edit', array($player->id), 'small|pencil' ) . '</li>' .
			'<li>' . action_link_to_route('admin.players@delete', 'Delete', array($player->id), 'small|remove' ) . '</li>' .
		 '</ul></td>';
		echo "<tr>\n";
}
?>
	</tbody>
</table>

<?php echo action_link_to_route('admin.players@new', 'Add new player', array(), 'plus|white', array('class'=>'btn btn-primary')); ?>

<script>
$(document).ready( function() {
	$('table.sortable').tablesorter({
		sortList: [[1,0], [0,0]],
		headers: { 4: { sorter: false } }
	});
});
</script>
