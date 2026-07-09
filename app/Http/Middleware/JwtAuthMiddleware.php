<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Forbidden. Token required.'], 403);
        }

        try {
            $secret = env('JWT_SECRET');
            if (!$secret) {
                return response()->json(['message' => 'Internal server error. JWT secret not configured.'], 500);
            }

            $decoded = JWT::decode($token, new Key($secret, 'HS256'));
            
            // Find user
            $user = User::find($decoded->id);
            if (!$user) {
                return response()->json(['message' => 'Unauthorized. User not found.'], 401);
            }

            // Bind user to request
            $request->setUserResolver(function () use ($user) {
                return $user;
            });

        } catch (ExpiredException $e) {
            return response()->json(['message' => 'Unauthorized. Token expired.'], 401);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unauthorized. Invalid token.'], 401);
        }

        return $next($request);
    }
}
