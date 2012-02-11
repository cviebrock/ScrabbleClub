<h1>Delete Game</h1>

<p>
	Do you really want to delete this game?
</p>

<table class="tablesorter">
	<thead>
		<tr>
			<th>Player</th>
			<th>Player Score</th>
			<th>Opponent</th>
			<th>Oppenent Score</th>
			<th>Spread</th>
		</tr>
	</thead>
	<tbody>
<?php
	echo '<tr>';
	echo '<td>' . $game->player->fullname() . '</td>';
	echo '<td class="numeric">' . $game->player_score . '</td>';
	echo '<td>' . $game->opponent->fullname() . '</td>';
	echo '<td class="numeric">' . $game->opponent_score . '</td>';
	echo '<td class="numeric">' . $game->spread . '</td>';
	echo "<tr>\n";
?>
	</tbody>
</table>

<?php

echo Form::open();
echo Form::token();

echo "<ul class=\"form\">\n";

echo '<li' . ( App::has_errors($game,'confirm') ? ' class="err"' : '' ) . '>' .
	Form::checkbox('confirm', 'yes') .
	Form::label('confirm', 'Confirm', array('class'=>'required inline')) .
	App::errors_for($game,'confirm') .
	"</li>\n";

echo "<li>" .
	Form::submit('Delete game') .
	HTML::link_to_route('admin_games_list', 'Back to game list', array($game->date), array('class'=>'btn')) .
	"</li>\n";

echo "</ul>\n";

echo Form::close();


?>

<script>
$(document).ready( function() {
	$('table.tablesorter').tablesorter(
		sortList: [[0,1]],
	});
});
</script>