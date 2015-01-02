<?php

Route::filter('authProducer', function() {
	$token = JWTAuth::getToken();
	if (!$token) {
		return Response::make('', 401);
	}
	/** @var $user User */
	$user = $token->toUser();
	if (!$user || $user->role !== "producer") {
		return Response::make('' ,401);
	}
});

// public site routes
Route::get('/', 'WebController@index');

// Public routes.
Route::get('/api/v1/site', 'SiteController@index');
Route::get('/api/v1/site/{id}', 'SiteController@show');
Route::get('/api/v1/post-version', 'PostController@index');
Route::get('/api/v1/post-version/{id}', 'PostController@show');

// Routes that requires authentication
Route::group(['prefix' => 'api/v1', 'before' => 'authProducer'], function()
{
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
