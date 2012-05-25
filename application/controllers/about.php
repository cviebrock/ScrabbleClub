<?php

class About_Controller extends Base_Controller {

	public function get_index()
	{

		$this->layout->with('title', 'About')
			->nest('content', 'about.index');

	}

	public function get_resources()
	{

		$this->layout->with('title', 'Resources')
			->nest('content', 'about.resources');

	}

	public function get_links()
	{

		$this->layout->with('title', 'Links')
			->nest('content', 'about.links');

	}

}