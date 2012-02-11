<?php

Router::register('GET /players', array( 'name'=>'players', function()
{

	$view = View::make('default')
		->with('title', 'Players')
		->nest('content', 'players.index', array(
			'players' => Player::all(),
		));

	Asset::add('tablesorter', 'js/jquery.tablesorter.min.js', 'jquery');
	Asset::add('tablesorter-pager', 'js/jquery.tablesorter.pager.js', 'jquery');
	Asset::add('tablesorter', 'css/tablesorter.css');
	Asset::add('tablesorter-pager', 'css/tablesorter-pager.css');

	return $view;

}));
