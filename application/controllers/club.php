<?php

class Club_Controller extends Base_Controller {


	public function get_index()
	{

		$year = Input::get('year',null);

		$overall = array();

		$q = 'SELECT
			COUNT(g.id)/2 AS total_games,
			COUNT(DISTINCT g.date) AS total_dates,
			AVG(g.player_score) AS average_score
			FROM games g
		';

		if ($year) {
			$temp = DB::query($q.' WHERE YEAR(date) = ?', array($year) );
		} else {
			$temp = DB::query($q);
		}

		$overall = array_merge($overall, (array)$temp[0]);

		$q = 'SELECT
			AVG(x.players) as players_per_date
			FROM (
				SELECT
				date, COUNT(DISTINCT player_id) AS players
				FROM games
				GROUP BY date
			) x
		';

		if ($year) {
			$temp = DB::query($q.' WHERE YEAR(x.date) = ?', array($year) );
		} else {
			$temp = DB::query($q);
		}

		$overall = array_merge($overall, (array)$temp[0]);


		$q = 'SELECT
			COUNT(id) AS total_bingos,
			SUM(valid) AS valid_bingos
			FROM bingos
		';

		if ($year) {
			$temp = DB::query($q.' WHERE YEAR(date) = ?', array($year) );
		} else {
			$temp = DB::query($q);
		}

		$overall = array_merge($overall, (array)$temp[0]);


		$q = 'SELECT
			g.date AS date,
			COUNT(DISTINCT g.player_id) AS players
			FROM games g' .
			( $year ? ' WHERE YEAR(date) = ?' : '' ) .
			' GROUP BY date';

		if ($year) {
			$attendance = DB::query($q, array($year));
		} else {
			$attendance = DB::query($q);
		}

		if ($year) {

			$high_scores = Game::with(array('player','opponent'))
				->where(DB::raw('YEAR(date)'), '=', $year )
				->order_by('player_score','desc')
				->order_by('opponent_score','desc')
				->order_by('date','desc')
				->take(5)
				->get();

			$high_losses = Game::with(array('player','opponent'))
				->where(DB::raw('YEAR(date)'), '=', $year )
				->where('spread','>',0)
				->order_by('opponent_score','desc')
				->order_by('date','desc')
				->take(5)
				->get();

			$blowouts = Game::with(array('player','opponent'))
				->where(DB::raw('YEAR(date)'), '=', $year )
				->order_by('spread','desc')
				->order_by('date','desc')
				->take(5)
				->get();

			$combined = Game::with(array('player','opponent'))
				->where(DB::raw('YEAR(date)'), '=', $year )
				->where('spread','>=',0)
				->order_by(DB::raw('`player_score`+`opponent_score`'),'desc')
				->order_by('date','desc')
				->take(5)
				->get();

			$bingos = Bingo::with('player')
				->left_join('validwords', 'bingos.word', '=', 'validwords.word')
				->where(DB::raw('YEAR(date)'), '=', $year )
				->order_by('score','desc')
				->order_by('date','desc')
				->take(5)
				->get(array('bingos.*','validwords.playability'));

		} else {

			$high_scores = Game::with(array('player','opponent'))
				->order_by('player_score','desc')
				->order_by('opponent_score','desc')
				->order_by('date','desc')
				->take(5)
				->get();

			$high_losses = Game::with(array('player','opponent'))
				->where('spread','>',0)
				->order_by('opponent_score','desc')
				->order_by('date','desc')
				->take(5)
				->get();

			$blowouts = Game::with(array('player','opponent'))
				->order_by('spread','desc')
				->order_by('date','desc')
				->take(5)
				->get();

			$combined = Game::with(array('player','opponent'))
				->where('spread','>=',0)
				->order_by(DB::raw('`player_score`+`opponent_score`'),'desc')
				->order_by('date','desc')
				->take(5)
				->get();

			$bingos = Bingo::with('player')
				->left_join('validwords', 'bingos.word', '=', 'validwords.word')
				->order_by('score','desc')
				->order_by('date','desc')
				->take(5)
				->get(array('bingos.*','validwords.playability'));

		}

		Asset::add('highcharts', 'js/highcharts/highcharts.js', 'jquery');

		$this->layout->with('title', 'Club Statistics')
			->nest('content', 'club.index', array(
				'year'        => $year,
				'overall'     => $overall,
				'attendance'  => $attendance,
				'high_scores' => $high_scores,
				'high_losses' => $high_losses,
				'blowouts'    => $blowouts,
				'combined'    => $combined,
				'bingos'      => $bingos,
			));

		// Asset::add('tablesorter', 'js/jquery.tablesorter.min.js', 'jquery');
		// Asset::add('tablesorter-pager', 'js/jquery.tablesorter.pager.js', 'jquery');
		// Asset::add('string_score', 'js/string_score.min.js', 'jquery');
		// Asset::add('quickselect', 'js/jquery.quickselect.js', 'jquery');

	}


	public function get_summary($date)
	{

		$temp = DB::query('SELECT
			DISTINCT(date) as date
			FROM games
			ORDER BY date DESC
		');

		$all_dates = array();
		foreach($temp as $v) {
			$all_dates[$v->date] = format_date($v->date);
		}

		$temp = DB::query('SELECT
			COUNT(g.id)/2 AS total_games,
			COUNT(DISTINCT g.player_id) AS total_players,
			AVG(g.player_score) AS average_score
			FROM games g
			WHERE g.date=?',
			$date
		);

		$overall = (array)$temp[0];

		if ($overall['total_players']==0) {
			$this->layout->with('title', 'Game Night Summary for '.$date)
				->nest('content', 'club.summary_nogame', array(
					'date'      => $date,
				));
			return;
		}

		$bingos = Bingo::with('player')
			->left_join('validwords', 'bingos.word', '=', 'validwords.word')
			->where('date','=',$date)
			->order_by('score','desc')
			->take(5)
			->get(array('bingos.*','validwords.playability'));

		$ratings = Rating::with('player')
			->where('date','=',$date)
			->get();

		$games = Game::with(array('player','opponent'))
			->select(array(
				'*',
				DB::raw('IF(spread=0,IF(player_id>opponent_id,1,0),sign(spread)) AS filter')
			))
			->where('date','=',$date)
			->having('filter','=', 1)
			->order_by('spread','desc')
			->get();


		Asset::add('tablesorter', 'js/jquery.tablesorter.min.js', 'jquery');

		$this->layout->with('title', 'Game Night Summary'.TITLE_DELIM.$date)
			->nest('content', 'club.summary', array(
				'date'      => $date,
				'all_dates' => $all_dates,
				'overall'   => $overall,
				'bingos'    => $bingos,
				'ratings'   => $ratings,
				'games'     => $games,
			));



	}


}
