<div class="page-header">
	<h1>Create Matching Game</h1>
</div>

<p>
	This will create the matching game to the game below (i.e. the game
	from the other player&rsquo;ls perspective). Use this if the opponent
	didn&rsquo;t enter the game on their sheet for some reason.
</p>


<h2>Game Entered</h2>

<table class="table table-striped table-bordered sortable">
	<thead>
		<tr>
			<th class="span2">Date</th>
			<th class="span2">Player</th>
			<th class="span1">Player Score</th>
			<th class="span2">Opponent</th>
			<th class="span1">Opp. Score</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?php echo format_date($game->date); ?></td>
			<td><?php echo $game->opponent->fullname; ?></td>
			<td class="numeric"><?php echo $game->opponent_score; ?></td>
			<td><?php echo $game->player->fullname; ?></td>
			<td class="numeric"><?php echo $game->player_score; ?></td>
		</tr>
	</tbody>
</table>

<h2>Matching Game to be Created</h2>

<table class="table table-striped table-bordered sortable">
	<thead>
		<tr>
			<th class="span2">Date</th>
			<th class="span2">Player</th>
			<th class="span1">Player Score</th>
			<th class="span2">Opponent</th>
			<th class="span1">Opp. Score</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?php echo format_date($game->date); ?></td>
			<td><?php echo $game->player->fullname; ?></td>
			<td class="numeric"><?php echo $game->player_score; ?></td>
			<td><?php echo $game->opponent->fullname; ?></td>
			<td class="numeric"><?php echo $game->opponent_score; ?></td>
		</tr>
	</tbody>
</table>

<?php


echo Form::open(null,'post', array('class'=>Form::TYPE_HORIZONTAL));
echo Form::token();

echo Form::field('labelled_checkbox', 'confirm', 'Are you sure?',
	array('Yes, create matching game', 1, false)
);

echo Form::actions(array(
	Form::submit('Create Match', array('class' => 'btn-primary')),
	action_link_to_route('admin.games@bydate', 'Back to Games List', array($game->date), 'arrow-left')
));

echo Form::close();

?>