<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Simply tell Laravel the HTTP verbs and URIs it should respond to. It is a
| breeze to setup your application using Laravel's RESTful routing and it
| is perfectly suited for building large applications and simple APIs.
|
| Let's respond to a simple GET request to http://example.com/hello:
|
|		Route::get('hello', function()
|		{
|			return 'Hello World!';
|		});
|
| You can even respond to more than one URI:
|
|		Route::post(array('hello', 'world'), function()
|		{
|			return 'Hello World!';
|		});
|
| It's easy to allow URI wildcards using (:num) or (:any):
|
|		Route::put('hello/(:any)', function($name)
|		{
|			return "Welcome, $name.";
|		});
|
*/

// Route::get('/', function()
// {
// 	return View::make('home.index');
// });


Route::controller('home');
Route::any('login',    array('as'=>'login',    'uses'=>'home@login')    );
Route::any('logout',   array('as'=>'logout',   'uses'=>'home@logout')   );
Route::get('download/(:num)/(:any)/(:all)', array('as'=>'download', 'uses'=>'home@download') );
Route::any('/',        array('as'=>'home',     'uses'=>'home@index')    );

Route::controller('about');

Route::controller('ajax');
Route::get('ajax/games/(:num)/(:num)', array( 'as'=>'ajax_one_on_one', 'uses'=>'ajax@games' ));

Route::controller('bingo');
Route::controller('club');

Route::get('news/(:num)/(:all?)', array( 'as'=>'news_item', 'uses'=>'news@item' ));
Route::controller('news');

Route::controller('players');

Route::controller('admin.games');
Route::controller('admin.players');
Route::controller('admin.housekeeping');
Route::controller('admin.news');
Route::get('admin/resources/(:num)', array('uses' => 'admin.resources@index') );
Route::controller('admin.resources');



/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
|
| To centralize and simplify 404 handling, Laravel uses an awesome event
| system to retrieve the response. Feel free to modify this function to
| your tastes and the needs of your application.
|
| Similarly, we use an event to handle the display of 500 level errors
| within the application. These errors are fired when there is an
| uncaught exception thrown in the application.
|
*/

Event::listen('404', function()
{
	return Response::error('404');
});

Event::listen('500', function()
{
	return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
|
| Filters provide a convenient method for attaching functionality to your
| routes. The built-in before and after filters are called before and
| after every request to your application, and you may even create
| other filters that can be attached to individual routes.
|
| Let's walk through an example...
|
| First, define a filter:
|
|		Route::filter('filter', function()
|		{
|			return 'Filtered!';
|		});
|
| Next, attach the filter to a route:
|
|		Router::register('GET /', array('before' => 'filter', function()
|		{
|			return 'Hello World!';
|		}));
|
*/

Route::filter('before', function()
{
	// Do stuff before every request to your application...
});

Route::filter('after', function($response)
{
	// Do stuff after every request to your application...
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::to('login');
});


/****************************/

/*
|--------------------------------------------------------------------------
| View composers
|--------------------------------------------------------------------------
*/

View::composer('base', function($view)
{
	Asset::container('head')->add('html5shim','http://html5shim.googlecode.com/svn/trunk/html5.js');
	Asset::container('head')->add('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js');
	Asset::container('head')->add('sc', 'css/scrabbleclub.css');


#	Asset::add('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js');
	Asset::add('bootstrap', 'js/bootstrap.min.js', 'jquery');
	Asset::add('app', 'js/scrabbleclub.min.js', 'jquery');


	$view->nest('header', 'partials.header');
	$view->nest('footer', 'partials.footer');
	$view->nest('flashes', 'partials.flashes');

});


View::composer('partials.header', function($view)
{

	if($user = Auth::user()) {
		$view->nest('authbox', 'partials.header.auth_info', array(
			'user' => $user
		));
	} else {
		$view->nest('authbox', 'partials.header.auth_form');
	}

});
