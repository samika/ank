<?php

class WebAdminController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$sites = Site::orderBy('name')->get();
		return View::make('admin/index', ['sites' => $sites]);
	}

}
