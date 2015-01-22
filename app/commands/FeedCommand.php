<?php

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class FeedCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'feed:add';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Add Feeds to queue';

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

		$updateDt = new DateTime(Config::get('job.feedUpdateInterval'));
		$now = new DateTime();

		$sites = Site::whereNotNull('rssUrl')
			->where('lastUpdate', '<', $updateDt)
			->orderBy('lastUpdate')
			->take(25)
			->get();

		print $sites->count() . ' Entries will be added to queue' . PHP_EOL;

		$connection = new AMQPConnection(
			Config::get('job.host'),
			Config::get('job.port'),
			Config::get('job.user'),
			Config::get('job.password'));

		$channel = $connection->channel();
		$channel->queue_declare('feed', false, false, false, false);

		/** @var $site Site */
		foreach ($sites as $site) {
			$message = new AMQPMessage(json_encode($site));
			$channel->basic_publish($message, '', 'feed');
			$site->lastUpdate = $now;
			$site->update();
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
