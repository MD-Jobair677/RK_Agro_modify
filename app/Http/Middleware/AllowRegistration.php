<?php

namespace App\Http\Middleware;

use App\Constants\ManageStatus;
use Closure;

class AllowRegistration
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
        if (bs('signup') == ManageStatus::INACTIVE) {
            $toast[] = ['info', 'We are not accepting registrations at the moment'];
            return back()->withToasts($toast);
        }

        return $next($request);
    }
}
