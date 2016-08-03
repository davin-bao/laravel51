<?php
namespace App\Modules\Admin\Services;

use App\Exceptions\NoticeMessageException;
use Auth;
use App\Models\Role;
use Symfony\Component\HttpFoundation\Request;

/**
 *  角色 Service
 * Class RoleService
 * @package App\Modules\Admin\Services
 *
 * @author davin.bao
 * @since 2016/7/20 9:34
 */
class RoleService {

    /**
     * 获取权限列表
     * @param $matchCon
     * @return mixed
     */
    public function roleList($matchCon){
        $query = new Role();
        //返回检索的Query
        return Role::getSearchQuery($query, $matchCon);
    }

    public function getRole($id){
        return Role::find($id);
    }

    /**
     * 创建角色
     * @param array $parameters
     * @return static
     */
    public function createRole(array $parameters){
        return Role::create($parameters);
    }

    /**
     * 修改角色
     * @param array $parameters
     * @return static
     */
    public function updateRole(array $parameters){
        $id = array_get($parameters, 'id', 0);
        $role = Role::find($id);
        if(!$role){
            throw new NoticeMessageException('编辑的角色不存在!');
        }
        return $role->update($parameters);
    }

    public function deleteRole($id){
        $role = Role::find($id);

        if(!$role){
            throw new NoticeMessageException('选择的角色不存在!');
        }else if ($role->id < 2) {
            throw new NoticeMessageException('系统角色不允许删除!');
        }else if($role->staff()->get()->count() > 0){
            throw new NoticeMessageException('角色下关联了用户，不允许删除!');
        } else {
            $role->delete();
        }
        return true;
    }

    /**
     * 得到角色以及其对于的权限
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     *
     * @author chuanhangyu
     * @since 2016/8/2 10:40
     */
    public function getRolePerimission($id) {
        return Role::with('permissions')->find($id);
    }

    /**
     * 更新角色权限
     * @param $id
     * @param $permissions
     * @return mixed
     *
     * @author chuanhanhyu
     * @since 2016/8/2 14:10
     */
    public function updateRolePermission($id, $permissions) {
        $role = Role::find($id);

        if ($role) {
            return $role->updatePermission($permissions);
        } else {
            throw new NoticeMessageException('角色不存在!');
        }
    }
}