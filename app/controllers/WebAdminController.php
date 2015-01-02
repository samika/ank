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

	/**
	 * Edit site
	 *
	 * @return Response
	 */
	public function editSite($id=null)
	{
		if ($id == null) {
			$site = new Site();
		} else {
			$site = Site::find($id);
		}

		if (!$site) {
			App::abort(404);
		}

		return View::make('admin/edit-site', ['site' => $site]);
	}

}
