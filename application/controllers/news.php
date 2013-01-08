<?php

class News_Controller extends Base_Controller {


	public function get_index()
	{

		$news = News::where('active','=',true)
			->order_by('date','desc')
			->paginate(5);


		$this->layout->with('title', 'News Archive')
			->nest('content', 'news.index', array(
				'news'    => $news,
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
