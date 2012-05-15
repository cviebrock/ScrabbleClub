<?php

class Admin_Players_Controller extends Base_Controller {


	public function __construct()
	{
		$this->filter('before', 'csrf')->on('post');
		parent::__construct();

	}


	public function get_index()
	{

		Asset::add('tablesorter', 'js/jquery.tablesorter.min.js', 'jquery');
		// Asset::add('tablesorter-pager', 'js/jquery.tablesorter.pager.js', 'jquery');
		// Asset::add('tablesorter-pager', 'css/tablesorter-pager.css');

		$this->layout->with('title', 'Players')
			->nest('content', 'admin.players.index', array(
				'players' => Player::all(),
			));

	}


	public function get_new()
	{

		$player = new Player;

		$this->layout->with('title', 'New Player')
			->nest('content', 'admin.players.form', array(
				'player'      => $player,
				'title'       => 'New Player',
				'submit_text' => 'Add Player',
			));
	}


	public function post_new()
	{

		$player = new Player;

		$player->fill(array(
			'firstname'    => Input::get('firstname'),
			'lastname'     => Input::get('lastname'),
			'email'        => Input::get('email'),
			'naspa_id'     => Input::get('naspa_id'),
			'naspa_rating' => Input::get('naspa_rating'),
		));

		if ($player->is_valid()) {
			$player->save();
			return Redirect::to_action('admin.players')
				->with('success', 'Player "' . $player->fullname() . '" added.');
		}


		$this->layout->with('title', 'New Player')
			->nest('content', 'admin.players.form', array(
				'player'      => $player,
				'title'       => 'New Player',
				'submit_text' => 'Add Player',
			));

	}


	public function get_details($id)
	{

		$player = Player::find($id);

		$this->layout->with('title', $player->fullname() )
			->nest('content', 'admin.players.show', array(
				'player'	=> $player,
			));
	}


	public function get_edit($id)
	{

		$player = Player::find($id);

		$this->layout->with('title', 'Edit Player')
			->nest('content', 'admin.players.form', array(
				'player'				=> $player,
				'title'				=> 'Edit Player',
				'submit_text'	=> 'Edit Player',
			));
	}


	public function post_edit($id)
	{

		$player = Player::find($id);

		$player->fill(array(
			'firstname'    => Input::get('firstname'),
			'lastname'     => Input::get('lastname'),
			'email'        => Input::get('email'),
			'naspa_id'     => Input::get('naspa_id'),
			'naspa_rating' => Input::get('naspa_rating'),
		));


		if (!$player->is_valid()) {

			$player->save();
			return Redirect::to_action('admin.players@edit', array($id))
				->with('success', 'Player "' . $player->fullname() . '" edited.');

		}

		$this->layout->with('title', 'Edit Player')
			->nest('content', 'admin.players.form', array(
				'player'        => $player,
				'title'       => 'Edit Player',
				'submit_text' => 'Edit Player',
			));

	}


	public function get_delete($id)
	{

		$player = Player::find($id);

		$this->layout->with('title', 'Delete Player')
			->nest('content', 'admin.players.delete', array(
				'player'				=> $player,
			));
	}


	public function post_delete($id)
	{

		$player = Player::find($id);

		if ( !Input::get('confirm') ) {
			return Redirect::to_action('admin.players@delete', array($id))
				->with('warning', 'Player not deleted &mdash; confirmation not checked.');
		}

		$player->delete();
		return Redirect::to_action('admin.players')
			->with('success', 'Player "' . $player->fullname() . '" deleted.');

	}

}
