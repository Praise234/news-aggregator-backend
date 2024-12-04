<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Routing\Exceptions\InvalidSignatureException;

use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $exception->errors(),
            ], 422);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            \Log::error('MethodNotAllowedHttpException', ['exception' => $exception]);
            return response()->json([
                'message' => 'The HTTP method is not allowed for this route.',
            ], 405);
        }

        if ($exception instanceof InvalidSignatureException) {
            // Log the error (optional)
            \Log::warning('Invalid signature detected.', [
                'url' => $request->fullUrl(),
                'client_ip' => $request->ip(),
            ]);
    
            // Return a custom error response
            return response()->json([
                'message' => 'The provided link is invalid or has expired.',
            ], 403); // 403 Forbidden
        }

        return parent::render($request, $exception);
    }

   

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // Return JSON response for API requests
            return response()->json([
                'message' => 'You are not authenticated.',
            ], 401);
    }


}
