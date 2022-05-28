<?php

namespace Tests\Feature;

use App\Models\Role;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class RoleTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_list_roles()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@hydra.project',
            'password' => 'hydra',
        ]);

        $data = json_decode($response->getContent());
        $this->token = $data->token;
        $this->user_id = $data->id;


        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->get("/api/roles");

        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has(6)
                    ->first(
                        fn ($json) =>
                        $json->where('id', 1)
                            ->where('name', 'Administrator')
                            ->where('slug', 'admin')
                            ->etc()
                    )
            );
    }

    public function test_update_role_name_as_admin()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@hydra.project',
            'password' => 'hydra',
        ]);

        $data = json_decode($response->getContent());
        $this->token = $data->token;
        $this->user_id = $data->id;


        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->put("/api/roles/4", [
                "name" => "Chief Editor",
            ]);

        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->where('name', 'Chief Editor')
                ->missing('error')
                ->etc()
            );
    }

    public function test_update_role_slug_as_admin()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@hydra.project',
            'password' => 'hydra',
        ]);

        $data = json_decode($response->getContent());
        $this->token = $data->token;
        $this->user_id = $data->id;


        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->put("/api/roles/4", [
                "slug" => "chief-editor",
            ]);

        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->where('slug', 'chief-editor')
                ->missing('error')
                ->etc()
            );
    }

    public function test_update_role_namd_and_slug_as_admin()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@hydra.project',
            'password' => 'hydra',
        ]);

        $data = json_decode($response->getContent());
        $this->token = $data->token;
        $this->user_id = $data->id;


        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->put("/api/roles/4", [
                "name" => "Editor X",
                "slug" => "editor-x",
            ]);

        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->where('name', 'Editor X')
                ->where('slug', 'editor-x')
                ->missing('error')
                ->etc()
            );
    }

    public function test_update_admin_slug_as_admin_should_fail()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@hydra.project',
            'password' => 'hydra',
        ]);

        $data = json_decode($response->getContent());
        $this->token = $data->token;
        $this->user_id = $data->id;


        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->put("/api/roles/1", [
                "slug" => "admin-x",
            ]);

        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json
                ->where('slug', 'admin')
                ->etc()
            );
    }

    public function test_create_new_role_as_admin()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@hydra.project',
            'password' => 'hydra',
        ]);

        $data = json_decode($response->getContent());
        $this->token = $data->token;
        $this->user_id = $data->id;


        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->post("/api/roles", [
                "name" => "New Role",
                "slug" => "new-role",
            ]);

        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->where('name', 'New Role')
                ->where('slug', 'new-role')
                ->missing('error')
                ->etc()
            );
    }

    public function test_duplicate_role_will_not_be_created()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@hydra.project',
            'password' => 'hydra',
        ]);

        $data = json_decode($response->getContent());
        $this->token = $data->token;
        $this->user_id = $data->id;


        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->post("/api/roles", [
                "name" => "New Role",
                "slug" => "new-role",
            ]);

        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->where('error', 1)
                ->etc()
            );
    }

    public function test_delete_role_as_admin()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@hydra.project',
            'password' => 'hydra',
        ]);

        $data = json_decode($response->getContent());
        $this->token = $data->token;
        $this->user_id = $data->id;

        $newRole = Role::where('slug', 'new-role')->first();


        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->delete("/api/roles/{$newRole->id}");

        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->where('error', 0)
                ->has('message')
            );
    }

    public function test_delete_admin_role_should_fail()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@hydra.project',
            'password' => 'hydra',
        ]);

        $data = json_decode($response->getContent());
        $this->token = $data->token;
        $this->user_id = $data->id;

        $newRole = Role::where('slug', 'admin')->first();


        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->delete("/api/roles/{$newRole->id}");

        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->where('error', 1)
                ->has('message')
            );
    }
}
