<?php

class About_Controller extends Base_Controller {

	public function get_index()
	{

		$this->layout->with('title', 'About')
			->nest('content', 'about.index')
			->nest('fb', 'partials.facebook', array(
				'fb' => Config::get('facebook')
			));
	}

	public function get_resources()
	{

		$resourcegroups = Resourcegroup::with(array('resources'))
			->order_by('sort_order','asc')->get();

		$this->layout->with('title', 'Resources & Links')
			->nest('content', 'about.resources', array(
				'resourcegroups' => $resourcegroups,
			));

	}


}
