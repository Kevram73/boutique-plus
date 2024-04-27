<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;


class ControleUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
           if(auth()->user()->flag_etat ==1)
            {
                    Auth::logout();
                    return redirect('/bloquer');
            //return redirect('/home');
            } 
        return $next($request);
    }
}
