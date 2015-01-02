<?php

class WebController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$sites = Site::whereNotNull('lastUpdate')->get();
		return View::make('index', ['sites' => $sites]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function site($id)
	{
		$site = Site::find($id);
		if (!$site) {
			return Response::make('Site not found.',404);
		}

		$posts = Post::where('site', '=', $site->_id)->get();
		return View::make('index', ['posts' => $posts]);
	}


}
