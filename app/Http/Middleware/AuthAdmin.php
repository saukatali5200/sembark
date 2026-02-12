<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;
use Redirect;

class AuthAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::guard('admins')->user()){
            return $next($request);          
         }
         return redirect::route('Auth.login');
    }
}
