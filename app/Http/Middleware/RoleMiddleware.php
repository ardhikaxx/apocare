<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'Akses ditolak.');
        }

        $userRole = $this->normalizeRoleName(optional($user->role)->nama);

        if ($userRole === '') {
            abort(403, 'Akun belum memiliki peran.');
        }

        if ($userRole === 'admin') {
            return $next($request);
        }

        $allowedRoles = collect($roles)
            ->map(fn (string $role) => $this->normalizeRoleName($role))
            ->filter()
            ->values();

        if ($allowedRoles->isEmpty() || ! $allowedRoles->contains($userRole)) {
            abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
        }

        return $next($request);
    }

    private function normalizeRoleName(?string $roleName): string
    {
        if (! is_string($roleName)) {
            return '';
        }

        $normalized = strtolower(trim(preg_replace('/\s+/', ' ', $roleName) ?? ''));

        return match ($normalized) {
            'staf gudang' => 'gudang',
            default => $normalized,
        };
    }
}