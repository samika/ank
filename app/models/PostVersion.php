<?php

use Jenssegers\Mongodb\Model as Eloquent;

class PostVersion extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'postVersion';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [];

	protected $connection = 'mongodb';

	protected static $unguarded = true;

	protected $collection = 'postVersion';

	protected $dates = ['storedAt'];
}
