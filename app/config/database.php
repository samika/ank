<?php

return array(

	'fetch' => PDO::FETCH_CLASS,

	'default' => 'mongodb',


	'connections' => array(

		'mongodb' => [
			'driver'   => 'mongodb',
			'host'     => 'localhost',
			'port'     => 27017,
			'database' => 'ank'
		],
		
	),

	'migrations' => 'migrations',

);
