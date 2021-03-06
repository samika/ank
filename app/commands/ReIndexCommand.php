<?php

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ReIndexCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'search:reindex';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'ReIndex search engine';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$postVersions = PostVersion::orderBy('storedAt', 'asc')
			->whereNotNull('storedAt')
			->take(1)
			->get();

		$dt = new \DateTime($postVersions[0]->storedAt->format('Y-m-d'));
		$now = new \DateTime();

		while ($dt < $now) {

			$i = 0;
			$end = new \DateTime($dt->format('Y-m-d').' 23:59:59');
			$postVersions = PostVersion::where('storedAt', '<=', $end)
				->where('storedAt', '>=', $dt)->get();

			$dt->add(new DateInterval('P1D'));

			foreach ($postVersions as $postVersion) {
				$this->info('Indexing ' . $dt->format('Y.m.d') . '#' . $i++);
				if (!$postVersion->post) {
					$this->info("Skipping" . $postVersion);
					continue;
				}
				$post = Post::find($postVersion->post);
				$site = Site::find($post->site);
				$search = [
					'body' => [
						'siteName' => $site->name,
						'title' => $postVersion->title,
						'content' => $postVersion->content,
						'rawContent' => $postVersion->rawContent,
						'post' => $post->_id,
						'site' => $site->_id,
						'storedAt' => $postVersion->storedAt,
						'url' => $post->url,
						'area' => $site->area,
						'party' => $site->party,
					],
					'index' => 'post-version',
					'type' => 'post-version',
					'id' => $postVersion->_id,
				];

				try {
					Es::index($search);
				} catch (\Exception $e) {
					$this->info($e);
				}
			}
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [];

		return array(
			array('run', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [];
		return array(
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
