<?php


class UserTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('user')->delete();
		User::create([
			'username' => 'admin',
			'password' =>  Hash::make('password'),
			'role' => 'Administrator'
		]);
		User::create([
			'username' => 'producer',
			'password' => Hash::make('password'),
			'role' => 'Producer'
		]);

	}

}
