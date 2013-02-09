<?php

class Admin_Housekeeping_Controller extends Base_Controller {


	public function __construct()
	{
		$this->filter('before', 'auth');
		$this->filter('before', 'csrf')->on('post');
		parent::__construct();

	}


	public function get_index()
	{

		$this->layout->with('title', 'Housekeeping')
			->nest('content', 'admin.housekeeping.index');

	}


	public function get_backup_sql()
	{

		$default = Config::get('database.default');
		$db = Config::get('database.connections.'.$default);

		$filename = 'scrabble-' . date('YmdHis') . '.sql.gz';

		$command = sprintf(
			'mysqldump --host=%s --user=%s --password=%s %s | gzip -f -9',
			escapeshellarg($db['host']),
			escapeshellarg($db['username']),
			escapeshellarg($db['password']),
			escapeshellarg($db['database'])
		);

		$data = shell_exec($command);

		return Response::make($data, 200, array(
			'Content-Description'       => 'File Transfer',
			'Content-Type'              => File::mime('gz'),
			'Content-Disposition'       => 'attachment; filename="'. $filename . '"',
			'Content-Transfer-Encoding' => 'binary',
			'Expires'                   => 0,
			'Cache-Control'             => 'must-revalidate, post-check=0, pre-check=0',
			'Pragma'                    => 'public',
			'Content-Length'            => strlen($data)
		));


	}


	public function get_export_bingos()
	{

		$bingos = Bingo::with(array('player'))
			->order_by('date','asc')
			->get();

		$data = '"date","player","player naspa","bingo","phoney","score"' . "\n";

		foreach($bingos as $bingo) {
			$data .= sprintf('"%s","%s","%s","%s","%s"',
				$bingo->date,
				$bingo->player,
				$bingo->player->naspa_id,
				$bingo->word,
				$bingo->valid ? '' : 'x',
				$bingo->score ?: ''
			) . "\n";
		}

		$filename = 'bingos-' . format_date($bingo->date,'Ymd') . '.csv';

		return Response::make($data, 200, array(
			'Content-Description'       => 'File Transfer',
			'Content-Type'              => File::mime('csv'),
			'Content-Disposition'       => 'attachment; filename="'. $filename . '"',
			'Content-Transfer-Encoding' => 'binary',
			'Expires'                   => 0,
			'Cache-Control'             => 'must-revalidate, post-check=0, pre-check=0',
			'Pragma'                    => 'public',
			'Content-Length'            => strlen($data)
		));

	}


	public function get_homepage()
	{

		if ( !( $homepage = Page::load('home') ) ) {
			$homepage = new Page;
		}

		$this->layout->with('title', 'Housekeeping'.TITLE_DELIM.'Home Page')
			->nest('content', 'admin.housekeeping.homepage', array(
				'homepage' => $homepage
			));
	}


	public function post_homepage()
	{

		if ( !( $homepage = Page::load('home') ) ) {
			$homepage = new Page(array('url'=>'/'));
		}

		$homepage->fill(array(
			'title'  => Input::get('title'),
			'body'   => Input::get('body'),
		));


		if ($homepage->is_valid()) {
			$homepage->save();
			return Redirect::to_action('admin.housekeeping@homepage')
				->with('success', 'Homepage saved.');
		}

		$this->layout->with('title', 'Housekeeping'.TITLE_DELIM.'Home Page')
			->nest('content', 'admin.housekeeping.homepage', array(
				'homepage' => $homepage
			));

	}



}
