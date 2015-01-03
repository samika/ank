<?php

use Jenssegers\Mongodb\Model as Eloquent;

class ProposedSite extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'site';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [];

	protected $connection = 'mongodb';

	protected static $unguarded = true;

	protected $collection = 'proposedSite';

	protected $dates = ['storedAt', ];

}
