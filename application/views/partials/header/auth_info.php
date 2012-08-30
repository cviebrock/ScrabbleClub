<li class="dropdown">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo Auth::user()->fullname; ?> <b class="caret"></b></a>
	<ul class="dropdown-menu">
		<li><?php echo HTML::link_to_action('players@details', 'My Stats', array(Auth::user()->id) ); ?></li>
		<li><?php echo HTML::link_to_action('Logout', 'Logout'); ?></li>
	</ul>
</li>