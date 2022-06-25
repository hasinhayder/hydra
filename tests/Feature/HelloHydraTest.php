<?php

namespace Tests\Feature;

use Tests\TestCase;

class HelloHydraTest extends TestCase {
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_hello_hydra() {
        $response = $this->get('/api/hydra');

        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => true,
            ]);
    }

    public function test_hydra_version() {
        $response = $this->get('/api/hydra/version');

        $response
            ->assertStatus(200)
            ->assertJson([
                'version' => true,
            ]);
    }
}
