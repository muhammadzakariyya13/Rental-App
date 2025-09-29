<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = auth()->user();
        if (!$user || !$user->role || $user->role->nama !== $role) {
            abort(403, 'Akses ditolak. Peran tidak sesuai.');
        }
        return $next($request);
    }
}
