<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\RefreshToken;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        return response()->json([
            'message' => 'User registered successfully',
            'user' => new UserResource($user),
        ], 201);
    }

    /**
     * Login and return a token.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->validated('email'))->first();

        if (! $user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        if (! Hash::check($request->validated('password'), $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        return $this->generateTokensResponse($user);
    }

    /**
     * Refresh the access token using a refresh token.
     */
    public function refresh(Request $request): JsonResponse
    {
        $request->validate(['refresh_token' => 'required|string']);
        
        $refreshTokenStr = $request->input('refresh_token');

        $refreshToken = RefreshToken::where('token', $refreshTokenStr)->first();

        if (!$refreshToken) {
            return response()->json(['message' => 'Invalid refresh token'], 401);
        }

        if (now()->greaterThan($refreshToken->expires_at)) {
            $refreshToken->delete();
            return response()->json(['message' => 'Refresh token expired'], 401);
        }

        $user = $refreshToken->user;

        // Revoke the old refresh token
        $refreshToken->delete();

        return $this->generateTokensResponse($user);
    }

    /**
     * Logout and revoke the refresh tokens.
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        
        // Revoke all refresh tokens for this user
        RefreshToken::where('user_id', $user->id)->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    /**
     * Generate JWT access token and refresh token response.
     */
    private function generateTokensResponse(User $user): JsonResponse
    {
        $secret = env('JWT_SECRET');
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600; // 1 hour

        $payload = [
            'id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
            'iat' => $issuedAt,
            'exp' => $expirationTime,
        ];

        $jwt = JWT::encode($payload, $secret, 'HS256');

        // Create refresh token
        $refreshTokenStr = Str::random(60);
        RefreshToken::create([
            'user_id' => $user->id,
            'token' => $refreshTokenStr,
            'expires_at' => now()->addDays(7), // 7 days
        ]);

        return response()->json([
            'token' => $jwt,
            'refresh_token' => $refreshTokenStr,
            'user' => new UserResource($user),
        ]);
    }
}
