<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\Response;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;

use ErrorException;
use RuntimeException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if (env('APP_DEBUG', false)) {
            return parent::render($request, $exception);
        }

        if ($exception instanceof NotFoundHttpException) {
            error_log($exception->getMessage());

            return response()->json([
                'msg' => 'Oops! It Seems like you are lost...🤔'
            ], Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            error_log($exception->getMessage());

            return response()->json([
                'msg' => 'Method Not Allowed...🤫'
            ], Response::HTTP_METHOD_NOT_ALLOWED);
        }

        if ($exception instanceof QueryException) {
            error_log($exception->getMessage());

            return response()->json([
                'msg' => 'Internal Server Error...🤯'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if ($exception instanceof RuntimeException) {
            error_log($exception->getMessage());

            return response()->json([
                'msg' => 'Internal Server Error...🤯'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if ($exception instanceof ErrorException) {
            error_log($exception->getMessage());

            return response()->json([
                'msg' => 'Internal Server Error...🤯'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
