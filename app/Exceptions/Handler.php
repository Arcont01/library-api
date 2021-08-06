<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return JsonResponse|Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
       return $request->is('api*') ? response()->json([
           'status' => 'error',
           'message' => $exception->getMessage()
       ], 401) : redirect()->guest(route('welcome'));
    }

    public function render($request, \Throwable $e)
    {
        if ($e instanceof ModelNotFoundException && $request->is('api*')) {
            return response()->json(['status' => 'error', 'message' => 'Not Found'], 404);
        }

        return parent::render($request, $e);
    }

}
