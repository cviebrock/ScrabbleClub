<?php

class Home_Controller extends Base_Controller {

	/*
	|--------------------------------------------------------------------------
	| The Default Controller
	|--------------------------------------------------------------------------
	|
	| Instead of using RESTful routes and anonymous functions, you might wish
	| to use controllers to organize your application API. You'll love them.
	|
	| This controller responds to URIs beginning with "home", and it also
	| serves as the default controller for the application, meaning it
	| handles requests to the root of the application.
	|
	| You can respond to GET requests to "/home/profile" like so:
	|
	|		public function action_profile()
	|		{
	|			return "This is your profile!";
	|		}
	|
	| Any extra segments are passed to the method as parameters:
	|
	|		public function action_profile($id)
	|		{
	|			return "This is the profile for user {$id}.";
	|		}
	|
	*/

	public function get_index()
	{

		$homepage = Cache::sear('page.home', function() {
			$homepage = Page::load('home');
			return $homepage->render;
		}, 86400 );

		$news = News::where('active','=',true)
			->order_by('date','desc')
			->take(3)
			->get();


		// SIDEBAR INFO

		$query = DB::query('SELECT date
			FROM games
			ORDER BY date DESC
			LIMIT 1');

		if (count($query)) {

			$date = $query[0]->date;

			$temp = DB::query('SELECT
				COUNT(g.id)/2 AS total_games,
				COUNT(DISTINCT g.player_id) AS total_players,
				AVG(g.player_score) AS average_score
				FROM games g
				WHERE g.date=?',
				$date
			);
			$sidebar = (array)$temp[0];

			$sidebar['bingos'] = Bingo::with('player')
				->where('date','=',$date)
				->order_by('score','desc')
				->take(5)
				->get();

			$sidebar['high_scores'] = Game::with('player')
				->where('date','=',$date)
				->order_by('player_score','desc')
				->take(5)
				->get();

			$sidebar['ratings'] = Rating::with('player')
				->select(array(
					'*',
					DB::raw('CAST(ending_rating AS signed) - CAST(starting_rating AS signed) AS delta')
				))
				->where('date','=',$date)
				->order_by('delta', 'desc')
				// ->order_by('performance_rating', 'desc')
				->take(5)
				->get();



		} else {
			$date = $sidebar = false;
		}

		$this->layout->with('title', 'Home')
			->nest('content', 'home.index', array(
				'homepage' => $homepage,
				'date'     => $date,
				'sidebar'  => $sidebar,
				'news'     => $news,
			))
			->nest('fb', 'partials.facebook', array(
				'fb' => Config::get('facebook')
			));


	}



	public function get_login()
	{

		if ( Auth::check() ) {
			return Redirect::to_action('home')
				->with('info', 'You are already signed in!');
		}


		if ( !($auth = Session::get('auth')) ) {
			$auth = array(
				'username' => '',
				'password' => '',
				'remember' => false,
				'errors'   => new Laravel\Messages,
			);
		}

		$this->layout->with('title', 'Login')
			->nest('content', 'home.login', array(
					'auth' => $auth,
			));
	}

	public function post_login()
	{

		$auth = array(
			'username' => Input::get('username'),
			'password' => Input::get('password'),
			'remember' => (Input::get('remember', 'no') == 'yes'),
		);


		if (Auth::attempt( $auth ) ) {

			return Redirect::to_action('home')
				->with('success', 'Welcome back, '.Auth::user()->fullname.'!');

		}

		$auth['errors'] = new Laravel\Messages;
		$auth['errors']->add('username', 'Username or password are wrong, please try again.');

		return Redirect::to_action('login')
			->with('error-autofade', 'Authentication failed')
			->with('auth', $auth);

	}


	function get_logout()
	{
		Session::forget('auth');
		$redirect = Redirect::home();
		if (Auth::check())
		{
			$name = Auth::user()->fullname;
			Auth::logout();
			$redirect->with('success', 'Bye, ' . $name . '.  You are now signed out.');
		}
		return $redirect;
	}


	function get_download($id, $localfile, $filename) {

		if ( $resource = Resource::find($id) ) {
			if ( $resource->is_file() && $resource->localfile==$localfile && $resource->filename==$filename) {
				return Response::download( path('uploads').$resource->localfile, $resource->filename, array(
					'Content-Type' => File::mime(File::extension($resource->filename)),
				));
			}

		}
		return Response::error('404');
	}


}
