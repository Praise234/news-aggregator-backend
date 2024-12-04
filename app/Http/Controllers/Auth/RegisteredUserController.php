<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Services\UserService;
use Illuminate\Support\Facades\Log;

class RegisteredUserController extends Controller
{

    protected $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Log the incoming request
            Log::info('Registration Request', $request->all());
            
            // Validate Inputs
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            // Create to new user
            $user = $this->userService->createUser($request->all());

            // Trigger the Registered event
            event(new Registered($user));


            return response()->json([
                'message' => 'User registered successfully.',
                'user' => $user,
            ], 201);

        } catch (\Exception $e) {
            Log::error('Registration Error', ['error' => $e->getMessage()]);

           

            return response()->json([
                'message' => 'Registration failed.',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
