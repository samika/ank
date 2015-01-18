<?php

use SebastianBergmann\Diff\Differ;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

class WebController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$posts = Post::where('modificationCount','>',0)->orderBy('storedAt','desc')->take(20)->get();
		return View::make('index', ['posts' => $posts]);
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

		return View::make('site', [
			'posts' => $posts,
			'site'	=> $site,
			'title' => $site->name . ' | ' . $site->party . ' | '. $site->area,
		]);
	}

	public function search()
	{

		$query = Input::get('q');

		// How gay is this format?
		$search['body']['query']['multi_match']['query'] = $query;
		$search['body']['query']['multi_match']['fields'] = ['siteName', 'content', 'title', 'url'];
		$search['size'] = 50;
		$search['index'] = 'post-version';
		$message = "";
		$resultEs = [];
		
		try {
			$resultEs = Es::search($search);
		} catch (\Exception $e) {
			$message = 'Haku epäonnistui';
		}


		$result = [];
		$uniquePosts = [];

		foreach ($resultEs as $row) {
			if (isset($row['hits'])) {
				foreach ($row['hits'] as $match) {
					if (!in_array($match['_source']['post'], $uniquePosts)) {
						$match['_source']['storedAt'] = $match['_source']['storedAt']['date'];
						$result[] = $match['_source'];
						$uniquePosts[] = $match['_source']['post'];
					}
				}
			}
		}

		$partyColor = Config::get('content.partyColor');
		$chartData = [];
		foreach ($result as $row) {
			if (isset($chartData[$row['party']])) {
				$chartData[$row['party']]['value']++;
			} else {
				$chartData[$row['party']]['value'] = 1;
				$chartData[$row['party']]['label'] = $row['party'];
				$chartData[$row['party']]['color'] = $partyColor[$row['party']];
			}
		}

		return View::make('search', [
			'query'	  => $query,
			'result'  => $result,
			'chartData' => json_encode(array_values($chartData)),
			'message' => $message,
			'count'   => count($result),
			'title'	  => $query,
		]);
	}

	public function viewSitesByArea($area)
	{
		$sites = Site::where('area', '=', $area)->get();
		return View::make('area', [
			'sites' => $sites,
			'area' => $area,
			'title' => $area,
		]);
	}
	public function viewSitesByParty($party)
	{
		$sites = Site::where('party', '=', $party)->get();
		return View::make('party', [
			'sites' => $sites,
			'party' => $party,
			'title' => $party,
		]);
	}


	public function diff($id, $versions = [0,1])
	{
		$post = Post::find($id);
		$postVersions = PostVersion::where('post', '=', $id)->get();
		$diffs = [];
		$old = null;
		foreach ($postVersions as $postVersion) {
			if ($old) {
				$diffs[] = [
					'content' => $this->getDiffMarkup($old->content, $postVersion->content),
					'dates' => [
						'old' => $old->storedAt,
						'new' => $postVersion->storedAt,
					],
				];
			}
			$old = $postVersion;
		}
		$site = Site::find($post->site);

		return View::make('post', [
			'post' => $post,
			'site'	=> $site,
			'diffs'  => $diffs,
			'title' => $site->name . ' | ' . $site->party . ' | '. $site->area . ' | ' . $post->title,
		]);

	}

	public function requeuePost($id) {
		$post = Post::find($id);
		if (!$post) {
			App::abort(404);
		}
		$site = Site::find($post->site);
		if (!$site) {
			App::abort(404);
		}

		$post->nextCheckAt ? $next = $post->nextCheckAt : $next = new DateTime();
		if ($next < new Datetime('+30 minutes') && $next > new Datetime('-30 minutes')) {
			return Redirect::to('/site/'. $post->site . '/')->with('message', 'Ei lisätty jonoon')->with('success', false);
		}

		$post->nextCheckAt = new \DateTime('-1 seconds');;
		// We do not increase the count for purpose.
		$post->update();

		$connection = new AMQPConnection(
			Config::get('job.host'),
			Config::get('job.port'),
			Config::get('job.user'),
			Config::get('job.password'));

		$channel = $connection->channel();
		$channel->queue_declare('post', false, false, false, false);

		$message = new AMQPMessage(
			json_encode(
				[
					'url' => $post->url,
					'checksum' => $post->checksum,
					'post' => $post->_id,
					'site' => $post->site,
					'xpath' => $site->xpath,
				]
			));

		$channel->basic_publish($message, '', 'post');
		$channel->close();
		$connection->close();
		return Redirect::to('/site/'. $post->site . '/')->with('message', 'Postaus lisätty tarkastusjonoon')->with('success', true);
	}

	public function addSite()
	{
		$keys = ['url', 'rss', 'name', 'party', 'area', 'source' ];
		$required = ['url', 'name', 'party', 'area', 'source' ];
		$isValid = true;
		$message = Session::get('message');

		$all = Input::only($keys);

		if (Request::isMethod('POST')) {

			foreach ($required as $key) {
				if (!isset($all[$key]) || empty($all[$key])) {
					$isValid = false;
					continue;
				}
			}

			if ($isValid) {
				ProposedSite::create($all);
				return Redirect::to('/add-site/')->with('message', 'Tallennus onnistui.')->with('success', true);
			} else {
				$message = "Tallennus epäonnistui, tarkista tiedot.";
			}
		}

		return View::make('add-site', [
			'proposedSite' => $all,
			'title' => 'Lisää blogi järjestelmään',
			'message' => $message,
			'success' => $isValid,
		]);

	}

	protected function getDiffMarkup($version1, $version2)
	{
		$differ = new Differ();

		$diff = $differ->diff($version1, $version2);
		$lines = explode("\n", $diff);
		$diff = '';
		foreach ($lines as $i => $line) {
			$span = '';
			if (strpos($line,"+") === 0) {
				$span = '<span class="bg-success">';
			} elseif (strpos($line,"-") === 0) {
				$span = '<span class="bg-danger">';
			}
			$diff .= $span.$line;
			if ($span) {
				$diff .= '</span>';
			}
			$diff .= PHP_EOL;
		}
		return $diff;
	}


}
