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
							<li><?php echo HTML::link_to_action('news', 'News Archive'); ?></li>
							<li><?php echo HTML::link_to_action('about@resources', 'Resources & Links'); ?></li>
						</ul>
					</li>

					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Statistics <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><?php echo HTML::link_to_action('club', 'Club Statistics'); ?></li>
							<li><?php echo HTML::link_to_action('players', 'Players'); ?></li>
							<li><?php echo HTML::link_to_action('bingo', 'Bingos'); ?></li>
						</ul>
					</li>

					<?php if (Auth::check()): ?>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Administration <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><?php echo HTML::link_to_action('admin.players', 'Players'); ?></li>
							<li><?php echo HTML::link_to_action('admin.games', 'Games'); ?></li>
							<li><?php echo HTML::link_to_action('admin.news', 'News'); ?></li>
							<li><?php echo HTML::link_to_action('admin.resources', 'Resources'); ?></li>
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
