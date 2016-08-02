<?php
namespace App\Modules\Admin\Controllers;

use App\Models\Permission;
use Breadcrumbs;
use App\Modules\Admin\Controllers\Controller as BaseController;
use Illuminate\Http\Request;

/**
 * 权限 管理
 * Class PermissionController
 * @package App\Modules\Admin\Controllers
 *
 * @author chuanhangyu
 * @since 2016/8/2 10:00
 */
class PermissionController extends BaseController {
    /**
     * 定义 permission list
     * @return array
     */
    public static function actionName()
    {
        return [
            'getModule'=> json_encode(['parent'=>null, 'icon'=>'home', 'display_name'=>'权限管理', 'is_menu'=>1, 'sort'=>20, 'allow'=>1, 'description'=>'']),
            'getList'=> json_encode(['parent'=>'PermissionController@getModule', 'icon'=>'user', 'display_name'=>'权限列表', 'is_menu'=>0, 'sort'=>21, 'allow'=>1, 'description'=>'']),
        ];
    }

    /**
     * 得到所有权限列表
     * @param Request $request
     * @return $this|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getList(Request $request) {
        return $this->response($request, ['data'=>Permission::getAllPermission()->toArray()], 'admin/role/assign');
    }
}
