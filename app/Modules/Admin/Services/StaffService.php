<?php
namespace App\Modules\Admin\Services;

use App\Exceptions\NoticeMessageException;
use Auth;
use App\Models\Role;
use App\Models\Staff;
use Symfony\Component\HttpFoundation\Request;

 /**
  * 管理员 Service
  * Class StaffService
  * @package App\Modules\Admin\Services
  *
  * @author
  * @since
  */
 class StaffService {

    /**
     * 获取权限列表
     * @param $matchCon
     * @return mixed
     */
     public function staffList($matchCon){
         $query = new Staff();
         //返回检索的Query
         return Staff::getSearchQuery($query, $matchCon);
     }

     public function getStaff($id){
         return Staff::find($id);
     }

    /**
     * 创建管理员
     * @param array $parameters
     * @return static
     */
     public function createStaff(array $parameters){
     return Staff::create($parameters);
     }


     /**
      * 获取角色信息
      */
     public function getAllRoleList(){
         return Role::all();
     }

    /**
     * 修改管理员
     * @param array $parameters
     * @return static
     */
    public function updateStaff(array $parameters){
        $id = array_get($parameters, 'id', 0);
        $staff = Staff::find($id);
        if(!$staff){
            throw new NoticeMessageException('编辑的管理员不存在!');
        }
        return $staff->update($parameters);
    }

     public function deleteStaff($id){
         $staff = Staff::find($id);
         if(!$staff){
             throw new NoticeMessageException('选择的管理员不存在!');
         }else if ($staff->id < 2) {
             throw new NoticeMessageException('系统管理员不允许删除!');
         }else {
                        $staff->remove($id);
                    }
         return true;
     }
}