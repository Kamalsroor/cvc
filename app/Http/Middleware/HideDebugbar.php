<?php
namespace App\Http\Middleware;

use Closure;
use Debugbar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HideDebugbar
{
    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if(strpos($request->path(),'install') === false){

            if (!Auth::user() || !Auth::user()->hasPermissionTo('system_log_view')) {
                Debugbar::disable();
            }
        }
        return $next($request);
    }
}