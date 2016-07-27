<?php
namespace App\Modules\Admin\Services;

use Auth;
use App\Models\Staff;
use App\Exceptions\NoticeMessageException;

/**
 *  Index Service
 * Class IndexService
 * @package App\Modules\Admin\Services
 *
 * @author davin.bao
 * @since 2016/7/15 9:34
 */
class IndexService {

    /**
     * 登录操作
     * @param $username
     * @param $password
     * @param $rememberMe
     * @return bool
     */
    public function login($username, $password, $rememberMe){
        //
        $admin = Staff::where('username', $username)->first();

        // Check is the account confirmed
        if (!empty($admin) && !empty($admin->confirm_token)) {
            throw new NoticeMessageException('您的账号未认证，禁止登陆!');
        }

        if (!Auth::staff()->attempt([
            'username' => $username,
            'password' => $password
        ], $rememberMe
        )) {
            throw new NoticeMessageException('用户名或密码错误!');
        }

        return true;
    }

    /**
     * 注册服务
     * @param $staff
     * @return bool
     *
     * @author chuanhangyu
     * @since 2016/7/25 16:00
     */
    public function register($staff) {
        if (Staff::create($staff)) {
            return true;
        } else {
            throw new NoticeMessageException('注册失败!');
        }
    }
}