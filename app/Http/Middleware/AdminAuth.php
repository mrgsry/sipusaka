<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    // Cek apakah user sudah login
    if (!session('admin_logged_in')) {
        return redirect()->route('admin.login')
                     ->with('error', 'Silakan login terlebih dahulu');
    }
    return $next($request);
}
}
