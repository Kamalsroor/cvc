<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserStatus
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
        $user = Auth::user();
        if ($user) {
            // dd($user->status);
            switch ($user->status) {
                case "Blocked":
                    Auth::guard()->logout();
                    $request->session()->invalidate();
                    return redirect('login')->with('error', 'Your account has been Not Active');
                    break;
                case "deleted":
                    Auth::guard()->logout();
                    $request->session()->invalidate();
                    return redirect('login')->with('error', 'Your account has been blocked');
            }
        }
        return $next($request);
    }
}