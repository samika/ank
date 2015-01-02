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

		$message = Input::get('message', '');
		$success = Input::get('message', true);

		$viewParameters = [
			'site' => $site,
			'message' => $message,
			'success' => $success,
		];


		return View::make('admin/edit-site', $viewParameters);
	}

	public function submitSite()
	{
		$data = Input::only(['name', 'area', 'party', 'area', 'number', 'url', 'rssUrl','xpath', 'platform']);
		$id = Input::get('_id', null);
		if ($id) {
			$site = Site::find($id);
			$site->update($data);
			$site->save();
		} else {
			$site = Site::create($data);
		}
		return Redirect::to('/admin/site/' . $site->_id)->with('message', 'Tallennus onnistui')->with('success', true);

	}

}
