<?php
namespace App\Modules\Admin\Controllers;

use Auth;
use Breadcrumbs;
use Illuminate\Http\Request;
use App\Modules\Admin\Controllers\Controller as BaseController;

/**
 * 管理员 管理
 * Class StaffController
 * @package App\Modules\Admin\Controllers
 *
 * @author davin.bao
 * @since 2016/7/26 11:34
 */
class StaffController extends BaseController {
    /**
     * 定义 route action name list
     * @return array
     */
    public static function actionName()
    {
        return [
            'getModule'=> json_encode(['parent'=>null, 'icon'=>'home', 'display_name'=>'管理员管理', 'is_menu'=>1, 'sort'=>10, 'allow'=>1, 'description'=>'']),

            'getIndex'=> json_encode(['parent'=>'StaffController@getModule', 'icon'=>'user', 'display_name'=>'管理员列表', 'is_menu'=>1, 'sort'=>11, 'allow'=>1, 'description'=>'']),
            'getPermissionUrlList'=> json_encode(['parent'=>'StaffController@getModule', 'icon'=>'', 'display_name'=>'', 'is_menu'=>0, 'sort'=>0, 'allow'=>1, 'description'=>'']),
        ];
    }

    public function __construct()
    {
        parent::__construct();

        Breadcrumbs::register('admin-staff', function ($breadcrumbs) {
            $breadcrumbs->parent('dashboard');
            $breadcrumbs->push('管理员管理', adminAction('StaffController@getIndex'));
        });
    }

    public function getIndex(){
        Breadcrumbs::register('admin-staff-index', function ($breadcrumbs) {
            $breadcrumbs->parent('admin-role');
            $breadcrumbs->push('管理员列表', adminAction('StaffController@getIndex'));
        });

        return $this->render('staff.index');
    }
    /**
     * 获取个人信息
     * @author cunqinghuang
     * @since 2016/7/26 19:00
     */
    public function getProfile(){
        Breadcrumbs::register('admin-staff-profile', function ($breadcrumbs) {
            $breadcrumbs->parent('admin-staff');
            $breadcrumbs->push('个人信息', adminAction('StaffController@getProfile'));
             });
        return $this->render('staff.profile',['staff'=> Auth::staff()->get()]);
    }
    /**
     * 获取当前管理员权限Uri列表
     * @param Request $request
     * @return $this|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getPermissionUrlList(Request $request){
        $uris = [];
        $permissions = Auth::staff()->get()->getPermissions();
        foreach($permissions as $Permission){
            array_push($uris, $Permission->uri);
        }

        return $this->response($request, ['data'=>$uris], 'admin/staff/index');
    }
}
