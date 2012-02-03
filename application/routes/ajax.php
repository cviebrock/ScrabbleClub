<?php

Router::register('GET /ajax/players', array( 'name'=>'ajax_players', 'before' => 'ajax', function()
{

	$players = Player::all();
	$data = array();

	foreach($players as $player) {
		$data[] = array( $player->fullname(), $player->id );
	}

	header('application/json');
	return json_encode($data);

}));
