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

     /**
      * 得到用户信息
      * @param $id
      * @return mixed
      */
     public function getStaff($id){
         return Staff::with('roles')->find($id);
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

        //不允许修改用户名
        if(isset($parameters['username'])){
            unset($parameters['username']);
        }

        //密码未修改时密码不变
        if(isset($parameters['password'])){
            unset($parameters['password']);
        }

        //角色未修改时角色不变
        if(isset($parameters['roles'])&&empty($parameters['roles'])){
            unset($parameters['roles']);
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
     /**
      * 更新用户头像
      * @param array $parameters
      * @return mixed
      */
     public static function updateAvatar(array $parameters) {
         $id = array_get($parameters, 'id', 0);
         $staff = Staff::find($id);
         if(!$staff){
             throw new NoticeMessageException('该管理员不存在!');
         }
         FileService::delete(basename($staff->avatar));
         return $staff->update($parameters);
     }

     /**
      * 根据id得到用户头像
      * @param $id
      * @return mixed
      */
     public static function getAvatar($id) {
         $staff = Staff::find($id);
         if (!$staff) {
             throw new NoticeMessageException('该管理员不存在!');
         }
         return $staff->avatar;
     }
}