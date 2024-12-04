<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Notifications\CustomVerifyEmail;


class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email is verified already.',
            ], 200);
        }

        // $request->user()->sendEmailVerificationNotification();

        // Send the custom email verification notification
        $request->user()->notify(new CustomVerifyEmail());

        return response()->json(['status' => 'verification-link-sent']);
    }
}
