<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;


class AdminLoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_admin_login()
    {
        $response = $this->postJson('/api/login',[
            'email'=>'admin@hydra.project',
            'password'=>'hydra'
        ]);

        $response
        ->assertJson(fn (AssertableJson $json) =>
            $json->where('error', 0)
                 ->has('token')
                 ->etc()
        );
    }

    public function test_admin_login_fail()
    {
        $response = $this->postJson('/api/login',[
            'email'=>'admin@hydra.project',
            'password'=>'hydrax'
        ]);

        $response
        ->assertJson(fn (AssertableJson $json) =>
            $json->where('error', 1)
                 ->missing('token')
                 ->has('message')
        );
    }
}
