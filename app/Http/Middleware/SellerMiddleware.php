<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SellerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->is_seller) {
            return $next($request);
        }
        session()->flash('warning', 'У вас нет прав продавца');
        return redirect()->route('index');
    }
}
