<?php

class Admin_Games_Controller extends Base_Controller {


	public function __construct()
	{
		$this->filter('before', 'csrf')->on('post');
		parent::__construct();

	}


	public function get_index()
	{

		$games = DB::query('SELECT
				date,
				FLOOR(SUM(IF(matching_game=0,0,1))/2) AS complete_games,
				COUNT(DISTINCT player_id) AS players,
				SUM(IF(matching_game=0,1,0)) AS unmatched_games
				FROM games
				GROUP BY date
				ORDER BY date DESC
		');

		Asset::add('tablesorter', 'js/jquery.tablesorter.min.js', 'jquery');
		// Asset::add('tablesorter-pager', 'js/jquery.tablesorter.pager.js', 'jquery');
		// Asset::add('tablesorter-pager', 'css/tablesorter-pager.css');

		$this->layout->with('title', 'Games')
			->nest('content', 'admin.games.index', array(
				'games' => $games,
			));

	}


	public function get_bydate($date)
	{

		$temp = DB::query('SELECT
			id, matching_game,
			IF( player_id<opponent_id,
				CONCAT(player_id,"-",opponent_id),
				CONCAT(opponent_id,"-",player_id)
			) AS pairing
			FROM games
			WHERE date = ?
			ORDER BY SIGN(matching_game) DESC, pairing DESC, spread DESC
		',
			array($date)
		);

		$matched_games = $unmatched_games = $seen = array();

		foreach ($temp as $k=>$game) {

			if ($game->matching_game) {
				if (!in_array($game->matching_game, $seen)) {
					$matched_games[] = Game::find($game->id);
					$seen[] = $game->id;
				}
			} else {
				$unmatched_games[] = Game::find($game->id);
			}
		}

		$fdate = App::format_date($date);

		Asset::add('tablesorter', 'js/jquery.tablesorter.min.js', 'jquery');
		// Asset::add('tablesorter-pager', 'js/jquery.tablesorter.pager.js', 'jquery');
		// Asset::add('tablesorter-pager', 'css/tablesorter-pager.css');

		$this->layout->with('title', 'Games for '.$fdate)
			->nest('content', 'admin.games.list', array(
				'fdate'           => $fdate,
				'date'            => $date,
				'matched_games'   => $matched_games,
				'unmatched_games' => $unmatched_games,
			));

	}


	public function get_new($date=null)
	{

		Log::write('info','request');


		$gameform = new Gameform;

		if ($date) {
			$gameform->date = $date;
		} else if (Session::has('last_date')) {
			$gameform->date = Session::get('last_date');
		} else {
			$t = new DateTime('tomorrow');
			$gameform->date = $t->modify('last Thursday')->format('Y-m-d');			/* last Thurs, unless today is Thurs, in which case today */
		}

		Asset::add('dateinput', 'js/jquery.tools.min.js', 'jquery');
		Asset::add('string_score', 'js/string_score.min.js', 'jquery');
		Asset::add('quickselect', 'js/jquery.quickselect.js', 'jquery');
		// Asset::add('quickselect', 'css/quickselect.css');
		// Asset::add('dateinput', 'css/dateinput.css');

		$this->layout->with('title', 'New Games')
			->nest('content', 'admin.games.form', array(
				'gameform' => $gameform,
				'all_players'  => App::all_players(),
			));

	}


	public function post_new()
	{

		$gameform = new Gameform;

		$gameform->player_id = Input::get('player_id');
		try {
			$temp = new DateTime( Input::get('date') );
			$gameform->date = $temp->format('Y-m-d');
		} catch ( Exception $e )  {
			$gameform->date = null;
		}
		$gameform->bingo_list = Input::get('bingo_list');
		$gameform->opponent_id = Input::get('opponent_id');
		$gameform->player_score = Input::get('player_score');
		$gameform->opponent_score = Input::get('opponent_score');

		if ($gameform->is_valid()) {

			Session::put('last_date', $gameform->date);

			$gameform->save();
			return Redirect::to_action('admin.games@new')
				->with('success', 'Games for "' . $gameform->player()->fullname() . '" on ' .
					App::format_date($gameform->date) . ' added.');

		}

		Asset::container('head')->add('dateinput', 'js/jquery.tools.min.js', 'jquery');
		Asset::container('head')->add('string_score', 'js/string_score.min.js', 'jquery');
		Asset::container('head')->add('quickselect', 'js/jquery.quickselect.js', 'jquery');
		// Asset::add('quickselect', 'css/quickselect.css');
		// Asset::add('dateinput', 'css/dateinput.css');

		$this->layout->with('title', 'New Games')
			->nest('content', 'admin.games.form', array(
				'gameform' => $gameform,
				'all_players'  => App::all_players(),
			));



	}


	public function get_edit($id)
	{

		$game = Game::find($id);

		Asset::container('head')->add('dateinput', 'js/jquery.tools.min.js', 'jquery');
		Asset::container('head')->add('string_score', 'js/string_score.min.js', 'jquery');
		Asset::container('head')->add('quickselect', 'js/jquery.quickselect.js', 'jquery');
		Asset::add('quickselect', 'css/quickselect.css');
		Asset::add('dateinput', 'css/dateinput.css');

		$this->layout->with('title', 'Edit Game')
			->nest('content', 'admin.games.edit', array(
				'game' => $game,
				'all_players'  => App::all_players(),
			));

	}


	public function post_edit($id)
	{

		$game = Game::find($id);

		try {
			$temp = new DateTime( Input::get('date') );
			$game->date = $temp->format('Y-m-d');
		} catch ( Exception $e )  {
			$game->date = null;
		}
		$game->player_id = Input::get('player_id');
		$game->player_score = Input::get('player_score');
		$game->opponent_id = Input::get('opponent_id');
		$game->opponent_score = Input::get('opponent_score');

		if (!$game->is_valid()) {

			Asset::container('head')->add('dateinput', 'js/jquery.tools.min.js', 'jquery');
			Asset::container('head')->add('string_score', 'js/string_score.min.js', 'jquery');
			Asset::container('head')->add('quickselect', 'js/jquery.quickselect.js', 'jquery');

			$this->layout->with('title', 'Edit Game')
				->nest('content', 'admin.games.edit', array(
					'game' => $game,
					'all_players'  => App::all_players(),
				));

			return;

		}

		Session::put('last_date', $game->date);

		$game->save();
		if ($game->match_game()) {
			return Redirect::to_action('admin.games@bydate', array($game->date) )
				->with('success', 'Game edited and matched.');
		} else {
			return Redirect::to_action('admin.games@edit', array($game->id) )
				->with('success', 'Game edited.');
		}



	}


	public function get_delete($id)
	{

		$game = Game::find($id);

		Asset::add('tablesorter', 'js/jquery.tablesorter.min.js', 'jquery');
		Asset::add('tablesorter', 'css/tablesorter.css');

		$this->layout->with('title', 'Delete Game')
			->nest('content', 'admin.games.delete', array(
				'game'				=> $game
			));

	}


	public function post_delete($id)
	{

		$game = Game::find($id);
		$date = $game->date;

		if ( !Input::get('confirm') ) {
			return Redirect::to_action('admin.games@delete', array($id))
				->with('warning', 'Game not deleted &mdash; confirmation not checked.');
		}


		$game->delete();
		return Redirect::to_action('admin.games@bydate', array($date))
			->with('success', 'Game deleted.');

	}



	public function get_create_match($id)
	{

		$game = Game::find($id);
		if ($game->matching_game) {
			return Redirect::to_action('admin.games')
				->with('error','That game is already matched.');
		}

		Asset::add('tablesorter', 'css/tablesorter.css');

		$this->layout->with('title', 'Match Game')
			->nest('content', 'admin.games.match', array(
				'game' => $game
			));

	}


	public function post_create_match($id)
	{

		$game = Game::find($id);

		if ( !Input::get('confirm') ) {
			return Redirect::to_action('admin.games@create_match', array($id))
				->with('warning', 'Game not created &mdash; confirmation not checked.');
		}


		$game->swap_players();
		$game->exists = false;
		$game->id = null;
		$game->save();
		$game->match_game();

		return Redirect::to_action('admin.games@bydate', array($game->date))
			->with('success', 'Matched game created.');

	}

}
