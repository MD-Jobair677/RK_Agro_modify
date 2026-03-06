<?php

namespace App\Http\Middleware;

use App\Constants\ManageStatus;
use Closure;

class MaintenanceMode
{
    public function handle($request, Closure $next)
    {
        if (bs('site_maintenance') == ManageStatus::ACTIVE) {
            return to_route('maintenance');
        }
        
        return $next($request);
    }
}
