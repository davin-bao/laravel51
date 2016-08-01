<?php
namespace App\Modules\Admin\Controllers;

use Auth;
use Breadcrumbs;
use Illuminate\Http\Request;
use App\Modules\Admin\Controllers\Controller as BaseController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

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
            'postUpdateInfo'=> json_encode(['parent'=>'StaffController@getModule', 'icon'=>'', 'display_name'=>'', 'is_menu'=>0, 'sort'=>0, 'allow'=>1, 'description'=>'']),
            'getEdit'=> json_encode(['parent'=>'StaffController@getModule', 'icon'=>'', 'display_name'=>'', 'is_menu'=>0, 'sort'=>0, 'allow'=>1, 'description'=>'']),
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
            $breadcrumbs->parent('admin-staff');
            $breadcrumbs->push('管理员列表', adminAction('StaffController@getIndex'));
        });

        return $this->render('staff.index');
    }

    public function getList(Request $request){
        $matchCon = $request->input('matchCon', null);
        $query = $this->getService()->staffList($matchCon);
        $queryData = $this->queryData($request, $query);
        return $this->response($request, $queryData, 'admin/staff/index');
    }

    public function getAdd(){
        Breadcrumbs::register('admin-staff-add', function ($breadcrumbs) {
            $breadcrumbs->parent('admin-staff');
            $breadcrumbs->push('编辑管理员', adminAction('StaffController@getAdd'));
        });

        return $this->render('staff.add',["role"=>$this->getService()->getAllRoleList()]);
    }

    public function getEdit(Request $request){

        $this->validateRequest([
            'id' => 'required|min:0',
        ], $request);
        $id = $request->input('id', 0);
        $staff = $this->getService()->getStaff($id);

        return $this->response($request, ['data'=>$staff->toArray()], 'admin/staff/index');
    }

    public function postEdit(Request $request){

        $id = $request->input('id', 0);
        if(empty($id)){  //创建
            $this->validateRequest([
                'username' => 'required|alpha_num|min:6|max:30|unique:staff',
                'name' => 'required|min:1|max:20',
                'email' => 'required|min:6|max:30|email',
                'password' => 'required|min:6|max:30',
                'mobile' => 'required|min:7|max:20',
                'roles'=>'required',
            ], $request);
            $this->getService()->createStaff($request->all());
        }else{ //修改
            $this->validateRequest([
                'username' => 'required|alpha_num|min:6|max:30|unique:staff,username,' . $request->input('id', 0),
                'name' => 'required|min:1|max:20',
                'email' => 'required|min:6|max:30|email',
                'password' => 'required|min:6|max:30',
                'mobile' => 'required|min:7|max:20',
                'roles'=>'required',
            ], $request);
            $this->getService()->updateStaff($request->all());
        }

        return $this->response($request, [], 'admin/staff/index');
    }

    public function postDelete(Request $request){
        $this->validateRequest([
            'id' => 'required|min:0',
        ], $request);

        $id = $request->input('id', 0);
        $this->getService()->deleteStaff($id);
        return $this->response($request, [], 'admin/staff/index');
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

    public function getEdit(Request $request){

        $this->validateRequest([
            'id' => 'required|min:0',
        ], $request);

        $id = $request->input('id', 0);
        $role = $this->getService()->getStaff($id);

        return $this->response($request, ['data'=>$role->toArray()], '');
    }

    /**
     * 处理用户信息修改提交
     * @param Request $request
     * @return $this|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function postUpdateInfo(Request $request) {
        $this->validateRequest([
            'email' => 'required|email|max:50|unique:staff',
            'name' => 'required|max:30',
            'mobile' => 'required|numeric|max:99999999999999999999|min:9999999',
        ], $request);

        if ($this->getService()->updateInfo($request->all())) {
            return $this->response($request, [], '/admin/staff/profile');
        }
    }
}
