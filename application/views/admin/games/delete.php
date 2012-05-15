<div class="page-header">
	<h1>Delete Game</h1>
</div>


<table class="table table-striped table-bordered sortable">
	<thead>
		<tr>
			<th class="span2">Date</th>
			<th class="span2">Player</th>
			<th class="span1">Player Score</th>
			<th class="span2">Opponent</th>
			<th class="span1">Opp. Score</th>
			<th class="span1">Spread</th>
		</tr>
	</thead>
	<tbody>
<?php
	echo '<tr>';
	echo '<td>' . App::format_date($game->date) . '</td>';
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

echo Form::open(null,'post', array('class'=>Form::TYPE_HORIZONTAL));
echo Form::token();

echo Form::field('labelled_checkbox', 'confirm', 'Are you sure?',
	array('Yes, delete this game', 1, false)
);

echo Form::actions(array(
	Form::submit('Delete Game', array('class' => 'btn-primary btn-warning')),
	App::action_link_to_route('admin.games@index', 'Back to Games List', array(), 'arrow-left')
));


echo Form::close();

?>