<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to continue.');
        }

        if ($user->status !== 'active') {
            \Illuminate\Support\Facades\Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('error', 'Your account is currently inactive or suspended.');
        }

        $userRole = $user->role?->name;

        // Admins have supervisory access across all dashboard sections
        if ($userRole === 'admin') {
            return $next($request);
        }

        if (!in_array($userRole, $roles, true)) {
            abort(403, 'Unauthorized access: You do not have permission to view this section.');
        }

        return $next($request);
    }
}
