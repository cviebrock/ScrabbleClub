<h1>Create Matching Game</h1>

<p>
	This will create the matching game to the game below (i.e. the game
	from the other player&rsquo;ls perspective). Use this if the opponent
	didn&rsquo;t enter the game on their sheet for some reason.
</p>


<h2>Game Entered</h2>

<table class="games tablesorter">
	<thead>
		<tr>
			<th>Date</th>
			<th>Player</th>
			<th>Score</th>
			<th>Opponent</th>
			<th>Opp. Score</th>
			</tr>
	</thead>
	<tbody>
		<tr>
			<td><?php echo App::format_date($game->date); ?></td>
			<td><?php echo $game->opponent->fullname(); ?></td>
			<td class="numeric"><?php echo $game->opponent_score; ?></td>
			<td><?php echo $game->player->fullname(); ?></td>
			<td class="numeric"><?php echo $game->player_score; ?></td>
		</tr>
	</tbody>
</table>

<h2>Matching Game to be Created</h2>

<table class="games tablesorter">
	<thead>
		<tr>
			<th>Date</th>
			<th>Player</th>
			<th>Score</th>
			<th>Opponent</th>
			<th>Opp. Score</th>
			</tr>
	</thead>
	<tbody>
		<tr>
			<td><?php echo App::format_date($game->date); ?></td>
			<td><?php echo $game->player->fullname(); ?></td>
			<td class="numeric"><?php echo $game->player_score; ?></td>
			<td><?php echo $game->opponent->fullname(); ?></td>
			<td class="numeric"><?php echo $game->opponent_score; ?></td>
		</tr>
	</tbody>
</table>

<?php


echo Form::open();
echo Form::token();

echo "<ul class=\"form\">\n";

echo '<li' . ( App::has_errors($game,'confirm') ? ' class="err"' : '' ) . '>' .
	Form::checkbox('confirm', 'yes') .
	Form::label('confirm', 'Confirm to add match', array('class'=>'required inline')) .
	App::errors_for($game,'confirm') .
	"</li>\n";

echo '<li>' .
	Form::submit('Create Match') .
	HTML::link_to_route('admin_games_list', 'Back to Games List', array($game->date), array('class'=>'btn')) .
	"</li>\n";

echo "</ul>\n";


echo Form::close();


?>