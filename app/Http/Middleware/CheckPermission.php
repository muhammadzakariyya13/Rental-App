<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = auth()->user();

        // Cek apakah user punya permission yang dibutuhkan
        if (!$user || !$user->hasPermission($permission)) {
            // Jika tidak punya, kembalikan 403 Forbidden
            abort(403, 'Anda tidak memiliki izin untuk mengakses sumber daya ini.');
        }

        return $next($request);
    }
}