<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $u = $request->user();
        if (!$u || !in_array($u->role, ['admin', 'superadmin'])) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return $next($request);
    }
}
