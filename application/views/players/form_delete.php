<h1><?php echo $title; ?></h1>

<p>
	Do you really want to delete <?php echo $player->fullname(); ?>?
</p>

<?php

echo Form::open();
echo Form::token();

echo "<ul class=\"form\">\n";

echo '<li' . ( App::has_errors($player,'confirm') ? ' class="err"' : '' ) . '>' .
	Form::checkbox('confirm', 'yes') .
	Form::label('confirm', 'Confirm', array('class'=>'required inline')) .
	App::errors_for($player,'confirm') .
	"</li>\n";

echo "<li>" .
	Form::submit($submit_text) .
	HTML::link_to_route('players', 'Back to players list', null, array('class'=>'btn')) .
	"</li>\n";

echo "</ul>\n";

echo Form::close();


?>
