<?php

class Players_Controller extends Base_Controller {


	public function get_index()
	{

		$lastgame = Game::order_by('date', 'desc')
			->take(1)
			->first();

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
				'lastgame' => $lastgame,
				'players'  => $players,
				'bingos'   => $bingos,
			));

	}


	public function get_details($id)
	{

		$player = Player::find($id);

		$temp = DB::query('SELECT
			COUNT(g.id) AS games_played,
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

		$low_score = Game::where('player_id','=',$id)
			->order_by('player_score','asc')
			->order_by('date','desc')
			->first();

		Asset::add('tablesorter', 'js/jquery.tablesorter.min.js', 'jquery');
		Asset::add('tablesorter-pager', 'js/jquery.tablesorter.pager.js', 'jquery');
		Asset::add('string_score', 'js/string_score.min.js', 'jquery');
		Asset::add('quickselect', 'js/jquery.quickselect.js', 'jquery');


		$this->layout->with('title', $player->fullname())
			->nest('content', 'players.details', array(
				'player'       => $player,
				'all_players'  => App::all_players($id),
				'club_details' => $club_details,
				'best_spread'  => $best_spread,
				'worst_spread' => $worst_spread,
				'high_score'   => $high_score,
				'low_score'    => $low_score,
				'bingos'       => $bingos,
			));


	}


	public function get_bingos($id)
	{

		$player = Player::find($id);

		$bingos = DB::query('SELECT
			b.*,
			v.playability AS playability
			FROM bingos b LEFT JOIN validwords v USING (word)
			WHERE b.player_id = ?
		', array($id));


		Asset::add('tablesorter', 'js/jquery.tablesorter.min.js', 'jquery');
		Asset::add('tablesorter-pager', 'js/jquery.tablesorter.pager.js', 'jquery');

		$this->layout->with('title', $player->fullname())
			->nest('content', 'players.bingos', array(
				'player' => $player,
				'bingos' => $bingos,
			));


	}


}