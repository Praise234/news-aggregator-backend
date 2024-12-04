<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class NewPasswordController extends Controller
{
    /**
     * Handle the password reset request.
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:users,email'],
            'token' => ['required'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Verify the reset token and email
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            // ->where('token', Hash::make($request->token))
            ->first();

       // Check if the record exists and verify the token
       if (!$passwordReset || !Hash::check($request->token, $passwordReset->token)) {
            throw ValidationException::withMessages([
                'token' => ['Invalid or expired token.'],
            ]);
        }

        // Update the user's password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the password reset record to invalidate the token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json(['message' => 'Password reset successfully.'], 200);
    }
}
