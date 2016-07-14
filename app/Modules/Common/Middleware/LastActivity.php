<?php

namespace App\Modules\Common\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class LastActivity {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (Auth::staff()->check()) {
            Cache::forever('last_seen_' . Auth::staff()->get()->id, date('Y-m-d H:i:s'));
        }

        return $next($request);
    }

}
