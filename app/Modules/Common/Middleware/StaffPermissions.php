<?php

namespace App\Modules\Common\Middleware;

use App\Models\Permission;
use Closure;
use Illuminate\Support\Facades\Auth;

class StaffPermissions
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
        $action = $request->route()->getActionName();
        $roleIds = (Auth::staff()->check()) ? Auth::staff()->get()->getRoleIds() : null;
        $permission = Permission::getPermission($action, $roleIds);

        if ($permission) {
            return $next($request);
        } else {
            return view('errors.403', ['admin' => null]);
        }
    }
}
