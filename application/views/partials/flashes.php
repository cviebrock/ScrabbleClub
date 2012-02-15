<?php
foreach( array('success','error','info','warning') as $flash_type ) {
	if (Session::has($flash_type)) {

		echo '<div class="alert alert-' . $flash_type . '">' .
			Session::get($flash_type) .
			"</div>\n";

	}
} ?>