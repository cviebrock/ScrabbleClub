<header class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container-fluid">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <?php echo HTML::link_to_route('home', Config::get('application.clubname'), null, array('class'=>'brand')); ?>
      <div class="nav-collapse">
        <ul class="nav">
					<li><?php echo HTML::link_to_route('players', 'Players'); ?></li>
					<li><?php echo HTML::link_to_route('admin_players', 'Admin Players'); ?></li>
					<li><?php echo HTML::link_to_route('admin_games', 'Admin Games'); ?></li>
        </ul>
        <p class="navbar-text pull-right">Logged in as <a href="#">username</a></p>
      </div><!--/.nav-collapse -->
    </div>
  </div>
</header>
