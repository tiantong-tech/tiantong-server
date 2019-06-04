<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class RootSeeder extends Seeder
{
	public function run()
	{
		$user = new User;
		$user->type = 'root';
		$user->username = 'root';
		$user->password = '123456';
		$user->save();
	}
}
