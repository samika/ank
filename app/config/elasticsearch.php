<?php

use Monolog\Logger;

return [
    'hosts' => [
        'search.dev.vaalikone.eu:9200',
    ],
    'logPath' => '/tmp/elastic.log',
    'logLevel' => Logger::ERROR
];