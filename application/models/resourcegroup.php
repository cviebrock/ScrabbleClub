<?php

class Resourcegroup extends BaseModel {

	public static $timestamps = true;

	public $rules = array(
		'title'    => 'required',
	);

	public function resources()
	{
	 return $this->has_many('Resource')->order_by('sort_order','asc');
	}


	public static function last_sort()
	{
		return static::max('sort_order');
	}

	public function delete()
	{
		foreach( $this->resources as $resource ) {
			$resource->delete();
		}
		return parent::delete();
	}

}
