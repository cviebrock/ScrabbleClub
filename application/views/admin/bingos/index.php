<div class="page-header">
	<h1>Bingos</h1>
</div>

<p>
	This is for editing single bingos that may have been entered incorrectly.
</p>

<?php

echo Form::open(null,'post', array('class'=>Form::TYPE_HORIZONTAL));
echo Form::token();

?>

<div class="control-group">
	<label for="search" class="control-label">Search</label>
	<div class="controls">
		<input class="span4" type="text" name="search" value="" id="search" placeholder="Start typing the bingo here">
		<div id="loader"></div>
		<p class="help-text">Maximum of 25 results will be displayed</p>
	</div>
</div>

<div id="results"></div>



<script>
$(document).ready( function() {

	var $results = $('#results'),
		$search = $('#search'),
		$loader = $('#loader').hide();


	$search.on('keyup', function(e) {
		var text = this.value;
		console.log( text.length );

		if ( text.length < 3 ) {
			$results.fadeOut( 250, function() {
				$results.html('');
			});

		} else {

			$loader.show();
			$results.load(
				'<?php echo URL::to_action('ajax@bingo_search'); ?>',
				{ 'q' : text },
				function() {
					$results.fadeIn(250);
					$loader.hide();
/*
						$('#ooo_games').tablesorter({
						sortList: [[0,1]],
						headers: {
							0: { sorter: 'sc_date' },
							1: { sorter: false },
							3: { sorter: false }
						}
					});
*/
				}
			);


		}


	});



});
</script>

