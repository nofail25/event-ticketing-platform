<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfMissingRole
{
    /**
     * Redirect authenticated web users to their own area when they open a route
     * reserved for another role.
     */
    public function handle(Request $request, Closure $next, string $role, ?string $guard = null): Response
    {
        $auth = Auth::guard($guard);

        if (! $auth->check()) {
            return redirect()->guest(route('login'));
        }

        $roles = array_filter(array_map('trim', explode('|', $role)));

        /** @var \App\Models\User $user */
        $user = $auth->user();

        if (! $user->hasAnyRole($roles)) {
            if ($request->expectsJson()) {
                abort(403, 'User does not have the right roles.');
            }

            return redirect()
                ->route('dashboard')
                ->with('warning', 'Akun Anda tidak memiliki akses ke halaman tersebut.');
        }

        return $next($request);
    }
}
