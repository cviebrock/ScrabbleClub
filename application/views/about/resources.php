<div class="page-header">
	<h1>Resources &amp; Links</h1>
</div>

<?php foreach($resourcegroups as $resourcegroup): ?>

<h2><?php echo $resourcegroup->title; ?></h2>

<ul class="resources">
<?php foreach($resourcegroup->resources as $resource): ?>
	<li>
		<?php echo HTML::link( $resource->url, $resource->title ); ?>
		<?php if ( $resource->description ): ?>
		<div class="resource-desc"><?php echo $resource->description; ?></div>
		<?php endif; ?>
	</li>
<?php endforeach; ?>
</ul>

<?php endforeach; ?>


<script type="text/javascript">
$(function() {
	$('a', '.container').attr('target', '_blank');
});
</script>
