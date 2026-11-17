<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $userRoleName = strtolower($user->role instanceof Role ? $user->role->name : (Role::tryFrom((int) $user->role)?->name ?? ''));

        foreach ($roles as $role) {
            $normalized = strtolower(str_replace(['_', ' '], '', $role));
            $userNormalized = strtolower(str_replace(['_', ' '], '', $userRoleName));

            if ($userNormalized === $normalized) {
                return $next($request);
            }
        }

        abort(403, 'Akses tidak diizinkan untuk peran akun Anda.');
    }
}
