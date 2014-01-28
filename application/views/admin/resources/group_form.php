<div class="page-header">
	<h1><?php echo $title; ?></h1>
</div>

<?php if ($group->has_errors()): ?>
<div class="alert alert-error">
	<strong>Oops!</strong> Please correct the errors below.
</div>
<?php endif; ?>

<?php

echo Form::open(null,'post', array('class'=>Form::TYPE_HORIZONTAL));
echo Form::token();

echo Form::field('text', 'title', 'Group Title',
	array($group->title, array('class'=>'span3 required')),
	array('error' => $group->error('title'))
);

echo Form::field('labelled_checkbox', 'private', 'Private?',
	array('Yes, make this group private', 1, $group->private)
);

echo Form::actions(array(
	Form::submit($submit_text, array('class' => 'btn-primary')),
	action_link_to_route('admin.resources', 'Back to Resource Groups List', array(), 'arrow-left')
));

echo Form::close();



