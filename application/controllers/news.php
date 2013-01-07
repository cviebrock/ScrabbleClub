<?php

class News_Controller extends Base_Controller {


	public function get_index()
	{

		$item = News::find($id);

		$this->layout->with('title', $item->title)
			->nest('content', 'home.index', array(
				'item'    => $item,
			));
	}



	public function get_item($id, $slug=null)
	{

		$item = News::find($id);

		$this->layout->with('title', $item->title)
			->nest('content', 'news.item', array(
				'item'    => $item,
			));
	}



}
