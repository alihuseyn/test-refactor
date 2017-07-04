<?php

namespace App\Exceptions;

use App\Http\Controllers\API;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if (empty(explode("api/v1",$request->path())[0])) {
            if ($e instanceof MethodNotAllowedHttpException) {
                return response(API::prettyResponse(API::getErrorContent('ERR-004')), 405)
                        ->header('Content-Type', 'application/json ; charset=utf-8');
            } else if ($e instanceof NotFoundHttpException) {
                return response(API::prettyResponse(API::getErrorContent('ERR-003')), 404)
                        ->header('Content-Type', 'application/json ; charset=utf-8');
            }
        }

        return parent::render($request, $e);
    }
}
