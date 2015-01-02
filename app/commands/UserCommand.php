<?php

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class UserCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'user:add';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Add user';

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
		$user = User::create([
			'username' 	=> $this->option('username'),
			'password' 	=> Hash::make($this->option('password')),
			'role'		=> $this->option('role'),
		]);
		$this->info('User created #'. $user->_id);

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
		return [
			['username', null, InputOption::VALUE_REQUIRED, 'Username.', null],
			['password', null, InputOption::VALUE_REQUIRED, 'Password.', null],
			['role', null, InputOption::VALUE_REQUIRED, 'Role Aministrator|Producer.', null],
		];
	}

}
