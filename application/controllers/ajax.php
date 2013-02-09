<?php

class Ajax_Controller extends Base_Controller {


	// no layout
	public $layout = null;


	public function __construct()
	{
		$this->filter('before', 'ajax');
		parent::__construct();
	}


	public function get_players()
	{
		$players = Player::all();
		$data = array();

		foreach($players as $player) {
			$data[ $player->id ] = $player->fullname;
		}

		header('application/json');
		return json_encode($data);
	}


	public function get_games($player_id, $opponent_id, $year=null)
	{

		// usleep(500000);

		$query = Game::with(array('player','opponent'))
			->where('player_id','=',$player_id)
			->where('opponent_id','=',$opponent_id);

		if ($year) {
			$query = $query->where(DB::raw('YEAR(date)'),'=',$year);
		}

		$games = $query
			->order_by('date','desc')
			->get();

		if ( count($games) ) {

			$summary = game_summary($games);

			echo View::make('partials.game_listing')
				->with('games', $games)
				->with('id', 'ooo_games')
				->with('mark_winners', true)
				->with('small_head', true)
				->with('summary', $summary)
				->render();

		} else {

			echo '<p class="ooo_no_history">No matching games.</p>';

		}

	}


	public function post_markdown()
	{

		$title = Input::get('title');
		$body = Input::get('body');

		if ($title || $body) {

			$text = '##'.$title."\n\n".$body;

			require_once path('bundle').'docs/libraries/markdown.php';
			echo Markdown($text);

		}

	}


	public function post_bingo_search()
	{

		$q = Input::get('q');

		$bingos = Bingo::with(array('player'))
			->where('word', 'LIKE', '%'.$q.'%' )
			->order_by('word', 'ASC')
			->order_by('date', 'DESC')
			->take(25)
			->get();

		echo View::make('partials.bingo_listing')
			->with('bingos', $bingos )
			->with('admin', true)
			->render();

	}

}
