<?php

namespace App\Http\Middleware;

use App\CPU\Helpers;
use Brian2694\Toastr\Facades\Toastr;
use Closure;
use http\Client;

class ActivationCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		return $next($request);
    }
}
