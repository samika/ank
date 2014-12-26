<?php

return [
	'secret' => 'SomeSecretPasswordWouldbeNicefor', #dev one..
	'ttl' => 1440,
	'algo' => 'HS256',
	'user' => 'User',
	'identifier' => '_id',
	'provider' => 'Tymon\JWTAuth\Providers\FirebaseProvider'
];
