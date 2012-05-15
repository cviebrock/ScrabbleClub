<?php

class Base_Controller extends Controller {

	// make controllers REST-ful by default
	public $restful = true;


	// One view to rule them all ...
	public $layout = 'base';


	/**
	 * Catch-all method for requests that can't be matched.
	 *
	 * @param  string    $method
	 * @param  array     $parameters
	 * @return Response
	 */
	public function __call($method, $parameters)
	{
		return Response::error('404');
	}

}