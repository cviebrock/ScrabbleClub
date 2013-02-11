<?php

class Admin_News_Controller extends Base_Controller {


	public function __construct()
	{
		$this->filter('before', 'auth');
		$this->filter('before', 'csrf')->on('post');
		parent::__construct();
	}

	public function load_albums($refresh=false)
	{

		if ($refresh) {
			Cache::forget('fb.albums');
		}

		return Cache::remember('fb.albums', function() {

			$fb = Ioc::resolve('facebook');

			$temp = $fb->api('/'.Config::get('facebook.uid').'/albums', 'GET', array('fields'=>'id,count,name,description') );

			$albums = array(0=>'None');
			foreach($temp['data'] as $v) {
				$albums[ $v['id'] ] = $v['name'] . ' (' . pluralize( 'picture', $v['count'] ) . ')';
			}

			return $albums;
		}, 10 );

	}


	public function get_index()
	{

		$news = News::order_by('date','desc')->get();

		$this->layout->with('title', 'News')
			->nest('content', 'admin.news.index', array(
				'news' => $news
			));
	}


	public function get_new()
	{
		$item = new News(array(
			'author_id' => Auth::user()->id,
			'date' => format_date('now', 'Y-m-d'),
		));

		Asset::add('dateinput', 'js/jquery.tools.min.js', 'jquery');
		Asset::add('string_score', 'js/string_score.min.js', 'jquery');
		Asset::add('quickselect', 'js/jquery.quickselect.js', 'jquery');

		$this->layout->with('title', 'New News Item')
			->nest('content', 'admin.news.form', array(
				'item'        => $item,
				'all_players' => all_players(),
				'albums'      => $this->load_albums(true),
				'title'       => 'New News Item',
				'submit_text' => 'Add News Item',
				'mode'        => 'new'
			));
	}




	public function post_new()
	{

		$item = new News;

		$temp = new DateTime( Input::get('date') );

		$item->fill(array(
			'title'     => Input::get('title'),
			'body'      => Input::get('body'),
			'date'      => $temp->format('Y-m-d'),
			'author_id' => Input::get('author_id'),
			'active'    => Input::get('active', 0),
			'fb_album'  => Input::get('fb_album', 0),
		));


		if ($item->is_valid()) {
			$item->save();
			return Redirect::to_action('admin.news')
				->with('success', 'News Item "' . $item->title . '" added.');
		}

		Asset::add('dateinput', 'js/jquery.tools.min.js', 'jquery');
		Asset::add('string_score', 'js/string_score.min.js', 'jquery');
		Asset::add('quickselect', 'js/jquery.quickselect.js', 'jquery');

		$albums = $this->load_albums();

		$this->layout->with('title', 'New News Item')
			->nest('content', 'admin.news.form', array(
				'item'        => $item,
				'all_players' => all_players(),
				'albums'      => $this->load_albums(),
				'title'       => 'New News Item',
				'submit_text' => 'Add News Item',
				'mode'        => 'new'
			));

	}


	public function get_edit($id)
	{
		$item = News::find($id);

		Asset::add('dateinput', 'js/jquery.tools.min.js', 'jquery');
		Asset::add('string_score', 'js/string_score.min.js', 'jquery');
		Asset::add('quickselect', 'js/jquery.quickselect.js', 'jquery');

		$this->layout->with('title', 'Edit News Item')
			->nest('content', 'admin.news.form', array(
				'item'        => $item,
				'all_players'  => all_players(),
				'albums'      => $this->load_albums(true),
				'title'       => 'Edit News Item',
				'submit_text' => 'Edit News Item',
				'mode'        => 'edit'
			));
	}


	public function post_edit($id)
	{

		$item = News::find($id);

		$temp = new DateTime( Input::get('date') );

		$item->fill(array(
			'title'  => Input::get('title'),
			'body'   => Input::get('body'),
			'date'   => $temp->format('Y-m-d'),
			'author_id' => Input::get('author_id'),
			'active' => Input::get('active', 0),
			'fb_album'  => Input::get('fb_album', 0),
		));


		if ($item->is_valid()) {
			$item->save();
			return Redirect::to_action('admin.news')
				->with('success', 'News Item "' . $item->title . '" edited.');
		}

		Asset::add('dateinput', 'js/jquery.tools.min.js', 'jquery');
		Asset::add('string_score', 'js/string_score.min.js', 'jquery');
		Asset::add('quickselect', 'js/jquery.quickselect.js', 'jquery');

		$this->layout->with('title', 'Edit News Item')
			->nest('content', 'admin.news.form', array(
				'item'        => $item,
				'all_players'  => all_players(),
				'albums'      => $this->load_albums(true),
				'title'       => 'Edit News Item',
				'submit_text' => 'Edit News Item',
				'mode'        => 'edit'
			));

	}


	public function get_delete($id)
	{

		$item = News::find($id);

		$this->layout->with('title', 'Delete News Item')
			->nest('content', 'admin.news.delete', array(
				'item'				=> $item,
			));
	}


	public function post_delete($id)
	{

		$item = News::find($id);

		if ( !Input::get('confirm') ) {
			return Redirect::to_action('admin.news@delete', array($id))
				->with('warning', 'News item not deleted &mdash; confirmation not checked.');
		}

		$item->delete();
		return Redirect::to_action('admin.news')
			->with('success', 'News item "' . $item->title . '" deleted.');

	}

}
