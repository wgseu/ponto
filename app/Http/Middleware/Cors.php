<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $addHeader = $response instanceof JsonResponse ?
            [$response, 'header'] :
            [$response->headers, 'set'];
        call_user_func($addHeader, 'Access-Control-Allow-Origin', '*');
        call_user_func($addHeader, 'Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE');
        call_user_func($addHeader, 'Access-Control-Allow-Headers', implode(
            ', ',
            ['X-Token-Auth', 'X-Requested-With', 'Content-Type', 'Authorization']
        ));
        call_user_func($addHeader, 'Access-Control-Expose-Headers', 'Content-Length, Content-Range');
        return $response;
    }
}
