<?php

namespace Tests\Feature;

class UserTest extends BaseTest
{
	protected $id = -1;

	public function testCreate()
	{
		$params = [
			'email' => 'test_seller@gmail.com',
			'password' => '123456',
			'role' => 'sale',
			'id' => $this->id
		];
		$response = $this->withRoot()
			->post('/users/create', $params);

		$response->assertStatus(201);

		return $params;
	}

	/**
	 * @depends testCreate
	 */
	public function testLoginByEmail($params)
	{
		$response = $this->post('login/email', $params);
		$response->assertStatus(201);
		$token = $response->decodeResponseJson()['token'];

		return $token;
	}

	/**
	 * @depends testLoginByEmail
	 */
	public function testGetProfile($token)
	{
		$response = $this->withToken($token)
			->post('user/profile');

		$response->assertStatus(200);
	}

	/**
	 * @depends testLoginByEmail
	 */
	public function testUpdate($token)
	{
		$response = $this->withToken($token)
			->post('user/update', ['name' => '']);
		$response->assertStatus(201);
	}

	/**
	 * @depends testLoginByEmail
	 */
	public function testDelete()
	{
		$response = $this->withRoot()
			->post('users/delete', ['id' => $this->id]);

		$response->assertStatus(201);
	}
}
