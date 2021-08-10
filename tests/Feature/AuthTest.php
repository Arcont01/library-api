<?php

namespace Tests\Feature;

use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function login_test()
    {
        $response = $this->post('/api/login', ['email' => 'test@test.com', 'password' => 'password']);
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function logout_test()
    {
        $response = $this->post('/api/login', ['email' => 'test@test.com', 'password' => 'password']);

        $token = json_decode($response->getContent())->data->token;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->post('/api/logout');
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function register_test()
    {
        $faker = Factory::create();
        $password = $faker->password(8);
        $response = $this->post('/api/register', ['name' => $faker->name() , 'email' => $faker->safeEmail(), 'password' => $password, 'password_confirmation' => $password]);
        $response->assertStatus(200);
    }
}
