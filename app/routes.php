<?php

Route::filter('authProducer', function() {
	/** @var $token Tymon\JWTAuth\JWTAuth */
	try {
		$token = JWTAuth::parseToken();
	} catch(\Exception $e) {
		return Response::make('U' ,401);
	}

	if (!$token) {
		return Response::make('' ,401);
	}
	$user = $token->toUser();
	if (!$user || $user->role !== "Producer") {
		return Response::make('' ,401);
	}
});


// Admin interface routes
Route::get('/login', function()
{
	return View::make('login');
});
Route::post('/login', function()
{
	if (Auth::check()) {
		return;
	}

	$credentials = Input::only('username', 'password');
	$credentials['role'] = 'Administrator';
	if (Auth::attempt($credentials)) {
		return Redirect::intended('admin');
	}

	return View::make('login', ['message' => 'Vituiks meni.']);
});

Route::group(['prefix' => '/admin', 'before' => 'auth'], function() {
	Route::get('/', 'WebAdminController@index');
	Route::get('/site', 'WebAdminController@editSite');
	Route::get('/site/{id}/', 'WebAdminController@editSite');
});


// API Routes that requires authentication
Route::group(['prefix' => 'api/v1', 'before' => 'authProducer'], function()
{
	Route::resource('site', 'SiteController');
	Route::resource('post', 'PostController');
	Route::resource('post-version', 'PostVersionController');
	Route::resource('feedjob', 'FeedJobController');
	Route::resource('postjob', 'PostJobController');
});

Route::post('/api/v1/auth', function() {
	$credentials = Input::only('username', 'password');
	$token = JWTAuth::attempt($credentials);

	if (!$token) {
		return Response::make('' ,401);
	}
	return Response::json(['token' => $token]);
});

// public site routes
Route::get('/', 'WebController@index');
Route::get('/site/{id}/', 'WebController@site');
