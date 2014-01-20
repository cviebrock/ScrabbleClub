<?php

class Players_Controller extends Base_Controller {


	public function get_index()
	{

		$year = Input::get('year', Config::get('scrabble.current_year') );

		$lastgame = Game::order_by('date', 'desc')
			->where(DB::raw('YEAR(date)'), '=', $year)
			->take(1)
			->first();

		if ($lastgame) {

			$players = DB::query('SELECT
				p.id,
				CONCAT (p.firstname," ",p.lastname) AS fullname,
				COUNT(g.id) AS games_played,
				SUM(IF(g.spread>0,1,0)) AS wins,
				SUM(IF(g.spread=0,1,0)) AS ties,
				SUM(IF(g.spread<0,1,0)) AS losses,
				ROUND(AVG(g.player_score)) AS average_score,
				ROUND(AVG(g.opponent_score)) AS average_opponent_score,
				ROUND(AVG(g.spread)) AS average_spread,
				MAX(g.player_score) AS best_score,
				MAX(g.spread) AS best_spread
				FROM players p
					LEFT JOIN games g ON (p.id=g.player_id)
				WHERE YEAR(g.date)=' . $year . '
				GROUP BY p.id
				HAVING games_played>0
				ORDER BY games_played DESC
			');

			$total_games = Game::where(DB::raw('YEAR(date)'), '=', $year)
				->count() / 2;
			$min_games_played = Input::get('min_games', floor( $total_games / count($players) ) );

			$temp = DB::query('SELECT
				player_id,
				COUNT(word) AS num_played,
				SUM(1-valid)/COUNT(word) AS phoniness
				FROM bingos
				WHERE YEAR(date)=' . $year . '
				GROUP BY player_id
			');

			$bingos = array();
			foreach($temp as $data) {
				$bingos[ $data->player_id ] = $data;
			}

			$temp = DB::query('SELECT
				r1.player_id, r1.ending_rating
				FROM ratings r1
					LEFT JOIN ratings r2 ON (
				 		r1.player_id = r2.player_id AND r1.date < r2.date
				 	)
				WHERE r2.id IS NULL
				ORDER BY r1.player_id
			');
			$ratings = array();
			foreach($temp as $data) {
				$ratings[ $data->player_id ] = $data;
			}

		} else {
			// no games

			$min_games_played = 0;
			$players = array();
			$bingos = array();
			$ratings = array();

		}

		Asset::add('tablesorter', 'js/jquery.tablesorter.min.js', 'jquery');
		Asset::add('tablesorter-pager', 'js/jquery.tablesorter.pager.js', 'jquery');

		$this->layout->with('title', 'Players')
			->nest('content', 'players.index', array(
				'min_games_played' => $min_games_played,
				'lastgame'         => $lastgame,
				'year'             => $year,
				'players'          => $players,
				'bingos'           => $bingos,
				'ratings'          => $ratings,
			));

	}

	public function get_slug($slug)
	{
		$player = Player::where(DB::raw('CONCAT(firstname,"-",lastname)'),'=',$slug)
			->first();

		print_r($player);
		die;
	}



	public function get_details($id)
	{

		$player = Player::find($id);

		$year = Input::get('year', null);

		$temp = DB::query(
			'SELECT DISTINCT(YEAR(date)) AS year
			FROM games
			ORDER by year ASC');

		$all_years = array(
			'' => 'All-time'
		);
		foreach($temp as $v) {
			$all_years[$v->year] = $v->year;
		}


		$q = 'SELECT
			COUNT(g.id) AS games_played,
			COUNT(DISTINCT g.date) AS dates_played,
			SUM(IF(g.spread>0,1,0)) AS wins,
			SUM(IF(g.spread=0,1,0)) AS ties,
			SUM(IF(g.spread<0,1,0)) AS losses,
			ROUND(AVG(g.player_score)) AS average_score,
			ROUND(AVG(g.opponent_score)) AS average_opponent_score,
			ROUND(AVG(g.spread)) AS average_spread
			FROM games g
			WHERE g.player_id = ?';

		if ($year) {
			$q .= ' AND YEAR(g.date) = ?';
			$temp = DB::query( $q, array($id, $year) );
		} else {
			$temp = DB::query( $q, array($id) );
		}

		$club_details = $temp[0];

		// this is ugly, but kinda the only way

		if ($year) {

			$bingos = array(
				'count'  => Bingo::where('player_id','=',$id)->where(DB::raw('YEAR(date)'),'=',$year)->count(),
				'good'   => Bingo::where('player_id','=',$id)->where('valid','=',1)->where(DB::raw('YEAR(date)'),'=',$year)->count(),
				'phoney' => Bingo::where('player_id','=',$id)->where('valid','=',0)->where(DB::raw('YEAR(date)'),'=',$year)->count(),
				'best'   => Bingo::where('player_id','=',$id)->where('score','>',0)->where(DB::raw('YEAR(date)'),'=',$year)->order_by('score','desc')->first(),
				'worst'  => Bingo::where('player_id','=',$id)->where('score','>',0)->where(DB::raw('YEAR(date)'),'=',$year)->order_by('score','asc')->first(),
				'rarest' => Bingo::join('validwords', 'bingos.word', '=', 'validwords.word')
											->where('player_id','=',$id)
											->where(DB::raw('YEAR(date)'),'=',$year)
											->order_by('playability','asc')
											->order_by('score','desc')
											->first(),
			);


			$best_spread = Game::where('player_id','=',$id)
				->where(DB::raw('YEAR(date)'),'=',$year)
				->order_by('spread','desc')
				->order_by('date','desc')
				->first();

			$worst_spread = Game::where('player_id','=',$id)
				->where(DB::raw('YEAR(date)'),'=',$year)
				->order_by('spread','asc')
				->order_by('date','desc')
				->first();

			$high_score = Game::where('player_id','=',$id)
				->where(DB::raw('YEAR(date)'),'=',$year)
				->order_by('player_score','desc')
				->order_by('date','desc')
				->first();

			$high_loss = Game::where('player_id','=',$id)
				->where(DB::raw('YEAR(date)'),'=',$year)
				->where('spread','<',0)
				->order_by('player_score','desc')
				->order_by('date','desc')
				->first();

			$low_score = Game::where('player_id','=',$id)
				->where(DB::raw('YEAR(date)'),'=',$year)
				->order_by('player_score','asc')
				->order_by('date','desc')
				->first();

			$best_combined = Game::where('player_id','=',$id)
				->where(DB::raw('YEAR(date)'),'=',$year)
				->order_by(DB::raw('`player_score`+`opponent_score`'),'desc')
				->first();


		} else {

			$bingos = array(
				'count'  => Bingo::where('player_id','=',$id)->count(),
				'good'   => Bingo::where('player_id','=',$id)->where('valid','=',1)->count(),
				'phoney' => Bingo::where('player_id','=',$id)->where('valid','=',0)->count(),
				'best'   => Bingo::where('player_id','=',$id)->where('score','>',0)->order_by('score','desc')->first(),
				'worst'  => Bingo::where('player_id','=',$id)->where('score','>',0)->order_by('score','asc')->first(),
				'rarest' => Bingo::join('validwords', 'bingos.word', '=', 'validwords.word')
											->where('player_id','=',$id)
											->order_by('playability','asc')
											->order_by('score','desc')
											->first(),
			);


			$best_spread = Game::where('player_id','=',$id)
				->order_by('spread','desc')
				->order_by('date','desc')
				->first();

			$worst_spread = Game::where('player_id','=',$id)
				->order_by('spread','asc')
				->order_by('date','desc')
				->first();

			$high_score = Game::where('player_id','=',$id)
				->order_by('player_score','desc')
				->order_by('date','desc')
				->first();

			$high_loss = Game::where('player_id','=',$id)
				->where('spread','<',0)
				->order_by('player_score','desc')
				->order_by('date','desc')
				->first();

			$low_score = Game::where('player_id','=',$id)
				->order_by('player_score','asc')
				->order_by('date','desc')
				->first();

			$best_combined = Game::where('player_id','=',$id)
				->order_by(DB::raw('`player_score`+`opponent_score`'),'desc')
				->first();

		}

		Asset::add('tablesorter', 'js/jquery.tablesorter.min.js', 'jquery');
		Asset::add('tablesorter-pager', 'js/jquery.tablesorter.pager.js', 'jquery');
		Asset::add('string_score', 'js/string_score.min.js', 'jquery');
		Asset::add('quickselect', 'js/jquery.quickselect.js', 'jquery');

		Asset::add('highcharts', 'js/highcharts/highcharts.js', 'jquery');


		$this->layout->with('title', $player->fullname)
			->nest('content', 'players.details', array(
				'player'        => $player,
				'ratings'       => $year ? $player->ratings()->where(DB::raw('YEAR(date)'),'=',$year)->get() : $player->ratings,
				'all_players'   => all_players($id),
				'club_details'  => $club_details,
				'best_spread'   => $best_spread,
				'worst_spread'  => $worst_spread,
				'high_score'    => $high_score,
				'high_loss'     => $high_loss,
				'low_score'     => $low_score,
				'best_combined' => $best_combined,
				'bingos'        => $bingos,
				'year'          => $year,
				'all_years'			=> $all_years,
			));


	}


	public function get_bingos($id)
	{

		$player = Player::find($id);

		$year = Input::get('year', null);

		$temp = Bingo::left_join('validwords', 'bingos.word', '=', 'validwords.word')
			->where('player_id','=',$id);

		if ($year) {
			$temp->where(DB::raw('YEAR(date)'), '=', $year);
		}

		$bingos = $temp->get(array('bingos.*','validwords.playability'));


		Asset::add('tablesorter', 'js/jquery.tablesorter.min.js', 'jquery');
		Asset::add('tablesorter-pager', 'js/jquery.tablesorter.pager.js', 'jquery');

		$this->layout->with('title', $player->fullname.TITLE_DELIM.'Bingos')
			->nest('content', 'players.bingos', array(
				'player' => $player,
				'year'   => $year,
				'bingos' => $bingos,
			));


	}


	public function get_games($id)
	{

		$player = Player::find($id);

		$year = Input::get('year', null);

		Asset::add('tablesorter', 'js/jquery.tablesorter.min.js', 'jquery');
		Asset::add('tablesorter-pager', 'js/jquery.tablesorter.pager.js', 'jquery');

		$this->layout->with('title', $player->fullname.TITLE_DELIM.'Games')
			->nest('content', 'players.games', array(
				'player' => $player,
				'year'   => $year,
				'games'  => $year ? $player->games()->where(DB::raw('YEAR(date)'),'=',$year)->get() : $player->games,
			));

	}


	public function get_ratings($id)
	{

		$player = Player::find($id);

		$year = Input::get('year', null);

		Asset::add('tablesorter', 'js/jquery.tablesorter.min.js', 'jquery');
		Asset::add('tablesorter-pager', 'js/jquery.tablesorter.pager.js', 'jquery');

		$this->layout->with('title', $player->fullname.TITLE_DELIM.'Ratings')
			->nest('content', 'players.ratings', array(
				'player'  => $player,
				'year'    => $year,
				'ratings' => $year ? $player->ratings()->where(DB::raw('YEAR(date)'),'=',$year)->get() : $player->ratings,
			));

	}

}
