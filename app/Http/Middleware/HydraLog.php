<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HydraLog {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next) {
        return $next($request);
    }

    public function terminate($request, $response) {
        Log::info("\n\n".str_repeat('=', 100)."\n\n");
        Log::debug('app.route', ['route'=>$request->route()]);
        Log::debug('app.headers', ['headers'=>$request->headers]);
        Log::debug('app.requests', ['request' => $request->all()]);
        Log::debug('app.response', ['response' => $response]);
        Log::info("\n\n".str_repeat('=', 100)."\n\n");
    }
}
