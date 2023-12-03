<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;

class EnsureTokenIsValid
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
        if (!empty(trim($request->input('api')))) {
            $is_exists = Admin::where('id', Admin::guard(['admin-api'])->id())->exists();
            if ($is_exists) {
                return $next($request);
            }
        }
        return response()->json('Invalid Token', 401);
    }
    // public function handle($request, Closure $next)
    // {
    //     if (!empty(trim($request->input('api_token')))) {

    //         $is_exists = Admin::where('id', Admin::guard(['admin-api'])->id())->exists();
    //         if ($is_exists) {
    //             return $next($request);
    //         }
    //     }
    //     return response()->json('Invalid Token', 401);
    // }
    // public function handle($request, Closure $next)
    // {
    //     if ($request->input('api_token') !== 'my-secret-token') {
    //         //return redirect('home');
    //         return response()->json('Invalid Token', 401);
    //     }


    // }
}
