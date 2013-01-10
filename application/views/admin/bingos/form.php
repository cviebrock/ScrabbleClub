<div class="page-header">
	<h1><?php echo $title; ?></h1>
</div>

<?php if ($bingo->has_errors()): ?>
<div class="alert alert-error">
	<strong>Oops!</strong> Please correct the errors below.
</div>
<?php endif; ?>

<?php

echo Form::open(null,'post', array('class'=>Form::TYPE_HORIZONTAL));
echo Form::token();

echo Form::field('date', 'date', 'Date',
	array($bingo->date, array('class'=>'span2 required')),
	array('error' => $bingo->error('date'))
);

echo Form::field('select', 'player_id', 'Player',
	array($all_players, $bingo->player_id, array('class'=>'span4 required qselect')),
	array('error' => $bingo->error('player_id'))
);

echo Form::field('text', 'word', 'Word',
	array($bingo->word, array('class'=>'span3 required')),
	array('error' => $bingo->error('word'))
);

echo Form::field('number', 'score', 'Score',
	array($bingo->score, array('class'=>'span1')),
	array('error' => $bingo->error('score'))
);


echo Form::actions(array(
	Form::submit($submit_text, array('class' => 'btn-primary')),
	action_link_to_route('admin.bingos', 'Back to Bingos List', array(), 'arrow-left')
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
		value: '<?php echo $bingo->date; ?>'
	});


});
</script>
