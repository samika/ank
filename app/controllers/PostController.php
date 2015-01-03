<?php

class PostController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$Posts = Post::all();

		return Response::json($Posts);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		print "huh?";
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$post = null;
		// TODO: this feels stupid.. find out is there better way to do this.
		$jsonString = Request::instance()->getContent();
		if (!empty($jsonString)) {
			$assoc = json_decode($jsonString, true);
			if ($assoc) {
				$post = new Post($assoc);
			}
		}

		if (!$post) {
			App::abort(400);
		}
		$site = Site::find($post->site);
		if (!$site) {
			App::abort(400);
		}

		if (Post::where('url','=',$post->url)->count() !== 0) {
			return Response::make('', 208);
		}

		$post->lastCheckAt = null;
		$post->storedAt = new \DateTime();
		$post->nextCheckAt = new \DateTime();
		$post->updatedAt = null;
		$post->modificationCount = 0;
		$post->save();

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
		$Post = Post::find($id);
		if (!$Post) {
			App::abort(404,'Post not found.');
		}

		return Response::json($Post);

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
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$Post = Post::find($id);
		if (!$Post) {
			App::abort(404,'Post not found.');
		}

		// update stuff

		$Post->store();
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
