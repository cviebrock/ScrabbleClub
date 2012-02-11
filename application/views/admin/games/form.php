<h1>New Games Entry</h1>
<script>
$(document).ready( function() {

	$('.qselect').quickselect({
		minChars: 1,
		delay: 10,
		autoSelectFirst: true
	});

	$("#date").dateinput({
		format: 'dd-mmm-yyyy',
		value: '<?php echo $gameform->date; ?>'
	});

	var eg_bingo = '';
	for (var $i=0; $i<3; $i++) {
		eg_bingo += ($i ? ', ' : '');
		eg_bingo += rword() + ' ' + (Math.floor(Math.random()*41)+60);
	}
	$("#bingos").val( eg_bingo );


});
</script>

<?php

$tabindex = 1;

echo Form::open();
echo Form::token();

echo "<ul class=\"form\">\n";

echo '<li' . ( App::has_errors($gameform,'player_id') ? ' class="err"' : '' ) . '>' .
	Form::label('player_id', 'Player', array('class'=>'required')) .
	Form::select('player_id', $all_players, $gameform->player_id, array('class'=>'qselect','tabindex'=>$tabindex++)) .
	App::errors_for($gameform,'player_id') .
	"</li>\n";

echo '<li' . ( App::has_errors($gameform,'date') ? ' class="err"' : '' ) . '>' .
	Form::label('date', 'Date', array('class'=>'required')) .
	Form::date('date', '' ) .
	App::errors_for($gameform,'date') .
	"</li>\n";

?>

<li>
	<fieldset>
	<legend class="required">Games</legend>

	<table class="games">
		<thead>
			<tr>
				<th>Score</th>
				<th>Opponent</th>
				<th>Opp. Score</th>
				</tr>
		</thead>
		<tbody>

<?php

$c = max(6, count($gameform->games));

for($i=0; $i<$c; $i++) {

	if (array_key_exists($i, $gameform->games)) {
		$game = $gameform->games[$i];
	} else {
		$game = new Game;
	}

	echo "<tr>\n";

	echo '<td' . ( App::has_errors($game,'player_score') ? ' class="err"' : '' ) . '>' .
		Form::number('player_score['.$i.']', $game->player_score, array('tabindex'=>$tabindex++)) .
		App::errors_for($game,'player_score',false) .
		"</td>\n";

	echo '<td' . ( App::has_errors($game,'opponent_id') ? ' class="err"' : '' ) . '>' .
		Form::select('opponent_id['.$i.']', $all_players, $game->opponent_id, array('class'=>'qselect','tabindex'=>$tabindex++)) .
		App::errors_for($game,'opponent_id',false) .
		"</td>\n";

	echo '<td' . ( App::has_errors($game,'opponent_score') ? ' class="err"' : '' ) . '>' .
		Form::number('opponent_score['.$i.']', $game->opponent_score, array('tabindex'=>$tabindex++)) .
		App::errors_for($game,'opponent_score',false) .
		"</td>\n";

	echo "</tr>\n";

}

?>
		</tbody>
	</table>

	</fieldset>
</li>

<?php


echo '<li' . ( App::has_errors($gameform,'bingo_list') ? ' class="err"' : '' ) . '>' .
	Form::label('bingo_list', 'Bingos') .
	App::help_for($gameform,'bingo_list') .
	Form::textarea('bingo_list', $gameform->bingo_list, array('placeholder'=>App::rwords(),'tabindex'=>$tabindex++) ) .
	App::errors_for($gameform,'bingo_list',false) .
	"</li>\n";

echo "<li>" .
	Form::submit('Save Games',array('tabindex'=>$tabindex++)) .
	HTML::link_to_route('admin_games', 'Back to Games List', null, array('class'=>'btn')) .
	"</li>\n";

echo "</ul>\n";


echo Form::close();


?>