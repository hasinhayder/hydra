<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HydraController extends Controller
{
    public function hydra(){
        return response([
            "message"=>"Welcome to Hydra, the zero config API boilerplate with roles and abilities for Laravel Sanctum. Please visit https://hasinhayder.github.io/hydra to know more."
        ]);
    }

    public function version(){
        return response([
            "version"=> env('APP_VERSION')
        ]);
    }
}
