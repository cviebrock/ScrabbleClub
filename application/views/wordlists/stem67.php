<div class="page-header">
	<h1>Six-Letter Stem - <?php echo $stem; ?>+?</h1>
</div>

<table class="table table-striped table-bordered sortable table-auto">
	<thead class="small">
		<tr>
			<th class="span1">?</th>
			<th class="span2">Alphagram</th>
			<th class="span2">Words</th>
		</tr>
	</thead>
	<tbody>
<?php foreach($words as $letter=>$list): ?>
		<tr>
			<td><?php echo $letter; ?></td>
			<td><?php echo alphabetize_word($stem.$letter); ?></td>
			<td>
<?php
	if( count($list) ) {
		foreach($list as $word) {
			echo Str::upper($word) . '<br>';
		}
	} else {
		echo '-';
	}
?>
			</td>
		</tr>
<?php endforeach; ?>
	</tbody>
</table>
