<?php


class UserTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('sites')->delete();
		Site::create([
			'username' => 'admin',
			'password' => 'password',
			'role' => 'Administrator'
		]);
		Site::create([
			'username' => 'producer',
			'password' => 'password',
			'role' => 'Producer'
		]);

	}

}
