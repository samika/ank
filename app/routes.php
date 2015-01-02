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

Route::when('/api/v1/feedjob', 'authProducer');

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
	if (!$token = JWTAuth::attempt($credentials)) {
		return Response::make('' ,401);
	}
	return Response::json(compact('token'));
});