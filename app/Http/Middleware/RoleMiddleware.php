<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Authentication required'], 401);
        }

        $userRole = auth()->user()->role;
        
        if (!in_array($userRole, $roles)) {
            return response()->json(['message' => 'Access denied for your role'], 403);
        }

        return $next($request);
    }
}