<div class="page-header">
	<h1><?php echo $title; ?></h1>
</div>

<?php if ($player->has_errors()): ?>
<div class="alert alert-error">
	<strong>Oops!</strong> Please correct the errors below.
</div>
<?php endif; ?>

<?php

echo Form::open(null,'post', array('class'=>Form::TYPE_HORIZONTAL));
echo Form::token();

echo Form::field('text', 'firstname', 'First Name',
	array($player->firstname, array('class'=>'span3 required')),
	array('error' => $player->error('firstname'))
);

echo Form::field('text', 'lastname', 'Last Name',
	array($player->lastname, array('class'=>'span3 required')),
	array('error' => $player->error('lastname'))
);

echo Form::field('email', 'email', 'Email Address',
	array($player->email, array('class'=>'span3')),
	array('error' => $player->error('email'))
);

echo Form::field('text', 'naspa_id', 'NASPA ID',
	array($player->naspa_id, array('class'=>'span2')),
	array(
		'post_field' => ' <a class="btn btn-small" id="naspa_lookup">Lookup</a>',
		'error' => $player->error('naspa_id')
	)
);

echo Form::field('number', 'naspa_rating', 'NASPA Rating',
	array($player->naspa_rating, array('class'=>'span1')),
	array('error' => $player->error('naspa_rating'))
);


echo Form::actions(array(
	Form::submit($submit_text, array('class' => 'btn-primary')),
	App::action_link_to_route('admin_players', 'Back to Players List', null, 'arrow-left')
));

echo Form::close();


?>

<script>
$(document).ready( function() {
	$('#naspa_lookup').click( function(e) {
		e.preventDefault();
		alert('Feature coming soon');
	});
});
</script>
