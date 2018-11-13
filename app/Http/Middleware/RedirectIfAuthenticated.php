<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $msg = $request->is('signup') ? '你已注册并登陆' :'你已登陆，无需再操作' ;
            session()->flash('info',$msg);
            return redirect('/');
        }

        return $next($request);
    }
}
