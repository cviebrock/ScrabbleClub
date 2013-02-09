<?php

class Page extends BaseModel {

	public static $timestamps = false;

	public $rules = array(
		'url'				=> 'unique:pages',
		'title'     => 'required|max:128',
		'body'      => 'required',
	);


	public static function load( $url )
	{
		return static::where('url','=',$url)->first();
	}


	public function save() {

		if ( $r = parent::save() ) {
			Cache::forget('page.'.$this->url);
		}

		return $r;

	}


	public function get_render()
	{
		require_once path('bundle').'docs/libraries/markdown.php';
		return '<div class="page-header"><h1>' . $this->title . '</h1></div>' .
			"\n\n". Markdown($this->body);
	}



}
