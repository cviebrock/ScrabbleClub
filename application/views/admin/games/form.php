<div class="page-header">
	<h1>New Games Entry</h1>
</div>

<?php if ($gameform->has_errors()): ?>
<div class="alert alert-error">
	<strong>Oops!</strong> Please correct the errors below.
</div>
<?php endif; ?>


<?php

$tabindex = 1;

echo Form::open(null,'post', array('class'=>Form::TYPE_HORIZONTAL));
echo Form::token();

echo Form::field('select', 'player_id', 'Player',
	array($all_players, $gameform->player_id, array('class'=>'span4 required qselect', 'tabindex'=>$tabindex++)),
	array('error' => $gameform->error('player_id'))
);

echo Form::field('date', 'date', 'Date',
	array($gameform->date, array('class'=>'span2 required')),
	array('error' => $gameform->error('date'))
);

?>

<div class="control-group">
	<label class="control-label">Games</label>
	<div class="controls">

	<table class="table table-condensed games table-auto">
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

	echo '<td' . ($game->error('player_score') ? ' class="error"' : '') . '>';
	echo Form::number('player_score['.$i.']', $game->player_score, array('class'=>'span1', 'tabindex'=>$tabindex++));
	echo $game->error('player_score', 'div');
	echo "</td>\n";

	echo '<td' . ($game->error('opponent_id') ? ' class="error"' : '') . '>';
	echo Form::select('opponent_id['.$i.']', $all_players, $game->opponent_id, array('class'=>'span3 qselect','tabindex'=>$tabindex++));
	echo $game->error('opponent_id', 'div');
	echo "</td>\n";

	echo '<td' . ($game->error('opponent_score') ? ' class="error"' : '') . '>';
	echo Form::number('opponent_score['.$i.']', $game->opponent_score, array('class'=>'span1', 'tabindex'=>$tabindex++));
	echo $game->error('opponent_score', 'div');
	echo "</td>\n";

	echo "</tr>\n";

}

?>
		</tbody>
	</table>


	</div>
</div>

<?php


echo Form::field('textarea', 'bingo_list', 'Bingos',
	array($gameform->bingo_list, array(
		'class'          => 'span6',
		'placeholder'    => rwords(),
		'tabindex'       => $tabindex++,
		'autocorrect'    => 'off',
		'autocomplete'   => 'off',
		'autocapitalize' => 'off',
	)),
	array('error' => (
		$gameform->error('bingo_list') ? join('<br>',$gameform->error('bingo_list')) : ''
	))
);


echo Form::actions(array(
	Form::submit('Save Games', array(
		'class'    => 'btn-primary',
		'tabindex' => $tabindex++
	)),
	action_link_to_route('admin.games', 'Back to Games List', array(), 'arrow-left')
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
		value: '<?php echo $gameform->date; ?>'
	});


});
</script>