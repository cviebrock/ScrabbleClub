<h1>New Games Entry</h1>
<script>
$(document).ready( function() {

	$.getJSON('<?php echo URL::to_ajax_players(); ?>', function(data) {

		$.each(data, function( k, v) {
			$('<option/>', { value : v[1] }).text(v[0]).appendTo('.qselect');
		});

		$('.qselect').quickselect({
			minChars: 1,
			delay: 10,
			autoSelectFirst: true
		});

	});


	$("#date").dateinput({
		format: 'dd-mmm-yyyy',
		value: '<?php echo $gameform->date(); ?>'
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

echo Form::open();
echo Form::token();

echo "<ul class=\"form\">\n";

echo '<li' . ( App::has_errors($gameform,'player') ? ' class="err"' : '' ) . '>' .
	Form::label('player', 'Player', array('class'=>'required')) .
	Form::select('player', array(0=>''), $gameform->player, array('class'=>'qselect')) .
	App::errors_for($gameform,'player') .
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
		Form::number('player_score['.$i.']', $game->player_score) .
		App::errors_for($game,'player_score') .
		"</td>\n";

	echo '<td' . ( App::has_errors($game,'opponent') ? ' class="err"' : '' ) . '>' .
		Form::select('opponent['.$i.']', array(0=>''), $game->opponent, array('class'=>'qselect')) .
		App::errors_for($game,'opponent') .
		"</td>\n";

	echo '<td' . ( App::has_errors($game,'opponent_score') ? ' class="err"' : '' ) . '>' .
		Form::number('opponent_score['.$i.']', $game->opponent_score) .
		App::errors_for($game,'opponent_score') .
		"</td>\n";

	echo "</tr>\n";

}

?>
		</tbody>
	</table>

	</fieldset>
</li>

<?php


echo '<li' . ( App::has_errors($gameform,'bingos') ? ' class="err"' : '' ) . '>' .
	Form::label('bingos', 'Bingos') .
	App::help_for($gameform,'bingos') .
	Form::textarea('bingos', $gameform->bingos, array('placeholder'=>App::rwords()) ) .
	App::errors_for($gameform,'bingos') .
	"</li>\n";

echo "<li>" .
	Form::submit('Save Games') .
	HTML::link_to_route('games', 'Back to Games List', null, array('class'=>'btn')) .
	"</li>\n";

echo "</ul>\n";


echo Form::close();


?>