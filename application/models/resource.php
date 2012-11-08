<?php

class Resource extends BaseModel {

	public static $timestamps = true;

	public $rules = array(
		'title'            => 'required',
		'url'              => 'required',
		'resourcegroup_id' => 'required|exists:resourcegroup,id',
	);

	public function resourcegroup()
	{
	 return $this->belongs_to('Resourcegroup');
	}


}