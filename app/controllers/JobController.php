<?php

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage as AMQPMessage;


class JobController extends \BaseController {

	/**
	 * Return new job.
	 *
	 * @return Response
	 */
	public function index()
	{
		$connection = new AMQPConnection(
			Config::get('job.host'),
			Config::get('job.port'),
			Config::get('job.user'),
			Config::get('job.password'));
		$channel = $connection->channel();

		$message =  $channel->basic_get('feed', true);
		if ($message) {
			return $message->body;
		}
		return "{}";

	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
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
