<?php
namespace App\Modules\Admin\Services;

use App\Exceptions\NoticeMessageException;
use App\Models\Staff;

/**
 * Class StaffService
 * @package App\Modules\Admin\Services
 *
 * @author chuanhangyu
 * @since 2016/7/29 9:30
 */
class StaffService {
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

    /**
     * 更新用户信息
     * @param $parameters
     * @return mixed
     */
    public function updateInfo($parameters) {
        $id = array_get($parameters, 'id', 0);
        $staff = Staff::find($id);
        if(!$staff){
            throw new NoticeMessageException('改管理员不存在!');
        }
        return $staff->update($parameters);
    }

    /**
     * 得到用户信息
     * @param $id
     * @return mixed
     */
    public function getStaff($id) {
        return Staff::find($id);
    }
}
