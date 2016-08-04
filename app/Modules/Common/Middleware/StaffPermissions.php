<?php

namespace App\Modules\Common\Middleware;

use App\Exceptions\ErrorMessageException;
use App\Exceptions\NoticeMessageException;
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
        if (!Auth::staff()->check()) {

            $jumpUri = $request->getUri();

            \Html::error('登录超时，请重新登录！', 401);
            return redirect("login?jumpUri=$jumpUri");
        } else {
            $roleIds = Auth::staff()->get()->getRoleIds();
            $permission = Permission::getPermission($action, $roleIds);

            if ($permission) {
                return $next($request);
            } else {
                throw new AccessDeniedHttpException('Forbidden');
            }
        }

    }
}
