<?php

namespace App\Modules\Common\Middleware;

use App\Models\Permission;
use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * 实现ACL权限控制
 *
 * Class StaffPermissions
 * @package App\Modules\Common\Middleware
 *
 * @author davin.bao
 * @since 2016/7/15 18:34
 */
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

        if (!$permission) {
            return $next($request);
        } else {
            throw new AccessDeniedHttpException('Forbidden');
        }
    }
}
