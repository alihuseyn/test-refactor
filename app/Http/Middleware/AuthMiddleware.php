<?php

namespace App\Http\Middleware;

use App\Http\Controllers\API;
use App\Model\User;
use Closure;
use Illuminate\Support\Facades\Response;

class AuthMiddleware
{
    /**
     * Check for user information whether user info exists or not
     * to continue operation
     * @param $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request, Closure $next)
    {
        $user = User::fetch(API::getAuthInfo($request));
        if (!is_null($user) && !empty($user)) {
            $request->merge(['user' => $user]);
            return $next($request);
        }
        // if not exists then return error message to back as a response with http error code
        return response(API::prettyResponse(API::getErrorContent('ERR-001')), 401)
                    ->header('Content-Type', 'application/json ; charset=utf-8');
    }
}
