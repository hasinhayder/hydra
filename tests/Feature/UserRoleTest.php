<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserRoleTest extends TestCase
{
    public function test_user_role_is_present()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@hydra.project',
            'password' => 'hydra',
        ]);

        $data = json_decode($response->getContent());
        $this->token = $data->token;
        $this->user_id = $data->id;


        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->get("/api/users/1/roles");

        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has(
                    'roles.0',
                    fn ($json) =>
                    $json->where('id', 1)
                        ->where('name', 'Administrator')
                        ->where('slug', 'admin')
                        ->etc()
                )->etc()
            );
    }

    public function test_assign_role_to_a_user()
    {
        $newUser = User::create([
            'name' => 'Test User',
            'password' => Hash::make('abcd'),
            'email' => 'testuser@hydra.project',
        ]);


        $response = $this->postJson('/api/login', [
            'email' => 'admin@hydra.project',
            'password' => 'hydra',
        ]);

        $data = json_decode($response->getContent());
        $this->token = $data->token;
        $this->user_id = $data->id;


        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->post("/api/users/{$newUser->id}/roles", ['role_id' => 3]); //assign customer role

        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has(
                    'roles.0',
                    fn ($json) =>
                    $json->where('id', 3)
                        ->where('name', 'Customer')
                        ->where('slug', 'customer')
                        ->etc()
                )->etc()
            );

        $newUser->delete();
    }

    public function test_assign_role_multiple_times_to_a_user_should_fail()
    {
        $newUser = User::create([
            'name' => 'Test User',
            'password' => Hash::make('abcd'),
            'email' => 'testuser@hydra.project',
        ]);


        $response = $this->postJson('/api/login', [
            'email' => 'admin@hydra.project',
            'password' => 'hydra',
        ]);

        $data = json_decode($response->getContent());
        $this->token = $data->token;
        $this->user_id = $data->id;


        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->post("/api/users/{$newUser->id}/roles", ['role_id' => 3]); //assign customer role

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->post("/api/users/{$newUser->id}/roles", ['role_id' => 3]); //again assign customer role

        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has(
                    'roles',
                    fn ($json) =>
                    $json->has(1)->etc() //only one role
                )->etc()
            );

        $newUser->delete();
    }

    public function test_assign_multiple_roles_to_a_user()
    {
        $newUser = User::create([
            'name' => 'Test User',
            'password' => Hash::make('abcd'),
            'email' => 'testuser@hydra.project',
        ]);


        $response = $this->postJson('/api/login', [
            'email' => 'admin@hydra.project',
            'password' => 'hydra',
        ]);

        $data = json_decode($response->getContent());
        $this->token = $data->token;
        $this->user_id = $data->id;


        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->post("/api/users/{$newUser->id}/roles", ['role_id' => 2]); //assign customer role

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->post("/api/users/{$newUser->id}/roles", ['role_id' => 3]); //again assign customer role

        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has(
                    'roles',
                    fn ($json) =>
                    $json->has(2)->etc() //only one role
                )->etc()
            );

        $newUser->delete();
    }

    public function test_delete_role_from_a_user()
    {
        $newUser = User::create([
            'name' => 'Test User',
            'password' => Hash::make('abcd'),
            'email' => 'testuser@hydra.project',
        ]);


        $response = $this->postJson('/api/login', [
            'email' => 'admin@hydra.project',
            'password' => 'hydra',
        ]);

        $data = json_decode($response->getContent());
        $this->token = $data->token;
        $this->user_id = $data->id;


        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->post("/api/users/{$newUser->id}/roles", ['role_id' => 2]); //assign customer role

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->post("/api/users/{$newUser->id}/roles", ['role_id' => 3]); //again assign customer role

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->delete("/api/users/{$newUser->id}/roles/3"); //delete

        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has(
                    'roles',
                    fn ($json) =>
                    $json->has(1)->etc() //only one role
                )->etc()
            );

        $newUser->delete();
    }

    public function test_delete_all_roles_from_a_user()
    {
        $newUser = User::create([
            'name' => 'Test User',
            'password' => Hash::make('abcd'),
            'email' => 'testuser@hydra.project',
        ]);


        $response = $this->postJson('/api/login', [
            'email' => 'admin@hydra.project',
            'password' => 'hydra',
        ]);

        $data = json_decode($response->getContent());
        $this->token = $data->token;
        $this->user_id = $data->id;


        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->post("/api/users/{$newUser->id}/roles", ['role_id' => 2]); //assign customer role

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->post("/api/users/{$newUser->id}/roles", ['role_id' => 3]); //again assign customer role

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->delete("/api/users/{$newUser->id}/roles/3"); //delete
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->delete("/api/users/{$newUser->id}/roles/2"); //delete

        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has(
                    'roles',
                    fn ($json) =>
                    $json->has(0)->etc() //only one role
                )->etc()
            );

        $newUser->delete();
    }
}
