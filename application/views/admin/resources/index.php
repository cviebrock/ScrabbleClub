<div class="page-header">
	<?php echo action_link_to_route('admin.resources@new_group', '', array(), 'plus|white', array('class'=>'btn btn-primary pull-right')); ?>
	<h1>Resources and Links</h1>
</div>


<table class="table table-striped table-bordered sortable">
	<thead>
		<tr>
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
		echo '<td>' . HTML::link_to_action('admin.resources@index', $group->title, array($group->id) ) .
			($group->private ? ' <i class="icon-lock"></i>' : '') .
			'</td>';
		echo '<td>' . $group->resources()->count() . '</td>';
		echo '<td><div class="sc_actions btn-group">' .
			action_link_to_route('admin.resources@sort_group', '', array('up',$group->id),   'small|arrow-up', ($group->sort_order == $first_group ? array('disabled') : array() )  ) .
			action_link_to_route('admin.resources@sort_group', '', array('down',$group->id), 'small|arrow-down', ($group->sort_order == $last_group  ? array('disabled') : array() )  ) .
			'</div></td>';
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




<?php if ($show_group): ?>

<div class="page-header">
	<?php echo action_link_to_route('admin.resources@new', '', array($show_group->id), 'plus|white', array('class'=>'btn btn-primary pull-right')); ?>
	<h2 class="push-down"><?php echo $show_group->title; ?></h2>
</div>

<table class="table table-striped table-bordered sortable">
	<thead>
		<tr>
			<th class="span6">Name</th>
			<th class="span1">Sort</th>
			<th class="span2">Actions</th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($show_group->resources as $resource): ?>
		<tr>
			<td>
				<?php echo $resource->title; ?>
				<div class="help-text"><?php echo $resource->downloadlink; ?></div>
			</td>
			<td><div class="sc_actions btn-group">
				<?php echo action_link_to_route('admin.resources@sort', '', array('up',$resource->id),   'small|arrow-up', ($resource->sort_order == $first ? array('disabled') : array() )  ) .
					action_link_to_route('admin.resources@sort', '', array('down',$resource->id), 'small|arrow-down', ($resource->sort_order == $last  ? array('disabled') : array() )  ); ?>
			</div></td>
			<td><ul class="sc_actions">
				<li><?php echo action_link_to_route('admin.resources@edit', 'Edit', array($resource->id), 'small|pencil' ); ?></li>
				<li><?php echo action_link_to_route('admin.resources@delete', 'Delete', array($resource->id), 'small|remove' ); ?></li>
			</ul></td>
		</tr>
<?php endforeach; ?>
	</tbody>
</table>

<?php echo action_link_to_route('admin.resources@new', 'Add new resource', array($show_group->id), 'plus|white', array('class'=>'btn btn-primary')); ?>


<?php endif; ?>



<script type="text/javascript">
$(function() {
	$('a[disabled]').on('click', function(e){
		e.preventDefault;
		return false;
	});
});</script>
