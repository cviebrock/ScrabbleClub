<?php

class Resourcegroup extends BaseModel {

	public static $timestamps = true;

	public $rules = array(
		'title'    => 'required',
	);

	public function resources()
	{
	 return $this->has_many('Resource');
	}


	public static function last_sort()
	{
		return static::max('sort_order');
	}

}