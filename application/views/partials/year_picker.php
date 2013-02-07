<div class="no-print" id="year-picker">
<?php

	$r = range( Config::get('scrabble.first_year'), Config::get('scrabble.current_year') );
	$years = array_combine($r,$r);
	if (!isset($hide_all)) {
		$years[0] = 'All';
		ksort($years);
	}

	echo Form::open(null, 'get', array('class'=>Form::TYPE_INLINE));
	echo '<label for="year">Show Stats for</label> ';
	echo Form::select('year', $years, $year) . ' ';
	echo Form::close();
?>
<script type="text/javascript">
$(function() {
	$('#year-picker select').on('change', function(e) {
		var y = parseInt( $(this).val() );
		location.href=location.pathname + ( y ? '?year='+y : '' );
	});
});
</script>
</div>
