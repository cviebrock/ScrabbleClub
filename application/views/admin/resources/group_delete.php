<div class="page-header">
	<h1>Delete Resource Group</h1>
</div>

<?php if ( count($group->resources) ): ?>
<div class="alert alert-warning">
	<strong>Note:</strong> Deleting this group will also delete all the resources in the group:
	<ul>
		<?php foreach($group->resources as $resource): ?>
		<li><?php echo $resource->title; ?></li>
		<?php endforeach; ?>
	</ul>
</div>
<?php endif; ?>


<?php

echo Form::open(null,'post', array('class'=>Form::TYPE_HORIZONTAL));
echo Form::token();

echo Form::field('labelled_checkbox', 'confirm', 'Are you sure?',
	array('Yes, delete '. $group->title, 1, false)
);

echo Form::actions(array(
	Form::submit('Delete Resource Group', array('class' => 'btn-primary btn-warning')),
	action_link_to_route('admin.resources@index', 'Back to Resources List', array($group->id), 'arrow-left')
));

echo Form::close();

?>
