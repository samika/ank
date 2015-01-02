<?php
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

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
			$data['lastUpdate'] = new \DateTime();
			$site = Site::create($data);

		}
		return Redirect::to('/admin/site/' . $site->_id)->with('message', 'Tallennus onnistui')->with('success', true);

	}

	public function addFeedQueue($id)
	{
		$site = Site::find($id);
		if (!$site) {
			App::abort(404);
		}

		$connection = new AMQPConnection(
			Config::get('job.host'),
			Config::get('job.port'),
			Config::get('job.user'),
			Config::get('job.password'));

		$channel = $connection->channel();
		$channel->queue_declare('feed', false, false, false, false);

		$message = new AMQPMessage(json_encode($site));
		$channel->basic_publish($message, '', 'feed');
		$site->lastUpdate = new \DateTime();
		$site->update();
		$channel->close();
		$connection->close();

		return Redirect::to('/admin/')->with('message', 'Tehtävä lisätty jonoon')->with('success', true);

	}
	public function removeFeedQueue($id)
	{
		$site = Site::find($id);
		if (!$site) {
			App::abort(404);
		}
		$site->lastUpdate = null;
		$site->update();

		$posts = Post::where('site', '=', $site->_id);
		foreach ($posts as $post) {
			$post->nextCheckAt = null;
			$post->update();
		}

		return Redirect::to('/admin/')->with('message', 'Tehtävä poistettu jonosta')->with('success', true);

	}
}
