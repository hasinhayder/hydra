<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;


class AdminLoginFailTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
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
