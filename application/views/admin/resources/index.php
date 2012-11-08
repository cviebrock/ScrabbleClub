<div class="page-header">
	<?php echo action_link_to_route('admin.resources@new_group', '', array(), 'plus|white', array('class'=>'btn btn-primary pull-right')); ?>
	<h1>Resources and Links</h1>
</div>


<table class="table table-striped table-bordered sortable">
	<thead>
		<tr>
			<th>#</th>
			<th class="span5">Name</th>
			<th class="span1">Resources</th>
			<th class="span1">Sort</th>
			<th class="span2">Actions</th>
		</tr>
	</thead>
	<tbody>
<?php
foreach ($resourcegroups as $group) {
		echo '<tr>';
		echo '<td>' . $group->sort_order . '</td>';
		echo '<td>' . $group->title . '</td>';
		echo '<td>' . $group->resources()->count() . '</td>';
		echo '<td><ul class="sc_actions">' .
			'<li>' . action_link_to_route('admin.resources@sort_group', '', array('up',$group->id), 'small|arrow-up' ) . '</li>' .
			'<li>' . action_link_to_route('admin.resources@sort_group', '', array('down',$group->id), 'small|arrow-down' ) . '</li>' .
			'</ul></td>';
		echo '<td><ul class="sc_actions">' .
			'<li>' . action_link_to_route('admin.resources@edit_group', 'Edit', array($group->id), 'small|pencil' ) . '</li>' .
			'<li>' . action_link_to_route('admin.resources@delete_group', 'Delete', array($group->id), 'small|remove' ) . '</li>' .
			'</ul></td>';
		echo "<tr>\n";
}
?>
	</tbody>
</table>

<?php echo action_link_to_route('admin.resources@new_group', 'Add new group', array(), 'plus|white', array('class'=>'btn btn-primary')); ?>
