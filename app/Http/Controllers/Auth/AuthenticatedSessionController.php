<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Articles;
use App\Models\Preferences;


class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        try {
            // Log the incoming request
            Log::info('Log In Request', $request->all());


            // Authenticate the user
            $request->authenticate();

            // Regenerate session to prevent session fixation
            // $request->session()->regenerate();

            // Get the authenticated user
            $user = Auth::user();

            $token = $user->createToken('auth_token')->plainTextToken;

            // Get distinct authors
            $authors = Articles::select('author')->distinct()->whereNotNull('author')->pluck('author');

            // Get distinct categories
            $categories = Articles::select('category')->distinct()->whereNotNull('category')->pluck('category');

            // Get distinct sources
            $sources = Articles::select('source')->distinct()->whereNotNull('source')->pluck('source');

            // Get User Preferences
            $user_preferences = Preferences::where('user_id', $user->id)->first();


   

            // Return a meaningful response
            return response()->json([
                'message' => 'Login successful.',
                'token' => $token,
                'user' => $user,
                'user_preferences' => $user_preferences,
                'categories' => $categories,
                'sources' => $sources,
                'authors' => $authors,
            ], 200); // HTTP 200 OK

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Log In Error', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'Log In failed.',
                'error' => $e->errors(),
            ], 400);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        // Log out the user
        // Auth::guard('web')->logout();

        // Invalidate the session and regenerate CSRF token
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();

        try {
            // Revoke the current user's token
            $request->user()->currentAccessToken()->delete();
    
            // Return a success response
            return response()->json(['message' => 'Logged out successfully.'], 200);
        } catch (\Exception $e) {
            // Log the error (optional)
            \Log::error('Error logging out: ' . $e->getMessage());
    
            // Return an error response
            return response()->json([
                'message' => 'Failed to log out.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
