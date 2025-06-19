<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureRoleWebIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    //  ...$role berfungsi untuk menampung multi role dar route ensureroleweb:verifikator,pmu-bpdlh
    public function handle(Request $request, Closure $next, ...$role)
    {
        if (in_array($request->user()->role_user, $role, true)) {
            # code...
            return $next($request);
        } else {
            return abort(403);
        }
    }
}
