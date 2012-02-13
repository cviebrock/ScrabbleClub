<h1>Edit Game</h1>

<script type="text/javascript">

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


<?php


echo Form::open();
echo Form::token();

echo "<ul class=\"form\">\n";

echo '<li' . ( App::has_errors($game,'date') ? ' class="err"' : '' ) . '>' .
	Form::label('date', 'Date', array('class'=>'required')) .
	Form::date('date', '' ) .
	App::errors_for($game,'date') .
	"</li>\n";


echo '<li' . ( App::has_errors($game,'player_id') ? ' class="err"' : '' ) . '>' .
	Form::label('player_id', 'Player', array('class'=>'required')) .
	Form::select('player_id', $all_players, $game->player_id, array('class'=>'qselect')) .
	App::errors_for($game,'player_id') .
	"</li>\n";

echo '<li' . ( App::has_errors($game,'player_score') ? ' class="err"' : '' ) . '>' .
	Form::label('player_score', 'Player Score', array('class'=>'required')) .
	Form::number('player_score', $game->player_score) .
	App::errors_for($game,'player_score',false) .
	"</li>\n";

echo '<li' . ( App::has_errors($game,'opponent_id') ? ' class="err"' : '' ) . '>' .
	Form::label('opponent_id', 'Opponent', array('class'=>'required')) .
	Form::select('opponent_id', $all_players, $game->opponent_id, array('class'=>'qselect')) .
	App::errors_for($game,'opponent_id') .
	"</li>\n";

echo '<li' . ( App::has_errors($game,'opponent_score') ? ' class="err"' : '' ) . '>' .
	Form::label('opponent_score', 'Opponent Score', array('class'=>'required')) .
	Form::number('opponent_score', $game->opponent_score) .
	App::errors_for($game,'opponent_score',false) .
	"</li>\n";


echo "<li>" .
	Form::submit('Save Changes') .
	HTML::link_to_route('admin_games_list', 'Back to Games List', array($game->date), array('class'=>'btn')) .
	"</li>\n";

echo "</ul>\n";


echo Form::close();


?>