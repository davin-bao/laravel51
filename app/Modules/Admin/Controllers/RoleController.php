<?php
namespace App\Modules\Admin\Controllers;

use Breadcrumbs;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Http\Request;
use App\Modules\Admin\Controllers\Controller as BaseController;

/**
 * 角色 管理
 * Class RoleController
 * @package App\Modules\Admin\Controllers
 *
 * @author davin.bao
 * @since 2016/7/20 9:34
 */
class RoleController extends BaseController {
    /**
     * 定义 permission list
     * @return array
     */
    public static function actionName()
    {
        return [
            'getModule'=> json_encode(['parent'=>null, 'icon'=>'home', 'display_name'=>'角色管理', 'is_menu'=>1, 'sort'=>20, 'allow'=>1, 'description'=>'']),

            'getIndex'=> json_encode(['parent'=>'RoleController@getModule', 'icon'=>'user', 'display_name'=>'角色列表', 'is_menu'=>1, 'sort'=>21, 'allow'=>1, 'description'=>'']),
            'getAdd'=> json_encode(['parent'=>'RoleController@getModule', 'icon'=>'plus', 'display_name'=>'添加角色', 'is_menu'=>1, 'sort'=>22, 'allow'=>1, 'description'=>'']),

            'getList'=> json_encode(['parent'=>'RoleController@getModule', 'icon'=>'', 'display_name'=>'', 'is_menu'=>0, 'sort'=>0, 'allow'=>1, 'description'=>'']),
            'postAdd'=> json_encode(['parent'=>'RoleController@getModule', 'icon'=>'', 'display_name'=>'添加角色', 'is_menu'=>0, 'sort'=>0, 'allow'=>1, 'description'=>'']),
            'postUpdate'=> json_encode(['parent'=>'RoleController@getModule', 'icon'=>'', 'display_name'=>'修改角色', 'is_menu'=>0, 'sort'=>0, 'allow'=>1, 'description'=>'']),
            'postDelete'=> json_encode(['parent'=>'RoleController@getModule', 'icon'=>'', 'display_name'=>'删除角色', 'is_menu'=>0, 'sort'=>0, 'allow'=>1, 'description'=>'']),
            'getAssignPermissions'=> json_encode(['parent'=>'RoleController@getModule', 'icon'=>'', 'display_name'=>'权限分配', 'is_menu'=>0, 'sort'=>0, 'allow'=>1, 'description'=>'']),
            'getPermissionList'=> json_encode(['parent'=>'RoleController@getModule', 'icon'=>'', 'display_name'=>'', 'is_menu'=>0, 'sort'=>0, 'allow'=>1, 'description'=>'']),
            'postPermissionList'=> json_encode(['parent'=>'RoleController@getModule', 'icon'=>'', 'display_name'=>'', 'is_menu'=>0, 'sort'=>0, 'allow'=>1, 'description'=>'']),
        ];
    }

    public function __construct()
    {
        parent::__construct();

        Breadcrumbs::register('admin-role', function ($breadcrumbs) {
            $breadcrumbs->parent('dashboard');
            $breadcrumbs->push('角色管理', adminAction('RoleController@getIndex'));
        });
    }

    public function getIndex(){
        Breadcrumbs::register('admin-role-index', function ($breadcrumbs) {
            $breadcrumbs->parent('admin-role');
            $breadcrumbs->push('角色列表', adminAction('RoleController@getIndex'));
        });

        return $this->render('role.index');
    }

    public function getList(Request $request){
        $matchCon = $request->input('matchCon', null);

        $query = $this->getService()->roleList($matchCon);
        $queryData = $this->queryData($request, $query);

        return $this->response($request, $queryData, 'admin/role/index');
    }

    public function getAdd(){
        Breadcrumbs::register('admin-role-add', function ($breadcrumbs) {
            $breadcrumbs->parent('admin-role');
            $breadcrumbs->push('编辑角色', adminAction('RoleController@getAdd'));
        });

        return $this->render('role.add');
    }

    public function getEdit(Request $request){

        $this->validateRequest([
            'id' => 'required|min:0',
        ], $request);

        $id = $request->input('id', 0);
        $role = $this->getService()->getRole($id);

        return $this->response($request, ['data'=>$role->toArray()], 'admin/role/index');
    }

    public function postUpdate(Request $request){

        $id = $request->input('id', 0);
        $customAttributes = [
            'name'=>'角色英文标示',
            'display_name'=>'角色中文名称'
        ];

        if(empty($id)){  //创建
            $this->validateRequest([
                'name' => 'required|alpha_num|min:6|max:30|unique:roles',
                'display_name' => 'required|min:6|max:30',
            ], $request, $customAttributes);

            $this->getService()->createRole($request->all());
        }else{ //修改
            $this->validateRequest([
                'name' => 'required|alpha_num|min:6|max:30|unique:roles,name,' . $request->input('id', 0),
                'display_name' => 'required|min:6|max:30',
            ], $request, $customAttributes);

            $this->getService()->updateRole($request->all());
        }

        return $this->response($request, [], 'admin/role/index');
    }

    public function postDelete(Request $request){

        $this->validateRequest([
            'id' => 'required|min:0',
        ], $request);

        $id = $request->input('id', 0);

        $this->getService()->deleteRole($id);

        return $this->response($request, [], 'admin/role/index');
    }

    /**
     * 权限分配页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAssignPermissions() {
        Breadcrumbs::register('admin-role-assign', function ($breadcrumbs) {
            $breadcrumbs->parent('admin-role');
            $breadcrumbs->push('权限分配', adminAction('RoleController@getAssignPermissions'));
        });
        return $this->render('role.assign');
    }

    /**
     * 根据角色id得到对应的所有权限
     * @param Request $request
     * @return $this|\Symfony\Component\HttpFoundation\JsonResponse
     *
     * @author chuanhangyu
     * @since 2016/8/2 10:30
     */
    public function getPermissionList(Request $request) {
        $this->validateRequest([
            'id' => 'required|min:0',
        ], $request);

        $id = $request->input('id', 0);
        $permission = $this->getService()->getRolePerimission($id);
        return $this->response($request, ['data'=>$permission->toArray()], 'admin/role/index');
    }

    /**
     * 提交权限修改
     * @param Request $request
     * @return $this|\Symfony\Component\HttpFoundation\JsonResponse
     *
     * @author chuanhangyu
     * @since 2016/8/2 14:00
     */
    public function postPermissionList(Request $request) {
        $this->validateRequest([
            'id' => 'required|min:0',
        ], $request);

        $id = $request->input('id', 0);
        $permissions = explode(',', $request->input('permissions', 0));
        $this->getService()->updateRolePermission($id, $permissions);

        return $this->response($request, [], 'admin/role');
    }
}
