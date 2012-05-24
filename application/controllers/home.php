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
		$this->layout->with('title', 'Home')
			->nest('content', 'home.index');
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
				->with('success', 'Welcome back, '.Auth::user()->fullname().'!');

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
			$name = Auth::user()->fullname();
			Auth::logout();
			$redirect->with('success', 'Bye, ' . $name . '.  You are now signed out.');
		}
		return $redirect;
	}


}