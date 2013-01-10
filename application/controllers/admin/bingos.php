<?php

class Admin_Bingos_Controller extends Base_Controller {


	public function __construct()
	{
		$this->filter('before', 'auth');
		$this->filter('before', 'csrf')->on('post');
		parent::__construct();

	}



	public function get_index()
	{

		$this->layout->with('title', 'Bingos')
			->nest('content', 'admin.bingos.index');
	}



	public function get_edit($id)
	{

		$bingo = Bingo::find($id);

		Asset::container('head')->add('dateinput', 'js/jquery.tools.min.js', 'jquery');
		Asset::container('head')->add('string_score', 'js/string_score.min.js', 'jquery');
		Asset::container('head')->add('quickselect', 'js/jquery.quickselect.js', 'jquery');

		$this->layout->with('title', 'Edit Bingo')
			->nest('content', 'admin.bingos.form', array(
				'bingo'       => $bingo,
				'all_players' => all_players(),
				'title'       => 'Edit Bingo',
				'submit_text' => 'Edit Bingo',
				'mode'        => 'edit'
			));

	}



	public function post_edit($id)
	{

		$bingo = Bingo::find($id);

		$input = array_only( Input::all(), array('player_id','word','score') );
		$bingo->fill( $input );
		try {
			$temp = new DateTime( Input::get('date') );
			$bingo->date = $temp->format('Y-m-d');
		} catch ( Exception $e )  {
			$bingo->date = null;
		}


		if ( $bingo->is_valid() ) {
			$bingo->save();
			return Redirect::to_action('admin.bingos@edit', array($id))
				->with('success', 'Bingo "' . $bingo->word . '" edited.');
		}

		Asset::container('head')->add('dateinput', 'js/jquery.tools.min.js', 'jquery');
		Asset::container('head')->add('string_score', 'js/string_score.min.js', 'jquery');
		Asset::container('head')->add('quickselect', 'js/jquery.quickselect.js', 'jquery');

		$this->layout->with('title', 'Edit Bingo')
			->nest('content', 'admin.bingos.form', array(
				'bingo'       => $bingo,
				'all_players' => all_players(),
				'title'       => 'Edit Bingo',
				'submit_text' => 'Edit Bingo',
				'mode'        => 'edit'
			));

	}


	public function get_delete($id)
	{

		$bingo = Bingo::find($id);

		$this->layout->with('title', 'Delete Bingo')
			->nest('content', 'admin.bingos.delete', array(
				'bingo' => $bingo,
			));
	}


	public function post_delete($id)
	{

		$bingo = Bingo::find($id);

		if ( !Input::get('confirm') ) {
			return Redirect::to_action('admin.bingos@delete', array($id))
				->with('warning', 'Bingo not deleted &mdash; confirmation not checked.');
		}

		$bingo->delete();
		return Redirect::to_action('admin.bingos')
			->with('success', 'Bingo "' . $bingo->word_and_score() . '" deleted.');

	}
}
