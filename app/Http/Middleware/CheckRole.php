<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $normalizedRoles = array_map(fn ($role) => strtolower((string) $role), $roles);
        $userRole = strtolower((string) $user->role);

        // Jika user tidak memiliki role yang diizinkan
        if (!in_array($userRole, $normalizedRoles, true)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
