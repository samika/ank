<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('post', function(Blueprint $table) {
			$table->string('site');
			$table->dateTime('storedAt');
			$table->dateTime('lastCheckAt');
			$table->integer('modificationCount');
			$table->string('checksum');
			$table->string('title');
			$table->string('content');
			$table->string('url')->unique();
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
