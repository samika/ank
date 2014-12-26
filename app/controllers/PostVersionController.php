<?php

class PostVersionController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$PostVersions = PostVersion::all();

		return Response::json($PostVersions);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		PostVersion::updateOrCreate(Request::all());
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		PostVersion::updateOrCreate(Request::all());
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$PostVersion = PostVersion::find($id);
		if (!$PostVersion) {
			App::abort(404,'PostVersion not found.');
		}

		return Response::json($PostVersion);

	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		die('What is this for?');
	}


	/**
	 *
 	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		// There should not be any use case to update version.
		App::abort('304');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
