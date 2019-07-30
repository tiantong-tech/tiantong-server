<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
	public function run()
	{
		$this->createRoot();
		if (env('APP_ENV') === 'local') {
			$this->createTestAdmin();
		}
	}

	public function createRoot()
	{
		$username = env('ROOT_USERNAME', 'root');
		$password = env('ROOT_PASSWORD', '123456');

		$user = new User;
		$user->type = 'root';
		$user->name = 'root';
		$user->username = $username;
		$user->password = $password;
		$user->email = 'root@tiantong.com';
		$user->save();
	}

	public function createTestAdmin()
	{
		for ($i = 1; $i <= 1; $i++) {
			$user = new User;
			$user->type = 'admin';
			$user->name = "管理员 $i";
			$user->username = "test_admin_$i";
			$user->password = '123456';
			$user->email = "test_admin_$i@tiantong.com";
			$user->save();
		}
	}
}
