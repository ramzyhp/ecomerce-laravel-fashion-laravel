<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
    if (Auth::check() && Auth::user()->role == 'admin') {
        // Cukup cek ini saja, karena admin.logout sudah masuk dalam admin/*
        if (!$request->is('admin') && !$request->is('admin/*')) {
            return redirect()->route('admin');
        }
    }

    return $next($request);
    }
}