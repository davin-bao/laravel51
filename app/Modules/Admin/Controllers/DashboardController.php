<?php
namespace App\Modules\Admin\Controllers;

use App\Modules\Admin\Services\StaffService;
use Breadcrumbs;
use App\Modules\Admin\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 控制面板
 * Class DashboardController
 * @package App\Modules\Admin\Controllers
 *
 * @author davin.bao
 * @since 2016/7/15 9:34
 */
class DashboardController extends BaseController {
    /**
     * 定义 permission list
     * @return array
     */
    public static function actionName()
    {
        return [
            'getIndex'=> json_encode(['parent'=>null, 'icon'=>'home', 'display_name'=>'控制面板', 'is_menu'=>1, 'sort'=>0, 'allow'=>1, 'description'=>'']),
            'getProfile'=> json_encode(['parent'=>'DashboardController@getIndex', 'icon'=>'home', 'display_name'=>'', 'is_menu'=>0, 'sort'=>0, 'allow'=>1, 'description'=>'']),
            'postEdit'=> json_encode(['parent'=>'DashboardController@getIndex', 'icon'=>'home', 'display_name'=>'修改用户个人信息', 'is_menu'=>0, 'sort'=>0, 'allow'=>1, 'description'=>''])
        ];
    }

    public function getIndex(){
        return $this->render('dashboard');
    }

    /**
     * 获取个人信息
     * @author cunqinghuang
     * @since 2016/7/26 19:00
     */
    public function getProfile(){
        Breadcrumbs::register('admin-staff-profile', function ($breadcrumbs) {
            $breadcrumbs->parent('dashboard');
            $breadcrumbs->push('个人信息', adminAction('DashboardController@getProfile'));
        });
        return $this->render('staff.profile',['staff'=> Auth::staff()->get()]);
    }

    /**
     * 处理用户信息修改提交
     * @param Request $request
     * @return $this|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function postEdit(Request $request) {
        $attribute = [
            'email' => '该邮箱',
            'name' => '名字',
            'mobile' => '手机号'
        ];
        $this->validateRequest([
            'email' => 'unique:staff,email,' . $request->input('id', 0),
            'name' => 'required|max:30',
            'mobile' => 'regex:/^\([0-9]{3}\)\ 1[34578][0-9]\-[0-9]{4}\ x[0-9]{4}$/',
        ], $request, $attribute);

        $staffService = new StaffService();
        if ($staffService->updateStaff($request->all())) {
            return $this->response($request, [], '/admin/profile');
        }
    }
}
