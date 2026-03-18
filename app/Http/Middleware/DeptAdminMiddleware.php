<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DeptAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->is_dept_admin == 1 && auth()->user()->is_active == 1 && auth()->user()->is_delete == 0) {
            return $next($request);
        }

        return response()->json([
            'status' => false,
            'message' => 'Unauthorized Access.',
            'data' => (object)[]
        ], 403);
    }
}
