<header class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container-fluid">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<?php echo HTML::link( URL::base(), Config::get('application.clubname'), array('class'=>'brand')); ?>
			<div class="nav-collapse">
				<ul class="nav">

					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">About <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><?php echo HTML::link_to_action('about', 'About the Club'); ?></li>
							<li><?php echo HTML::link_to_action('about@resources', 'Resources'); ?></li>
							<li><?php echo HTML::link_to_action('about@links', 'Links'); ?></li>
						</ul>
					</li>

					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Stats <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><?php echo HTML::link_to_action('players', 'Player'); ?></li>
							<li><?php echo HTML::link_to_action('club', 'Club'); ?></li>
							<li><?php echo HTML::link_to_action('bingo', 'Bingo'); ?></li>
						</ul>
					</li>

					<?php if (Auth::check()): ?>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Admin <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><?php echo HTML::link_to_action('admin.players', 'Players'); ?></li>
							<li><?php echo HTML::link_to_action('admin.games', 'Games'); ?></li>
							<li><?php echo HTML::link_to_action('admin.news', 'News'); ?></li>
							<li><?php echo HTML::link_to_action('admin.housekeeping', 'Housekeeping'); ?></li>
						</ul>
					</li>
					<?php endif; ?>

				</ul>

				<ul class="nav pull-right">
					<?php echo $authbox; ?>
				</ul>

			</div><!--/.nav-collapse -->
		</div>
	</div>
</header>
