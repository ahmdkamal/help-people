<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
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
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            $message = $exception->getMessage();
            $errors = $exception->errors();
            $code = 422;
        } elseif ($exception instanceof AuthenticationException) {
            $message = 'Unauthenticated';
            $code = 401;
            $errors = [
                'token' => [$exception->getMessage()]
            ];
        } elseif ($exception instanceof ModelNotFoundException) {
            $message = 'Not Found';
            $code = 404;
            $errors = [
                'route' => ['url is not found']
            ];
        } elseif ($exception instanceof NotFoundHttpException) {
            $message = 'Not Found';
            $code = 404;
            $errors = [
                'route' => ['url is not found']
            ];
        } else {
            $code = 400;
            $message = $exception->getMessage();
            $errors = [
                'failed'=>['Something Went Wrong']

            ];
        }
//        if (env('APP_DEBUG')) {
//            if ($code == 404) {
//                $errors = [
//                    'route' => [$exception->getMessage()]
//                ];
//            }
//            $errors['line'] = $exception->getLine();
//            $errors['trace'] = $exception->getTrace();
//
//        }

        return response()->json([
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}
