<h1><?php echo $title; ?></h1>
<script>
$(document).ready( function() {
	$('#naspa_lookup').click( function(e) {
		e.preventDefault();
		alert('Feature coming soon');
/*
		$.ajax({
				url: 'http://scrabbleplayers.org/cgi-bin/members.pl',
				method: 'post',
				data: {
					'naspa_profile_search_key_given': $('#firstname').val(),
					'naspa_profile_search_key_surname': $('#lastname').val()
				},
				success: function(data) {
					alert(data);
				}
			});
*/
	});
});
</script>

<?php

echo Form::open();
echo Form::token();

echo "<ul class=\"form\">\n";

echo '<li' . ( App::has_errors($player,'firstname') ? ' class="err"' : '' ) . '>' .
	Form::label('firstname', 'First Name', array('class'=>'required')) .
	Form::text('firstname', $player->firstname) .
	App::errors_for($player,'firstname') .
	"</li>\n";

echo '<li' . ( App::has_errors($player,'lastname') ? ' class="err"' : '' ) . '>' .
	Form::label('lastname', 'Last Name', array('class'=>'required')) .
	Form::text('lastname', $player->lastname ) .
	App::errors_for($player,'lastname') .
	"</li>\n";

echo '<li' . ( App::has_errors($player,'email') ? ' class="err"' : '' ) . '>' .
	Form::label('email', 'Email Address') .
	Form::email('email', $player->email) .
	App::errors_for($player,'email') .
	"</li>\n";


echo '<li' . ( App::has_errors($player,'naspa_id') ? ' class="err"' : '' ) . '>' .
	Form::label('naspa_id', 'NASPA Member Number') .
	Form::text('naspa_id', $player->naspa_id) .
	App::errors_for($player,'naspa_id');
echo '<button id="naspa_lookup">Lookup NASPA info</button>';
echo "</li>\n";

echo '<li' . ( App::has_errors($player,'naspa_rating') ? ' class="err"' : '' ) . '>' .
	Form::label('naspa_rating', 'NASPA Rating') .
	Form::number('naspa_rating', $player->naspa_rating ? $player->naspa_rating : '' ) .
	App::errors_for($player,'naspa_rating') .
	"</li>\n";


echo "<li>" .
	Form::submit($submit_text) .
	HTML::link_to_route('players', 'Back to Players List', null, array('class'=>'btn')) .
	"</li>\n";

echo "</ul>\n";

echo Form::close();


?>
