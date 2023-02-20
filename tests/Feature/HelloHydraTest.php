<?php

namespace Tests\Feature;

use Tests\TestCase;

class HelloHydraTest extends TestCase {
    /**
     * A basic feature test example.
     */
    public function test_hello_hydra(): void {
        $response = $this->get('/api/hydra');

        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => true,
            ]);
    }

    public function test_hydra_version(): void {
        $response = $this->get('/api/hydra/version');

        $response
            ->assertStatus(200)
            ->assertJson([
                'version' => true,
            ]);
    }
}
