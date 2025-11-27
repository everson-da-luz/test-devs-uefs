<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;

class ApiAuthMiddleware
{
    public function handle($request, Closure $next)
    {
        $token = $request->header('Authorization');

        if (! $token) {
            return response()->json([
                'success' => false,
                'code' => 401,
                'message' => 'Token obrigatório.',
                'data' => []
            ], 401);
        }

        $token = str_replace('Bearer ', '', $token);
        $modelUser = new User();
        $user = $modelUser->getByApiToken($token);

        if (! $user) {
            return response()->json([
                'success' => false,
                'code' => 401,
                'message' => 'Não autorizado.',
                'data' => []
            ], 401);
        }

        $request->setUserResolver(fn() => $user);

        return $next($request);
    }
}
