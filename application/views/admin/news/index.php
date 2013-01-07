<div class="page-header">
	<?php echo action_link_to_route('admin.news@new', '', array(), 'plus|white', array('class'=>'btn btn-primary pull-right')); ?>
	<h1>News</h1>
</div>

<table class="table table-striped table-bordered sortable">
	<thead>
		<tr>
			<th class="span1">Date</th>
			<th class="span6">Title</th>
			<th class="span2">Actions</th>
		</tr>
	</thead>
	<tbody>
<?php foreach($news as $item): ?>
		<tr>
			<td><?php echo format_date($item->date); ?></td>
			<td>
				<i class="icon-<?php echo $item->active ? 'ok' : ''; ?>"></i>
				<?php echo $item->title; ?>
			</td>
			<td><ul class="sc_actions">
				<li><?php echo action_link_to_route('admin.news@edit', 'Edit', array($item->id), 'small|pencil' ); ?></li>
				<li><?php echo action_link_to_route('admin.news@delete', 'Delete', array($item->id), 'small|remove' ); ?></li>
			</ul></td>
		</tr>
<?php endforeach; ?>
	</tbody>
</table>

<?php echo action_link_to_route('admin.news@new', 'Add new news item', array(), 'plus|white', array('class'=>'btn btn-primary')); ?>
