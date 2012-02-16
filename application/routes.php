<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Simply tell Laravel the HTTP verbs and URIs it should respond to. It is a
| breeze to setup your applications using Laravel's RESTful routing, and it
| is perfectly suited for building both large applications and simple APIs.
| Enjoy the fresh air and simplicity of the framework.
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
|		Route::post('hello, world', function()
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

/*
Route::get('/, home', function()
{
	return View::make('home.index');
});
*/


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
| routes. The built-in "before" and "after" filters are called before and
| after every request to your application, and you may even create other
| filters that can be attached to individual routes.
|
| Let's walk through an example...
|
| First, define a filter:
|
|		Filter::register('filter', function()
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

Route::filter('after', function()
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

View::composer('default', function($view)
{
	Asset::container('head')->add('html5shim','http://html5shim.googlecode.com/svn/trunk/html5.js');
	Asset::container('head')->add('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js');
	Asset::container('head')->add('bootstrap', 'css/bootstrap.css');
#	Asset::container('head')->add('reset', 'css/reset.css');
#	Asset::container('head')->add('style', 'css/style.css');


#	Asset::add('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js');
	Asset::add('bootstrap-transition', 'js/bootstrap-transition.js', 'jquery');
	Asset::add('bootstrap-alert', 'js/bootstrap-alert.js', 'jquery');
	Asset::add('bootstrap-modal', 'js/bootstrap-modal.js', 'jquery');
	Asset::add('bootstrap-dropdown', 'js/bootstrap-dropdown.js', 'jquery');
	Asset::add('bootstrap-scrollspy', 'js/bootstrap-scrollspy.js', 'jquery');
	Asset::add('bootstrap-tab', 'js/bootstrap-tab.js', 'jquery');
	Asset::add('bootstrap-tooltip', 'js/bootstrap-tooltip.js', 'jquery');
	Asset::add('bootstrap-popover', 'js/bootstrap-popover.js', 'jquery');
	Asset::add('bootstrap-button', 'js/bootstrap-button.js', 'jquery');
	Asset::add('bootstrap-collapse', 'js/bootstrap-collapse.js', 'jquery');
	Asset::add('bootstrap-carousel', 'js/bootstrap-carousel.js', 'jquery');
	Asset::add('bootstrap-typeahead', 'js/bootstrap-typeahead.js', 'jquery');
	Asset::add('app', 'js/application.js', 'jquery');


	$view->nest('header', 'partials.header');
	$view->nest('footer', 'partials.footer');
	$view->nest('flashes', 'partials.flashes');

});

Route::get('/', array('as'=>'home', function()
{

	return View::make('default')
		->with('title', 'Home Page')
		->nest('content', 'home.index');
}));

/* other files, kept in routes directory */

require( path('app') .'routes/ajax.php');
require( path('app') .'routes/players.php');
require( path('app') .'routes/admin/games.php');
require( path('app') .'routes/admin/players.php');
