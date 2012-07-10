<?php

class Players_Controller extends Base_Controller {


	public function get_index()
	{

		$lastgame = Game::order_by('date', 'desc')
			->take(1)
			->first();

		$total_games = Game::count() / 2;
		$min_games_played = floor($total_games * 0.04);


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
			FROM players p LEFT JOIN games g ON (p.id=g.player_id)
			GROUP BY p.id
			HAVING games_played>0
			ORDER BY games_played DESC
		');

		$temp = DB::query('SELECT
			player_id,
			COUNT(word) AS num_played,
			SUM(1-valid)/COUNT(word) AS phoniness
			FROM bingos
			GROUP BY player_id
		');

		$bingos = array();
		foreach($temp as $data) {
			$bingos[ $data->player_id ] = $data;
		}

		Asset::add('tablesorter', 'js/jquery.tablesorter.min.js', 'jquery');
		Asset::add('tablesorter-pager', 'js/jquery.tablesorter.pager.js', 'jquery');

		$this->layout->with('title', 'Players')
			->nest('content', 'players.index', array(
				'min_games_played' => $min_games_played,
				'lastgame'         => $lastgame,
				'players'          => $players,
				'bingos'           => $bingos,
			));

	}


	public function get_details($id)
	{

		$player = Player::find($id);

		$temp = DB::query('SELECT
			COUNT(g.id) AS games_played,
			COUNT(DISTINCT g.date) AS dates_played,
			SUM(IF(g.spread>0,1,0)) AS wins,
			SUM(IF(g.spread=0,1,0)) AS ties,
			SUM(IF(g.spread<0,1,0)) AS losses,
			ROUND(AVG(g.player_score)) AS average_score,
			ROUND(AVG(g.opponent_score)) AS average_opponent_score,
			ROUND(AVG(g.spread)) AS average_spread
			FROM games g
			WHERE g.player_id = ?
		', array($id));

		$club_details = $temp[0];

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

		Asset::add('tablesorter', 'js/jquery.tablesorter.min.js', 'jquery');
		Asset::add('tablesorter-pager', 'js/jquery.tablesorter.pager.js', 'jquery');
		Asset::add('string_score', 'js/string_score.min.js', 'jquery');
		Asset::add('quickselect', 'js/jquery.quickselect.js', 'jquery');

		Asset::add('highcharts', 'js/highcharts/highcharts.js', 'jquery');


		$this->layout->with('title', $player->fullname())
			->nest('content', 'players.details', array(
				'player'       => $player,
				'ratings'      => $player->ratings,
				'all_players'  => App::all_players($id),
				'club_details' => $club_details,
				'best_spread'  => $best_spread,
				'worst_spread' => $worst_spread,
				'high_score'   => $high_score,
				'high_loss'    => $high_loss,
				'low_score'    => $low_score,
				'bingos'       => $bingos,
			));


	}


	public function get_bingos($id)
	{

		$player = Player::find($id);

		$bingos = Bingo::left_join('validwords', 'bingos.word', '=', 'validwords.word')
			->where('player_id','=',$id)
			->get(array('bingos.*','validwords.playability'));


		Asset::add('tablesorter', 'js/jquery.tablesorter.min.js', 'jquery');
		Asset::add('tablesorter-pager', 'js/jquery.tablesorter.pager.js', 'jquery');

		$this->layout->with('title', $player->fullname().TITLE_DELIM.'Bingos')
			->nest('content', 'players.bingos', array(
				'player' => $player,
				'bingos' => $bingos,
			));


	}


	public function get_games($id)
	{

		$player = Player::find($id);

		Asset::add('tablesorter', 'js/jquery.tablesorter.min.js', 'jquery');
		Asset::add('tablesorter-pager', 'js/jquery.tablesorter.pager.js', 'jquery');

		$this->layout->with('title', $player->fullname().TITLE_DELIM.'Games')
			->nest('content', 'players.games', array(
				'player' => $player,
				'games'  => $player->games,
			));

	}


	public function get_ratings($id)
	{

		$player = Player::find($id);

		Asset::add('tablesorter', 'js/jquery.tablesorter.min.js', 'jquery');
		Asset::add('tablesorter-pager', 'js/jquery.tablesorter.pager.js', 'jquery');

		$this->layout->with('title', $player->fullname().TITLE_DELIM.'Ratings')
			->nest('content', 'players.ratings', array(
				'player'  => $player,
				'ratings' => $player->ratings,
			));

	}

}