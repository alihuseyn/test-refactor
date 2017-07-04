<?php

namespace App\Http\Middleware;

use App\Http\Controllers\API;
use Closure;
use Illuminate\Support\Facades\Response;

class PermissionMiddleware
{
    /**
     * Check whether a user has permission to do operation
     * on the given endpoint
     * @param $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request, Closure $next)
    {
        $profile = $request->user->profile;
        $path = explode('/', explode('api/v1/', $request->path())[1])[0]; // endpoint
        if($profile == 'USER') {
            if($path != 'value') {
                // return permission error code with http response code
                return response(API::prettyResponse(API::getErrorContent('ERR-002')), 403)
                    ->header('Content-Type', 'application/json ; charset=utf-8');
            }
        } else if($profile == 'AGENT'){
            if(!in_array($path, ['waiting', 'translate'])) {
                // return permission error code with http response code
                return response(API::prettyResponse(API::getErrorContent('ERR-002')), 403)
                    ->header('Content-Type', 'application/json ; charset=utf-8');
            }
        }
        // continue operation
        return $next($request);
    }
}