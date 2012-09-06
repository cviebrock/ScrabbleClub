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