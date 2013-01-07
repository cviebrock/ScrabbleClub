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


	public function get_games($player_id, $opponent_id)
	{

		// usleep(500000);

		$games = Game::with(array('player','opponent'))
			->where('player_id','=',$player_id)
			->where('opponent_id','=',$opponent_id)
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

		$text = '##'.$title."\n\n".$body;

		require path('bundle').'docs/libraries/markdown.php';
		echo Markdown($text);

	}

}
