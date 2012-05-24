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



	public function get_backup()
	{

		$this->layout->with('title', 'Backup')
			->nest('content', 'admin.housekeeping.backup');

	}


	public function get_backup_sql()
	{

		$default = Config::get('database.default');
		$db = Config::get('database.connections.'.$default);

		$filename = 'scrabble-' . date('YmdHis') . '.sql.gz';

		$command = sprintf(
			'/usr/local/mysql/bin/mysqldump --user=%s --password=%s %s | gzip -f -9',
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


}
