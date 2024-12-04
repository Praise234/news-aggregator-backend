<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules;


class ChangePasswordController extends Controller
{
    /**
     * Handle the password change request.
     */
    public function changePassword(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', Rules\Password::defaults(), 'confirmed'], // Ensure the password is confirmed
        ]);

        $user = Auth::user();

        // Check if the current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Current password does not match.',
            ], 400);
        }

        // Update the new password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json([
            'message' => 'Password changed successfully.',
        ], 200);
    }
}
