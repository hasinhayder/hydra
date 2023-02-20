<?php

namespace Tests\Feature;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AdminLoginTest extends TestCase {
    /**
     * A basic feature test example.
     */
    public function test_admin_login(): void {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@hydra.project',
            'password' => 'hydra',
        ]);

        $response
        ->assertJson(fn (AssertableJson $json) => $json->where('error', 0)
                 ->has('token')
                 ->etc()
        );
    }

    public function test_admin_login_fail(): void {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@hydra.project',
            'password' => 'hydrax',
        ]);

        $response
        ->assertJson(fn (AssertableJson $json) => $json->where('error', 1)
                 ->missing('token')
                 ->has('message')
        );
    }
}
