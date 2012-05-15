<div class="page-header">
	<h1>Edit Game</h1>
</div>

<?php

echo Form::open(null,'post', array('class'=>Form::TYPE_HORIZONTAL));
echo Form::token();


echo Form::field('date', 'date', 'Date',
	array($game->date, array('class'=>'span2 required')),
	array('error' => $game->error('date'))
);

echo Form::field('select', 'player_id', 'Player',
	array($all_players, $game->player_id, array('class'=>'span4 required qselect')),
	array('error' => $game->error('player_id'))
);

echo Form::field('number', 'player_score', 'Player Score',
	array($game->player_score, array('class'=>'span2 required')),
	array('error' => $game->error('player_score'))
);

echo Form::field('select', 'opponent_id', 'Opponent',
	array($all_players, $game->opponent_id, array('class'=>'span4 required qselect')),
	array('error' => $game->error('opponent_id'))
);

echo Form::field('number', 'opponent_score', 'Opponent Score',
	array($game->opponent_score, array('class'=>'span2 required')),
	array('error' => $game->error('opponent_score'))
);



echo Form::actions(array(
	Form::submit('Save Changes', array('class' => 'btn-primary')),
	App::action_link_to_route('admin.games@bydate', 'Back to Games List', array($game->date), 'arrow-left')
));

echo Form::close();

?>


<script>
$(document).ready( function() {

	$('.qselect').quickselect({
		minChars: 1,
		delay: 10,
		autoSelectFirst: true
	});


	$("#date").dateinput({
		format: 'dd-mmm-yyyy',
		value: '<?php echo $game->date; ?>'
	});


});
</script>
