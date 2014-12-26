<?php

use Jenssegers\Mongodb\Model as Eloquent;

class Post extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'post';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [];

	protected $connection = 'mongodb';

	protected static $unguarded = true;

	protected $collection = 'post';

}
