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
		die('Huu.');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$postVersion = null;
		// TODO: this feels stupid.. find out is there better way to do this.
		$jsonString = Request::instance()->getContent();
		if (!empty($jsonString)) {
			$assoc = json_decode($jsonString, true);
			if ($assoc) {
				$post = new PostVersion($assoc);
			}
		}

		if (!$postVersion || $postVersion->checksum !== sha1($postVersion->rawBody)) {
			App::abort(400);
		}
		if (PostVersion::where('url','=',$postVersion->url)
				->where('checksum', '=', $postVersion->checksum)->count() !== 0) {
			App::abort(208);
		}

		$postVersion->storedAt = new \DateTime();
		$postVersion->updatedAt = null;
		$postVersion->modificationCount = 0;

		//Fix this when we have authentication middleware
		$post->producedBy = 'admin';

		$postVersion->save();
		return Response::make('', 201);
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
