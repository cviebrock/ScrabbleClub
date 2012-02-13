<h1>Games for <?php echo $fdate; ?></h1>

<h2>Matched Games</h2>

<table id="matched" class="tablesorter">
	<thead>
		<tr>
			<th>Winner</th>
			<th>Winner Score</th>
			<th>Loser</th>
			<th>Loser Score</th>
			<th>Spread</th>
			<th>Actions</th>
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
		echo '<td><ul class="actions">' .
#			HTML::link_to_route('view_games', 'view', array($game->date) ) .
		 '</ul></td>';
		echo "<tr>\n";
}
?>
	</tbody>
</table>


<h2>Unmatched Games</h2>

<table id="unmatched" class="tablesorter">
	<thead>
		<tr>
			<th>Player</th>
			<th>Player Score</th>
			<th>Opponent</th>
			<th>Oppenent Score</th>
			<th>Spread</th>
			<th>Actions</th>
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
		echo '<td><ul class="actions">' .
			'<li>' . HTML::link_to_route('admin_game_edit', 'edit', array($game->id) ) . '</li>' .
			'<li>' . HTML::link_to_route('admin_game_create_match', 'match', array($game->id) ) . '</li>' .
			'<li>' . HTML::link_to_route('admin_game_delete', 'delete', array($game->id) ) . '</li>' .
		 '</ul></td>';
		echo "<tr>\n";
}
?>
	</tbody>
</table>

<?php echo HTML::link_to_route('admin_games_new', 'Add new games', null, array('class'=>'btn')); ?>
<?php echo HTML::link_to_route('admin_games', 'Back to games', null, array('class'=>'btn')); ?>




<script>
$(document).ready( function() {
	$('#matched').tablesorter({
		sortList: [[4,1]],
		headers: { 5: { sorter: false } }
	});
	$('#unmatched').tablesorter({
//		sortList: [[0,0], [2,0]],
		headers: { 5: { sorter: false } }
	});
});
</script>