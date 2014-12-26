<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('site', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('party');
			$table->string('area');
			$table->string('number');
			$table->string('url');
			$table->string('rssUrl');
			$table->string('platform');
			$table->string('lastUpdate');
			$table->string('contentSelector');
			$table->boolean('elected');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('site', function(Blueprint $table)
		{
			//
		});
	}

}
