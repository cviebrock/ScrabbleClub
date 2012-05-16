<?php

class App {


	public static function format_date($date, $format='d-M-Y') {
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


	public static function has_errors($object, $field)
	{
		return array_key_exists($field, $object->errors);
	}


	public static function errors_for($object, $field, $inline=true)
	{
		if (array_key_exists($field, $object->errors)) {
			return View::make('partials.form_errors')
					->with('messages', $object->errors[$field] )
					->with('inline' , $inline);
		} else {
			return '';
		}
	}


	public static function help_for($object, $field)
	{
		if (array_key_exists($field, $object->help)) {
			return View::make('partials.form_help')
					->with('message', $object->help[$field] );
		} else {
			return '';
		}
	}


	public static function all_players($excl=null)
	{
		$players = Player::all();
		$data = array(0=>'');
		foreach($players as $player) {
			if ($player->id != $excl ) {
				$data[ $player->id ] = $player->fullname();
			}
		}
		return $data;
	}


	public static function rwords($num=3, $with_score=true) {

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


	public static function action_link_to_route($name, $title, $parameters=array(), $icon=null, $attributes = array() ) {

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

		return static::link_to_action($name, $title, $parameters, $attributes );

	}


	public static function link_to_action($name, $title, $parameters = array(), $attributes = array(), $https = false)
	{
		return static::link(URL::to_action($name, $parameters, $https), $title, $attributes);
	}


	public static function link($url, $title, $attributes = array(), $https = false)
	{
		$url = HTML::entities(URL::to($url, $https));
		return '<a href="'.$url.'"'.HTML::attributes($attributes).'>'.$title.'</a>';
	}


	/**
	 * Convert an un-indexed array of models into one indexed on the id.
	 */
	public static function index_array( $models )
	{
		$array = array();
		foreach ($models as $model) {
			$array[ $model->id ] = $model;
		}
		return $array;
	}

}