<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;

use App\Http\Requests\LoginRequest;
use App\Models\User;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends BaseController
{
    /**
     * Handle a registration request.
     */
    public function register(Request $request)
    {
        // Will implement later if required
    }

    /**
     * Handle a login request.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid login details' ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        $cookie = cookie(
            'auth_token',   // Name of the cookie
            $token,         // The token value
            60 * 24 * 7,    // Expires in 7 days (in minutes)
            '/',            // Path
            null,           // Domain (null means current domain)
            true,           // Secure (only send over HTTPS)
            true            // HttpOnly (cannot be accessed by JavaScript)
        );

        return response()->json(['message' => 'Login successful', 'user' => $user, 'token' => $token])->withCookie($cookie);
    }

    /**
     * Handle a logout request.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    /**
     * Handle a forgot password request.
     */
    public function forgotPassword(Request $request)
    {
        // Will implement later if required
    }

    /**
     * Handle a reset password request.
     */
    public function resetPassword(Request $request)
    {
        // Will implement later if required
    }


}
