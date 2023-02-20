<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class HydraController extends Controller {
    public function hydra(): Response {
        return response([
            'message' => 'Welcome to Hydra, the zero config API boilerplate with roles and abilities for Laravel Sanctum. Please visit https://hasinhayder.github.io/hydra to know more.',
        ]);
    }

    public function version(): Response {
        return response([
            'version' => config('hydra.version'),
        ]);
    }
}
