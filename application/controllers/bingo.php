<?php

class Bingo_Controller extends Base_Controller {


	public function get_index()
	{

		$firstgame = Game::order_by('date', 'asc')
			->take(1)
			->first();

		$bingos = Bingo::with('player')
			->left_join('validwords', 'bingos.word', '=', 'validwords.word')
			->order_by('score','desc')
			->order_by('date','desc')
			->take(30)
			->get(array('bingos.*','validwords.playability'));

		$commonest = Bingo::with('player')
			->left_join('validwords', 'bingos.word', '=', 'validwords.word')
			->order_by('times_played','desc')
			->order_by('average_score','desc')
			->group_by('bingos.word')
			// ->having('times_played','>',2)
			->take(15)
			->get(array(
				'bingos.*',
				'validwords.playability',
				DB::raw('COUNT(bingos.word) AS times_played'),
				DB::raw('ROUND(AVG(bingos.score)) AS average_score'),
			));


		$q = array();
		$alpha = range('a','z');
		foreach($alpha as $letter) {
			$q[] = 'SUM(LENGTH(word)-LENGTH(REPLACE(word,"'.$letter.'",""))) AS ' . $letter;
		}
		$q = DB::first('SELECT ' . join(",\n", $q) . ' FROM bingos');


		$letter_freq = (array)$q;
		$sum = array_sum($letter_freq);

		array_walk($letter_freq, function(&$v,$k,$sum) {
			$v = round( 100 * $v/$sum, 2 );
		}, $sum);




		// calculate common word tails

		$q = DB::query('SELECT
			COUNT(word) AS count,
			REVERSE(RIGHT(UPPER(word),3)) AS r3,
			REVERSE(RIGHT(UPPER(word),2)) AS r2,
			RIGHT(UPPER(word),1) AS r1
			FROM bingos
			GROUP BY r3
			ORDER BY r3, count DESC
		');

		$d1 = $d2 = $d3 = array();
		$s1 = $s2 = array();
		$l1 = $l2 = $l3 = '';
		$c = 0;

		foreach($q as $t) {

			if ($t->r1 != $l1) {
				$c++;
				$l1 = $t->r1;
				$d1[ $l1 ] = array(
					'name'  => $l1,
					'color' => make_color( $c, 1 ),
					'y'     => 0
				);
				$s1[ $l1 ] = 0;
			}
			$d1[ $l1 ]['y'] += $t->count;
			$s1[ $l1 ] += $t->count;

			if ($t->r2 != $l2) {
				$l2 = $t->r2;
				$d2[ $l2 ] = array(
					'name'  => strrev($l2),
					'color' => make_color( $c, 2 ),
					'y'     => 0
				);
				$s2[ $l2 ] = 0;
			}
			$d2[ $l2 ]['y'] += $t->count;
			$s2[ $l2 ] += $t->count;


			$l3 = $t->r3;
			$d3[ $l3 ] = array(
				'name'  => strrev($l3),
				'color' => make_color( $c, 3 ),
				'y'     => $t->count
			);

		}






		// clean out

		$x = '~';
		$s = array_sum($s1);
		$o1 = 0;

		foreach($d1 as $k1=>$v1) {
			if ($s1[$k1] / $s  < 0.05) {
				$o1 += $s1[$k1];
				unset($d1[$k1]);
				array_unset_keys_starting_with($d2, $k1);
				array_unset_keys_starting_with($d3, $k1);
			} else {

				$o2 = 0;

				foreach( array_keys_starting_with($d2, $k1) as $k2 ) {
					if ($s2[$k2] / $s1[$k1] < 0.1) {
						$o2 += $s2[$k2];
						unset($d2[$k2]);
						array_unset_keys_starting_with($d3, $k2);
					} else {

						$o3 = 0;
						foreach( array_keys_starting_with($d3, $k2) as $k3 ) {
							if ($d3[$k3]['y'] / $s2[$k2] < 0.15) {
								$o3 += $d3[$k3]['y'];
								unset($d3[$k3]);
							}
						}
						if ($o3) {
							$d3[$k2.$x] = array(
								'name' => $x.strrev($k2),
								'color' => make_color(0,3),
								'y' => $o3
							);
						}

					}


				}
				if ($o2) {
					$d2[$k1.$x] = array(
						'name' => $x.$k1,
						'color' => make_color(0,2),
						'y' => $o2
					);
					$d3[$k1.$x.$x] = array(
						'name' => $x.$x.$k1,
						'color' => make_color(0,3),
						'y' => $o2
					);
				}
			}

		}
		if ($o1) {
			$d1[$x] = array(
				'name' => $x,
				'color' => make_color(0, 1),
				'y' => $o1
			);
			$d2[$x.$x] = array(
				'name' => $x.$x,
				'color' => make_color(0, 2),
				'y' => $o1
			);
			$d3[$x.$x.$x] = array(
				'name' => $x.$x.$x,
				'color' => make_color(0, 3),
				'y' => $o1
			);
		}

		ksort($d1);
		ksort($d2);
		ksort($d3);


		// END tails


		// calculate common alphagrams

		$q = Bingo::all();

		$alphagrams = array();

		foreach($q as $b) {

			$a = alphabetize_word($b->word);

			// alphagrams;

			if (array_key_exists($a, $alphagrams)) {
				$alphagrams[$a]->count++;
				if ($b->score) {
					$alphagrams[$a]->score += $b->score;
					$alphagrams[$a]->scount++;
				}
			} else {
				$alphagrams[$a] = (object) array(
					'word' => $a,
					'count' => 1,
					'score' => $b->score,
					'scount' => ($b->score ? 1 : 0)
				);
			}

		}

		array_walk($alphagrams, function(&$v,$k){
			$v->average_score = round($v->scount==0 ? 0 : $v->score / $v->scount );
		});

		uasort($alphagrams, function($a,$b) {
			if ($a->count==$b->count) {
				return $a->average_score < $b->average_score;
			}
			return $a->count < $b->count;
		});

		$alphagrams = array_slice($alphagrams, 0, 15, true);


		// common subwords

		$min = 4;
		$max = 7;

		$subwords = array();

		foreach($alphagrams as $a) {

			$subs = find_subwords($a->word, $min, $max);

			foreach($subs as $sub) {

				$l = strlen($sub);
				if (!array_key_exists($l, $subwords)) {
					$subwords[$l] = array();
				}

				if (array_key_exists($sub, $subwords[$l])) {
					$subwords[$l][$sub]->count += $a->count;
					if ($b->score) {
						$subwords[$l][$sub]->score += $a->score;
						$subwords[$l][$sub]->scount += $a->scount;
					}
				} else {
					$subwords[$l][$sub] = (object) array(
						'word' => $sub,
						'count' => $a->count,
						'score' => $a->score,
						'scount' => $a->scount,
					);
				}

			}

		}

		foreach($subwords as $len=>&$subs) {

			array_walk($subs, function(&$v,$k){
				$v->average_score = round($v->scount==0 ? 0 : $v->score / $v->scount );
			});

			uasort($subs, function($a,$b) {
				if ($a->count==$b->count) {
					return $a->average_score < $b->average_score;
				}
				return $a->count < $b->count;
			});

			$subs = array_slice($subs, 0, 15, true);

		}


		Asset::add('highcharts', 'js/highcharts/highcharts.js', 'jquery');

		$this->layout->with('title', 'Bingo Statistics')
			->nest('content', 'bingo.index', array(
				'firstgame'   => $firstgame,
				'bingos'      => $bingos,
				'commonest'   => $commonest,
				'alpha'				=> $alpha,
				'letter_freq' => $letter_freq,
				'tails'       => array( $d1, $d2, $d3 ),
				'tail_sum'    => $s,
				'alphagrams'	=> $alphagrams,
				'subwords'	  => $subwords,
			));

	}


}
