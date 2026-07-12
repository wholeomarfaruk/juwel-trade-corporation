<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureCustomerLoggedIn
{
    /**
     * Guards storefront-only pages (account, orders). Guests are sent back
     * to the homepage with ?auth=1 so the existing Alpine auth modal opens
     * itself, instead of Laravel's default redirect to the scaffolded
     * /login page which the storefront doesn't use.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'user') {
            return $next($request);
        }

        return redirect()->route('home', ['auth' => 1]);
    }
}
