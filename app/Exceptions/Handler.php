<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Sentry\Laravel\Integration;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpFoundation\Response;

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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->renderable(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'code'      => 401,
                    'success'   => false,
                    'message'   => "Not authenticated",
                    'data'      => null,
                ], 401);
            }
        });

        $this->renderable(function (ThrottleRequestsException $exception, $request) {
            return response()->json(
                [
                    'code' => Response::HTTP_TOO_MANY_REQUESTS, // 429
                    'success' => false,
                    'message' => 'Maaf, server sedang sibuk. Silakan coba lagi dalam beberapa saat.',
                    'data' => null
                ],
                Response::HTTP_TOO_MANY_REQUESTS
            );
        });

        $this->reportable(function (\Throwable $e) {
            Integration::captureUnhandledException($e);
        });
    }
}
