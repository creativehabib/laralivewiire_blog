<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIfInstalled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // যদি 'installed' ফাইলটি না থাকে এবং ইউজার ইনস্টল পেজে না থাকে
        if (!file_exists(storage_path('installed')) && !$request->is('install*')) {
            return redirect()->route('install.index');
        }

        // যদি ইনস্টল করা থাকে কিন্তু ইউজার আবার ইনস্টল পেজে যেতে চায়
        if (file_exists(storage_path('installed')) && $request->is('install*')) {
            return redirect('/');
        }

        return $next($request);
    }
}
