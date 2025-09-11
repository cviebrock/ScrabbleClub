<?php

class About_Controller extends Base_Controller {

	public function get_index()
	{

		Asset::container('head')->add('ml', 'js/mailerlite.js');

		$this->layout->with('title', 'About')
      ->with('canonical', 'about')
			->nest('content', 'about.index')
			->nest('fb', 'partials.facebook', array(
				'fb' => Config::get('facebook')
			));
	}

	public function get_resources()
	{

		$resourcegroups = Resourcegroup::with(array('resources'))
			->where('private','=',0)
			->order_by('sort_order','asc')->get();

		$this->layout->with('title', 'Resources & Links')
      ->with('canonical', 'about/resources')
			->nest('content', 'about.resources', array(
				'resourcegroups' => $resourcegroups,
			));

	}


}
