<?php
foreach( array('success','notice','error') as $flash_type ) {
	if (Session::has($flash_type)) {

		echo '<div class="flash ' . $flash_type . '">' .
			Session::get($flash_type) .
			"</div>\n";

	}
} ?>