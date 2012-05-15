<div class="page-header">
	<h1>Games for <?php echo $fdate; ?></h1>
</div>




<?php if (count($unmatched_games)): ?>

<h2>Unmatched Games</h2>

<table id="unmatched" class="table table-striped table-bordered sortable">
	<thead>
		<tr>
			<th class="span3">Winner</th>
			<th class="span1">Winner Score</th>
			<th class="span3">Loser</th>
			<th class="span1">Loser Score</th>
			<th class="span1">Spread</th>
			<th class="span3">Actions</th>
		</tr>
	</thead>
	<tbody>
<?php
foreach ($unmatched_games as $game) {
		echo '<tr>';
		echo '<td>' . $game->player->fullname() . '</td>';
		echo '<td class="numeric">' . $game->player_score . '</td>';
		echo '<td>' . $game->opponent->fullname() . '</td>';
		echo '<td class="numeric">' . $game->opponent_score . '</td>';
		echo '<td class="numeric">' . $game->spread . '</td>';
		echo '<td><ul class="sc_actions">' .
			'<li>' . App::action_link_to_route('admin.games@edit', 'edit', array($game->id), 'small|pencil' ) . '</li>' .
			'<li>' . App::action_link_to_route('admin.games@create_match', 'match', array($game->id), 'small|magnet' ) . '</li>' .
			'<li>' . App::action_link_to_route('admin.games@delete', 'delete', array($game->id), 'small|remove' ) . '</li>' .
		 '</ul></td>';
		echo "<tr>\n";
}
?>
	</tbody>
</table>

<?php endif; ?>

<?php if (count($matched_games)): ?>

<h2>Matched Games</h2>

<table id="matched" class="table table-striped table-bordered sortable">
	<thead>
		<tr>
			<th class="span3">Winner</th>
			<th class="span1">Winner Score</th>
			<th class="span3">Loser</th>
			<th class="span1">Loser Score</th>
			<th class="span1">Spread</th>
			<th class="span3">Actions</th>
		</tr>
	</thead>
	<tbody>
<?php
foreach ($matched_games as $game) {
		echo '<tr>';
		echo '<td>' . $game->player->fullname() . '</td>';
		echo '<td class="numeric">' . $game->player_score . '</td>';
		echo '<td>' . $game->opponent->fullname() . '</td>';
		echo '<td class="numeric">' . $game->opponent_score . '</td>';
		echo '<td class="numeric">' . $game->spread . '</td>';
		echo '<td><ul class="sc_actions">' .
#			HTML::link_to_route('view_games', 'view', array($game->date) ) .
		 '</ul></td>';
		echo "<tr>\n";
}
?>
	</tbody>
</table>

<?php endif; ?>


<?php echo App::action_link_to_route('admin.games@new', 'Add new games', array($date), 'plus|white', array('class'=>'btn btn-primary')); ?>

<?php echo App::action_link_to_route('admin.games', 'Back to games', array(), 'arrow-left', array('class'=>'btn')); ?>


<script>
$(document).ready( function() {
	$('#matched').tablesorter({
		sortList: [[4,1]],
		headers: { 5: { sorter: false } }
	});
	$('#unmatched').tablesorter({
		headers: { 5: { sorter: false } }
	});
});
</script>