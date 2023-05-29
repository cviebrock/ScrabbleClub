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

$naspa = array(
	'naspa_profile_search_key_given'   => $player->firstname,
	'naspa_profile_search_key_surname' => $player->lastname,
	'naspa_profile_search'             => 'Search'
);



echo Form::field('text', 'naspa_id', 'NASPA ID',
	array($player->naspa_id, array('class'=>'span2')),
	array(
		'post_field' => ' ' . HTML::link(
			'https://scrabbleplayers.org/cgi-bin/members.pl?' . http_build_query($naspa),
			'Lookup',
			array('class'=>'btn btn-small', 'id'=>'naspa_lookup', 'target'=>'blank')
		),
		'error' => $player->error('naspa_id')
	)
);

echo Form::field('number', 'naspa_rating', 'NASPA Rating',
	array($player->naspa_rating, array('class'=>'span1')),
	array('error' => $player->error('naspa_rating'))
);

?>



<?php if (Auth::check()): ?>

<h2>Password</h2>


<?php if ($mode=='edit' && !empty($player->password) ): ?>

<p>
	This player is currently able to administer the site.  Uncheck this box to disable this.
</p>

<?php echo Form::field('checkbox', 'password[enable]', 'Enable Admin',
	array( 'yes', !empty($player->password) )
); ?>

<p>
	Leave the above box checked and complete these fields to change the user&rsquo;s password,
	or leave both fields blank to leave the password as-is.
</p>

<?php else: ?>

<p>
	Only complete these fields if you want the player to be able to administer the site.
</p>

<?php endif; ?>


<?php

echo Form::field('password', 'password[password]', 'Password',
	array(),
	array('error' => $player->error('password'))
);

echo Form::field('password', 'password[confirmation]', 'Confirm Password',
	array()
);

endif; // Auth::check()


echo Form::actions(array(
	Form::submit($submit_text, array('class' => 'btn-primary')),
	action_link_to_route('admin.players', 'Back to Players List', array(), 'arrow-left')
));

echo Form::close();


?>

<script>
// $(document).ready( function() {
// 	$('#naspa_lookup').click( function(e) {
// 		e.preventDefault();
// 		alert('Feature coming soon');
// 	});
// });
</script>
