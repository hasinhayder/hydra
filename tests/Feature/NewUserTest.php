<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;


class NewUserTest extends TestCase {
    /**
     * A basic feature test example.
     *
     * @return void
     */

    private $token;
    private $user_id;

    public function test_new_user_registration() {
        $response = $this->postJson('/api/users', [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'test'
        ]);

        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->where('email', 'test@test.com')
                    ->where('name', 'Test User')
                    ->etc()
            );
    }

    public function test_existing_email_registration_fail() {
        $response = $this->postJson('/api/users', [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'test'
        ]);

        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->where('error', 1)
                    ->where('message', 'user already exists')
            );
    }

    public function test_new_user_login() {
        $response = $this->postJson('/api/login', [
            'email' => 'test@test.com',
            'password' => 'test'
        ]);

        $data = json_decode($response->getContent());
        $this->token = $data->token;
        echo $this->token;

        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->where('error', 0)
                    ->has('token')
            );
    }

    public function test_new_user_failed_login() {
        $response = $this->postJson('/api/login', [
            'email' => 'test@test.com',
            'password' => 'testX'
        ]);

        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->where('error', 1)
                    ->has('message')
            );
    }

    public function test_new_user_data_update() {
        $response = $this->postJson('/api/login', [
            'email' => 'test@test.com',
            'password' => 'testX'
        ]);

        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->where('error', 1)
                    ->has('message')
            );
    }
}
