<?php
namespace App\Modules\Admin\Controllers;

use Breadcrumbs;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Http\Request;
use App\Modules\Admin\Controllers\Controller as BaseController;

/**
 * 权限 管理
 * Class PermissionController
 * @package App\Modules\Admin\Controllers
 *
 * @author cunqinghuang
 * @since 2016/8/2 15:50
 */
class PermissionController extends BaseController
{
    /**
     * 定义 permission list
     * @return array
     */
    public static function actionName()
    {
        return [
            'getModule'=> json_encode(['parent'=>null, 'icon'=>'home', 'display_name'=>'权限管理', 'is_menu'=>1, 'sort'=>30, 'allow'=>1, 'description'=>'']),
            'getIndex'=> json_encode(['parent'=>'PermissionController@getModule', 'icon'=>'user', 'display_name'=>'权限列表', 'is_menu'=>1, 'sort'=>31, 'allow'=>1, 'description'=>'']),
            'getAdd'=> json_encode(['parent'=>'PermissionController@getModule', 'icon'=>'plus', 'display_name'=>'添加权限', 'is_menu'=>1, 'sort'=>32, 'allow'=>1, 'description'=>'']),
            'getEdit'=> json_encode(['parent'=>'PermissionController@getModule', 'icon'=>'', 'display_name'=>'', 'is_menu'=>0, 'sort'=>0, 'allow'=>1, 'description'=>'']),
            'getList'=> json_encode(['parent'=>'PermissionController@getModule', 'icon'=>'', 'display_name'=>'', 'is_menu'=>0, 'sort'=>0, 'allow'=>1, 'description'=>'']),
            'postAdd'=> json_encode(['parent'=>'PermissionController@getModule', 'icon'=>'', 'display_name'=>'添加权限', 'is_menu'=>0, 'sort'=>0, 'allow'=>1, 'description'=>'']),
            'postUpdate'=> json_encode(['parent'=>'PermissionController@getModule', 'icon'=>'', 'display_name'=>'修改权限', 'is_menu'=>0, 'sort'=>0, 'allow'=>1, 'description'=>'']),
        ];
    }

    public function __construct()
    {
        parent::__construct();

        Breadcrumbs::register('admin-permission', function ($breadcrumbs) {
            $breadcrumbs->parent('dashboard');
            $breadcrumbs->push('权限管理', adminAction('PermissionController@getIndex'));
        });
    }

    public function getIndex()
    {
        Breadcrumbs::register('admin-permission-index', function ($breadcrumbs) {
            $breadcrumbs->parent('admin-permission');
            $breadcrumbs->push('权限列表', adminAction('PermissionController@getIndex'));
        });

        return $this->render('permission.index');
    }

    /**
     * 得到所有权限列表
     * @param Request $request
     * @return $this|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getList(Request $request){
        $matchCon = $request->input('matchCon', null);

        $query = $this->getService()->permissionList($matchCon);
        $queryData = $this->queryData($request, $query);

        return $this->response($request, $queryData, 'admin/permission/index');
    }

    public function getAdd(){
        Breadcrumbs::register('admin-permission-add', function ($breadcrumbs) {
            $breadcrumbs->parent('admin-permission');
            $breadcrumbs->push('编辑角色', adminAction('PermissionController@getAdd'));
        });

        return $this->render('permission.add');
    }

    public function getEdit(Request $request){

        $this->validateRequest([
            'id' => 'required|min:0',
        ], $request);

        $id = $request->input('id', 0);
        $permission = $this->getService()->getPermission($id);
        return $this->response($request, ['data'=>$permission->toArray()], 'admin/permission/index');
    }

    public function postUpdate(Request $request){

        $id = $request->input('id', 0);
        $customAttributes = [
            'parent'=>'父菜单',
            'display_name'=>'权限名称',
            'icon'=>'图标',
            'uri'=>'路径',
            'action'=>'URL地址对接的接口地址',
            'is_menu'=>'是否作为菜单显示',
            'sort'=>'排序',
            'description'=>'描述',
        ];

        if(empty($id)){  //创建
            $this->validateRequest([
                'parent' => 'min:0|max:255',
                'display_name' => 'min:0|max:30',
                'icon' => 'min:0|max:50',
                'uri'=>'required|min:0|max:255',
                'action'=>'required|min:0|max:255|unique:permissions',
                'is_menu'=>'required|regex:/^[01]$/',
                'sort'=>'required|regex:/^[0-9]*$/',
                'description'=>'min:0|max:255',
            ], $request, $customAttributes);

            $this->getService()->createPermission($request->all());
        }else{ //修改
            $this->validateRequest([
                'action' => 'required|min:0|max:255|unique:permissions,action,' . $request->input('id', 0),
                'parent' => 'min:0|max:255',
                'icon' => 'min:0|max:50',
                'uri'=>'required|min:0|max:255',
                'sort'=>'required|regex:/^[0-9]{0,10}$/',
                'description'=>'min:0|max:255',
                'is_menu'=>'required|regex:/^[0,1]$/',
                'display_name' => 'required|min:0|max:30',
            ], $request, $customAttributes);

            $this->getService()->updatePermission($request->all());
        }

        return $this->response($request, [], 'admin/permission/index');
    }

}