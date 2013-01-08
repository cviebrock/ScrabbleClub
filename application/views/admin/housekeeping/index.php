<div class="page-header">
	<h1>Housekeeping</h1>
</div>

<h2>Backup/Export</h2>

<ul>
	<li><?php echo HTML::link_to_action('admin.housekeeping@backup_sql', 'Backup Entire Database (SQL)'); ?></li>
	<li><?php echo HTML::link_to_action('admin.housekeeping@export_bingos', 'Export Bingo Listing (CSV)'); ?></li>
</ul>
