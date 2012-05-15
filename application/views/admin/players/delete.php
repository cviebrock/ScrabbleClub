<div class="page-header">
	<h1>Delete Player</h1>
</div>


<?php

echo Form::open(null,'post', array('class'=>Form::TYPE_HORIZONTAL));
echo Form::token();

echo Form::field('labelled_checkbox', 'confirm', 'Are you sure?',
	array('Yes, delete '.$player->fullname(), 1, false)
);

echo Form::actions(array(
	Form::submit('Delete Player', array('class' => 'btn-primary btn-warning')),
	App::action_link_to_route('admin.players', 'Back to Players List', array(), 'arrow-left')
));


echo Form::close();


?>
