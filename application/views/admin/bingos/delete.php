<div class="page-header">
	<h1>Delete Bingo</h1>
</div>


<?php

echo Form::open(null,'post', array('class'=>Form::TYPE_HORIZONTAL));
echo Form::token();

echo Form::field('labelled_checkbox', 'confirm', 'Are you sure?',
	array('Yes, delete '.$bingo->word_and_score().' by '.$bingo->player->fullname, 1, false)
);

echo Form::actions(array(
	Form::submit('Delete Bingo', array('class' => 'btn-primary btn-warning')),
	action_link_to_route('admin.bingos', 'Back to Bingos List', array(), 'arrow-left')
));


echo Form::close();


?>
