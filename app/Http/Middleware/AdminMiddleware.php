<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
{
    if (!Auth::guard('admin')->check()) {
        return redirect()->route('admin.login'); // Chuyển hướng nếu chưa đăng nhập
    }

    return $next($request);
}
}


