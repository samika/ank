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
			$table->string('name');
			$table->string('party');
			$table->string('area');
			$table->string('number');
			$table->string('url')->unique();
			$table->string('rssUrl')->unique();
			$table->string('rssChecksum');
			$table->string('platform');
			$table->dateTime('lastUpdate');
			$table->string('xpath');
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
