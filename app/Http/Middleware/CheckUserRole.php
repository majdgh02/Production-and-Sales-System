<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$role): Response
    {
        $userRole = $request->user()->role;
        if(!in_array($userRole->name, $role)){
            return response()->json([
                'message' => 'You are not authorized, you do not have the required role.'
            ], 403);
        }

        return $next($request);
    }
}
