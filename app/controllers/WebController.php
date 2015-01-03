<?php
use SebastianBergmann\Diff\Differ;

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

		return View::make('site', [
			'posts' => $posts,
			'site'	=> $site,
		]);
	}

	public function search()
	{

		$query = Input::get('q');

		// How gay is this format?
		$search['body']['query']['multi_match']['query'] = $query;
		$search['body']['query']['multi_match']['fields'] = ['siteName', 'content', 'rawContent', 'title', 'url'];
		$search['size'] = 50;
		$search['index'] = 'post-version';
		$result =  [];
		$message = "";

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
						$unigPosts[] = $match['_source']['post'];
					}
				}
			}
		}

		return View::make('search', [
			'query'	  => $query,
			'result'  => $result,
			'message' => $message,
			'count'   => count($result),
		]);
	}

	public function diff($id, $versions = [0,1])
	{
		$postVersions = [];
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
			if (strpos($line,"+\t") === 0) {
				$span = '<span class="bg-success">';
			} elseif (strpos($line,"-\t") === 0) {
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
