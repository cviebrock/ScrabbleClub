<div class="page-header">
	<h1>
		Games for <?php echo $player->fullname; ?>
<?php if ($year = Input::get('year')): ?>
		<span class="subhead">For <?php echo $year; ?></span>
<?php endif; ?>
	</h1>
</div>

<?php
	echo View::make('partials.game_listing')
		->with('games', $games)
		->with('id', 'games')
		->with('mark_winners', true)
		->with('small_head', true)
		->render();
?>

<?php echo action_link_to_route('players@details', 'Back to Player Details', array($player->id), 'arrow-left', array('class'=>'btn')); ?>


<script>
$(document).ready( function() {
	$('#games').tablesorter({
		sortList: [[0,0]],
		headers: {
			0: { sorter: 'sc_date' },
		}
	});
});
</script>
