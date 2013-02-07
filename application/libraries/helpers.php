<?php


function index_array( $models )
{
	$array = array();
	foreach ($models as $model) {
		$array[ $model->id ] = $model;
	}
	return $array;
}


function action_link_to_route($name, $title, $parameters=array(), $icon=null, $attributes = array() ) {

	$small = false;

	if ($icon) {
		$icons = explode('|',$icon);
		$tag = '<i class="';
		foreach($icons as $i) {
			if ($i=='small') {
				$small = true;
			} else {
				$tag .= 'icon-'.$i.' ';
			}
		}
		if (empty($title)) $tag .= 'empty ';
		$tag .= '"></i>';
		$title = $tag.$title;
	}

	if (!array_key_exists('class', $attributes)) {
		$attributes['class'] = 'btn';
		if ($small) {
			$attributes['class'] .= ' btn-small';
		}
	}

	return HTML::link_to_action($name, $title, $parameters, $attributes );

}



function all_players($excl=null)
{
	$players = index_array( Player::all() );
	if ($excl && array_key_exists($excl, $players)) {
		unset($players[$excl]);
	}
	$players[0] = '';
	ksort($players);
	return $players;
	// $data = array(0=>'');
	// foreach($players as $player) {
	// 	if ($player->id != $excl ) {
	// 		$data[ $player->id ] = $player->fullname;
	// 	}
	// }
	// return $data;
}


function rwords($num=3, $with_score=true) {

	$words = DB::query('SELECT
		word
		FROM validwords
		ORDER BY RAND()
		LIMIT ?',
		array($num)
	);

	$return = '';
	foreach($words as $word) {

		if ($return) $return .= ', ';

		$return .= $word->word;

		if ($with_score) {
			$return .= ' ' . mt_rand(60,100);
		}

	}

	return $return;

}


function format_date($date, $format='d-M-Y') {
	if ($date) {
		if (!($date instanceof DateTime)) {
			try {
				$date = new DateTime($date);
			} catch ( Exception $e ) {
				return null;
			}
		}
		return $date->format($format);
	} else {
		return null;
	}
}


function array_keys_starting_with( $array, $key ) {
	$old_keys = array_keys($array);
	return array_filter($old_keys, function($v) use ($key) {
		return strpos($v, $key)===0;
	});
}


function array_unset_keys_starting_with( &$array, $key ) {
	$filtered_keys = array_keys_starting_with( $array, $key );
	foreach ($filtered_keys as $k) {
		unset($array[$k]);
	}
}


function alphabetize_word_to_array( $word ) {
	$array = str_split($word);
	sort($array);
	return $array;
}

function alphabetize_word( $word ) {
	return join('', alphabetize_word_to_array($word));
}

function find_subwords($word, $min_length=0, $max_length=false) {
	$word = alphabetize_word_to_array($word);
	 $count = count($word);
	 $members = pow(2,$count);
	 $return = array();

	 for ($i = 0; $i < $members; $i++) {
			$b = sprintf("%0".$count."b",$i);
			$out = array();
			for ($j = 0; $j < $count; $j++) {
				 if ($b{$j} == '1') $out[] = $word[$j];
			}
			if (count($out)>=$min_length) {
				if ($max_length===false || count($out)<=$max_length) {
					$return[] = join('',$out);
				}
			}
	 }
	 return $return;
}

function pluralize($what, $num)
{
	return sprintf('%d %s', $num, Str::plural($what,$num) );
}

function make_color($index, $level) {

	$colors = array(
		array(  69, 114, 167 ),
		array( 170,  70,  67 ) ,
		array( 137, 165,  78 ),
		array( 128, 105, 155 ),
		array(  61, 150, 174 ),
		array( 219, 132,  61 ),
		array( 146, 168, 205 ),
		array( 164, 125, 124 ),
		array( 181, 202, 146 )
	);

	if ($index==0) {
		$color = array( 153, 153, 153 );
	} else {
		$color = $colors[ ($index-1) % count($colors) ];
	}

	$l = (($level-1) * 10) / 100;
	$m = 1-$l;

	$r = ($color[0] * $m) + ( 255 * $l );
	$g = ($color[1] * $m) + ( 255 * $l );
	$b = ($color[2] * $m) + ( 255 * $l );

	return sprintf('#%02x%02x%02x' , $r, $g, $b );

}


/**
 * Add "http://" to URLs if it doesn't already exist
 */
function protofy($address)
{
	if (empty($address)) return '';
	$http = preg_match('/^([a-z]+:)?\/\//i', $address) ? '' : 'http://';
	return $http.$address;
}


function game_summary( array $games )
{

		$summary = (object) array(
			'games_played'  => 0,
			'wins'          => 0,
			'losses'        => 0,
			'ties'          => 0,
			'total_score'   => 0,
			'total_against' => 0,
			'record'        => '0.0-0.0',
			'percentage'    => '&mdash;'
		);

		if (count($games) ) {

			foreach($games as $game) {
				$summary->games_played++;
				$summary->total_score += $game->player_score;
				$summary->total_against += $game->opponent_score;

				if ($game->spread == 0 ) {
					$summary->ties++;
				} else if ($game->spread > 0 ) {
					$summary->wins++;
				} else {
					$summary->losses++;
				}
			}

			$numerator = $summary->wins + ($summary->ties / 2 );
			$summary->record = sprintf('%.1f-%.1f',
				$numerator,
				$summary->losses
			);
			$summary->percentage = sprintf('%.1f%%', $numerator * 100 / $summary->games_played );

			$summary->average_score = round( $summary->total_score / $summary->games_played );
			$summary->average_opponent_score = round( $summary->total_against / $summary->games_played );
			$summary->average_spread = round( ($summary->total_score - $summary->total_against) / $summary->games_played );

		}

		return $summary;
}




function paragraphs($value, $paragraphs = 2, $end = '...')
{
	if (trim($value) == '') return '';

	$parts = preg_split('/\n+/', $value, null, PREG_SPLIT_DELIM_CAPTURE);

	$num = ($paragraphs * 2 ) - 1;

	$chunk = array_chunk($parts, $num);

	return join($chunk[0]);

}
