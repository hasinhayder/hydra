<?php

namespace Modules;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function transformResponse(string $message,  $data = '', int $statusCode = Response::HTTP_OK): object
    {
        return response()->json((object)[

            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data,

        ], $statusCode);
    }
}
