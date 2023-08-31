<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if(Auth::guard($guard)->check()){
                       //write code for redirect admin or front when login is already done
          if($request->is('admin') || $request->is('admin/*'))
          {
             return redirect(RouteServiceProvider::Admin);
          }
          else
          {
            return redirect(RouteServiceProvider::Admin);
            //redirect to front end
            //return redirect(RouteServiceProvider::HOME);
          }

            }

     
        }

        return $next($request);
    }
}
