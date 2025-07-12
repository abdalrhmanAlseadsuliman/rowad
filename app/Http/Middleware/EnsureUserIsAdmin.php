<?php

namespace App\Http\Middleware;

use Closure;
use App\Enums\Role;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{

    protected $except = [
        'admin/login',
        'admin/register', // إذا كان موجود
        'login',
        'register',
    ];

    public function handle(Request $request, Closure $next): Response
    {

        foreach ($this->except as $except) {
            if ($request->is($except)) {
                return $next($request);
            }
        }

        if (!auth()->check() || auth()->user()->role !== Role::Admin) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
