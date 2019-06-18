<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
	public function run()
	{
		$user = new User;
		$user->autoGroup = 'root';
		$user->username = 'root';
		$user->password = '123456';
		$user->email = 'root@tiantong.com';
		$user->save();
	}
}
