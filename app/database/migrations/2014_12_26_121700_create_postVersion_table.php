<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostVersionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('postVersion', function(Blueprint $table) {
			$table->string('site');
			$table->string('post');
			$table->dateTime('storedAt');
			$table->string('checksum');
			$table->string('title');
			$table->string('content');
			$table->string('rawContent');
			$table->string('producedBy');
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
