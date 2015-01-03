<?php

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PostCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'post:add';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Add posts to queue';

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

		$updateDt = new DateTime();
		$now = new DateTime();

		$posts = Post::whereNotNull('url')
			->where('nextCheckAt', '<', $updateDt)
			->take(30)
			->orderBy('nextCheckAt')
			->get();

		print $posts->count() . ' Entries will be added to queue' . PHP_EOL;

		$connection = new AMQPConnection(
			Config::get('job.host'),
			Config::get('job.port'),
			Config::get('job.user'),
			Config::get('job.password'));

		$channel = $connection->channel();
		$channel->queue_declare('post', false, false, false, false);

		/** @var $site Site */
		foreach ($posts as $post) {
			$site = Site::find($post->site);
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
			$post->lastUpdate = $now;
			$minutes = pow(5, max([1,min([11, $post->checkCount])]));
			$post->nextCheckAt = new \DateTime("+{$minutes} minutes");
			$post->update();
		}
		$channel->close();
		$connection->close();
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
