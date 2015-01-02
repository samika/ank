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

	public function diff($id, $versions = [0,1])
	{
		$postVersions = [];
		$post = Post::find($id);
		$tmp = PostVersion::where('post', '=', $id)->get();
		foreach ($tmp as $postVersion) {
			$postVersions[] = $postVersion;
		}
		$site = Site::find($post->site);
		$differ = new Differ();

		$diff = $differ->diff($postVersions[$versions[0]]->content, $postVersions[$versions[1]]->content);
		$lines = explode("\n", $diff);
		$diff = '';
		foreach ($lines as $i => $line) {
			$span = '';
			if (strpos($line,'+') === 0) {
				$span = '<span class="bg-success">';
			} elseif (strpos($line,'-') === 0) {
				$span = '<span class="bg-danger">';
			}
			$diff .= $span.$line;
			if ($span) {
				$diff .= '</span>';
			}
			$diff .= PHP_EOL;
		}

		return View::make('post', [
			'post' => $post,
			'site'	=> $site,
			'diff'  => $diff,
		]);

	}
}
