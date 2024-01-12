<?php

namespace App\Http\Middleware;

use App\Models\TotalLogin;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserActivity
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
        if (TotalLogin::ifUserExist(Auth::id())) {
            $currentTime = Carbon::now();
            TotalLogin::setLastActivity($currentTime);
        } else {
            toastr()->warning('Your session has expired due to inactivity.');
            return redirect('login');
        }
        return $next($request);
    }
}
