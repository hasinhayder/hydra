<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserTest extends TestCase {
    /**
     * A basic feature test example.
     */
    private $token;

    private $user_id;

    public function test_new_user_registration(): void {
        $response = $this->postJson('/api/users', [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'test',
        ]);

        $response
            ->assertJson(
                fn (AssertableJson $json) => $json->where('email', 'test@test.com')
                    ->where('name', 'Test User')
                    ->etc()
            );
    }

    public function test_existing_email_registration_fail(): void {
        $response = $this->postJson('/api/users', [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'test',
        ]);

        $response
            ->assertJson(
                fn (AssertableJson $json) => $json->where('error', 1)
                    ->where('message', 'user already exists')
            );
    }

    public function test_new_user_login(): void {
        $response = $this->postJson('/api/login', [
            'email' => 'test@test.com',
            'password' => 'test',
        ]);

        $data = json_decode($response->getContent());
        $this->token = $data->token;
        $this->user_id = $data->id;

        $response
            ->assertJson(
                fn (AssertableJson $json) => $json->where('error', 0)
                    ->has('token')
                    ->has('id')
            );
    }

    public function test_new_user_failed_login(): void {
        $response = $this->postJson('/api/login', [
            'email' => 'test@test.com',
            'password' => 'testX',
        ]);

        $response
            ->assertJson(
                fn (AssertableJson $json) => $json->where('error', 1)
                    ->has('message')
            );
    }

    public function test_new_user_name_update_with_user_token(): void {
        $response = $this->postJson('/api/login', [
            'email' => 'test@test.com',
            'password' => 'test',
        ]);

        $data = json_decode($response->getContent());
        $this->token = $data->token;
        $this->user_id = $data->id;

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->put("/api/users/{$this->user_id}", [
                'name' => 'Mini Me',
            ]);

        $response
            ->assertJson(
                fn (AssertableJson $json) => $json->where('name', 'Mini Me')
                    ->etc()
            );
    }

    public function test_new_user_name_update_with_admin_token(): void {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@hydra.project',
            'password' => 'hydra',
        ]);

        $data = json_decode($response->getContent());
        $this->token = $data->token;
        $this->user_id = $data->id;

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->put("/api/users/{$this->user_id}", [
                'name' => 'Mini Me',
            ]);

        $response
            ->assertJson(
                fn (AssertableJson $json) => $json->where('name', 'Mini Me')
                    ->etc()
            );
    }

    public function test_new_user_destroy_as_user_should_fail(): void {
        $response = $this->postJson('/api/login', [
            'email' => 'test@test.com',
            'password' => 'test',
        ]);

        $data = json_decode($response->getContent());
        $this->token = $data->token;
        $this->user_id = $data->id;

        $target = User::where('email', 'test@test.com')->first();

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->delete("/api/users/{$target->id}");

        $response
            ->assertJson(
                fn (AssertableJson $json) => $json->where('error', 1)
                    ->has('message')
            );
    }

    public function test_new_user_destroy_as_admin(): void {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@hydra.project',
            'password' => 'hydra',
        ]);

        $data = json_decode($response->getContent());
        $this->token = $data->token;
        $this->user_id = $data->id;

        $target = User::where('email', 'test@test.com')->first();

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->delete("/api/users/{$target->id}");

        $response
            ->assertJson(
                fn (AssertableJson $json) => $json->where('error', 0)
                    ->where('message', 'user deleted')
            );
    }

    public function test_delete_admin_user_if_multiple_admins_are_present(): void {
        $newAdminUser = User::create([
            'name' => 'Test Admin',
            'password' => Hash::make('abcd'),
            'email' => 'testadmin@test.com',
        ]);

        $adminRole = Role::find(1);

        $newAdminUser->roles()->attach($adminRole);

        $response = $this->postJson('/api/login', [
            'email' => 'admin@hydra.project',
            'password' => 'hydra',
        ]);

        $data = json_decode($response->getContent());
        $this->token = $data->token;
        $this->user_id = $data->id;

        $target = User::where('email', 'testadmin@test.com')->first();

        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->delete("/api/users/{$target->id}");

        $response
            ->assertJson(
                fn (AssertableJson $json) => $json->where('error', 0)
                    ->where('message', 'user deleted')
            );
    }
}
