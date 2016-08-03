<?php
namespace App\Modules\Admin\Services;

use App\Exceptions\NoticeMessageException;
use Auth;
use App\Models\Permission;
use Symfony\Component\HttpFoundation\Request;

/**
 *  权限 Service
 * Class PermissionService
 * @package App\Modules\Admin\Services
 *
 * @author davin.bao
 * @since 2016/7/20 9:34
 */
class PermissionService{

    /**
     * 获取权限列表
     * @param $matchCon
     * @return mixed
     */
    public function permissionList($matchCon){
        $query = new Permission();
        //返回检索的Query
        return Permission::getSearchQuery($query, $matchCon);
    }

    public function getPermission($id){
        return Permission::find($id);
    }

    /**
     * 创建角色
     * @param array $parameters
     * @return static
     */
    public function createPermission(array $parameters){
        return Permission::create($parameters);
    }

    /**
     * 修改角色
     * @param array $parameters
     * @return static
     */
    public function updatePermission(array $parameters){
        $id = array_get($parameters, 'id', 0);
        $permission = Permission::find($id);
        if(!$permission){
            throw new NoticeMessageException('编辑的权限不存在!');
        }
        return $permission->update($parameters);
    }

}