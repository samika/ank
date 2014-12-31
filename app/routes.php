<?php

Route::group(array('prefix' => 'api/v1'), function()
{
	Route::resource('site', 'SiteController');
	Route::resource('post', 'PostController');
	Route::resource('post-version', 'PostVersionController');
	Route::resource('feedjob', 'FeedJobController');
	Route::resource('postjob', 'PostJobController');

});