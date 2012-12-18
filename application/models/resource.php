<?php

class Resource extends BaseModel {

	public static $timestamps = true;

	public $rules = array(
		'title'            => 'required',
		'url'              => 'required|url_or_file',
		'resourcegroup_id' => 'required|exists:resourcegroups,id',
	);

	public $messages = array(
		'url_or_file' => 'Not a valid URL or file',
	);


	public function resourcegroup()
	{
	 return $this->belongs_to('Resourcegroup');
	}

	public static function last_sort()
	{
		return static::max('sort_order');
	}


	public function get_url($current=true) {
		return $current ? array_get($this->attributes, 'url') : array_get($this->original, 'url');
	}


	public function is_file($current=true)
	{
		return substr($this->get_url($current),0,7) == 'file://';
	}


	public function get_localfile($current=true)
	{
		if (!$this->is_file($current)) {
			return null;
		}

		if ( !( $url = $this->get_url($current) ) ) {
			return null;
		}

		$temp = explode('/', substr($url,7), 2 );
		return $temp[0];

	}


	public function get_filename($current=true)
	{
		if (!$this->is_file($current)) {
			return null;
		}

		if ( !( $url = $this->get_url($current) ) ) {
			return null;
		}

		$temp = explode('/', substr($url,7), 2 );
		return $temp[1];

	}


	public function get_downloadlink()
	{
		if ($this->is_file()) {
			return HTML::link_to_action(
				'download',
				$this->filename,
				array($this->id, $this->localfile, rawurlencode($this->filename)),
				array('target'=>'_blank')
			);
		} else {
			return HTML::link($this->url, $this->url, array('target'=>'blank'));
		}

	}


	public function delete_file($current=true)
	{
		if ($this->is_file($current)) {
			$filename = path('uploads') . $this->get_localfile($current);
			File::delete($filename);
		}
	}


	public function delete() {
		$this->delete_file();
		return parent::delete();
	}


	public function save() {
		if ($this->changed('url')) {
			$this->delete_file(false);
		}
		return parent::save();
	}


}
