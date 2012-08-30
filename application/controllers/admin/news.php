<?php

class Admin_News_Controller extends Base_Controller {


	public function __construct()
	{
		$this->filter('before', 'auth');
		$this->filter('before', 'csrf')->on('post');
		parent::__construct();

	}

	public function get_index()
	{
		$this->layout->with('title', 'News')
			->nest('content', 'admin.news.index');
	}


}
