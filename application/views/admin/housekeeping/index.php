<div class="page-header">
	<h1>Homepage</h1>
</div>

<h2>Site Content</h2>
<ul>
	<li><?php echo HTML::link_to_action('admin.housekeeping@homepage', 'Home Page'); ?></li>
</ul>

<h2>Backup/Export</h2>

<ul>
	<li><?php echo HTML::link_to_action('admin.housekeeping@backup_sql', 'Backup Entire Database (SQL)'); ?></li>
	<li><?php echo HTML::link_to_action('admin.housekeeping@export_bingos', 'Export Full Bingo Listing (CSV)'); ?><br>
	(<?php foreach ($years as $k=>$year): ?>
		<?php echo $k ? ', ' : ''; ?>
		<?php echo HTML::link_to_action('admin.housekeeping@export_bingos', $year, array($year)); ?>
	<?php endforeach; ?>)
	</li>
</ul>
