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

		if ( !$item || ( !$item->active && !Auth::check() ) ) {
			return Response::error('404');
		}

		Asset::add('fancybox', 'js/fancybox.min.js', 'jquery');
		Asset::container('head')->add('fancybox', 'css/fancybox.css');

		$this->layout->with('title', $item->title)
			->nest('content', 'news.item', array(
				'item'    => $item,
			));
	}



}
