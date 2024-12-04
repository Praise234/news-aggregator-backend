<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): JsonResponse
    {
        try{
            if ($request->user()->hasVerifiedEmail()) {
                return response()->json([
                    'message' => 'Verification successfully.',
                ], 201);
            }

            if ($request->user()->markEmailAsVerified()) {
                event(new Verified($request->user()));
            }

            return response()->json([
                'message' => 'Verification successfully.',
            ], 201);

        } catch (\Throwable $e) {
            Log::error('Verification Error', ['error' => $e->getMessage()]);

        

            return response()->json([
                'message' => 'Verification failed.',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
