<?php
namespace App\Modules\Admin\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use App\Modules\Admin\Services\IndexService;
use App\Modules\Common\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/**
 * 首页
 * Class IndexController
 * @package App\Modules\Admin\Controllers
 *
 * @author davin.bao
 * @since 2016/7/15 9:34
 */
class IndexController extends BaseController {
    /**
     * 定义 permission list
     * @return array
     */
    public static function actionName()
    {
        return [
            'getLogin'=> json_encode(['parent'=>0, 'icon'=>'home', 'display_name'=>'登录', 'is_menu'=>1, 'sort'=>0, 'allow'=>1, 'description'=>''])
        ];
    }

    public function getLogin(){
        return view('Admin::login');
    }

    /**
     * 登录操作
     * @param Request $request
     */
    public function postLogin(Request $request){
        //验证参数是否有误
        $this->validateRequest([
            'username' => 'required|max:50',
            'password' => 'required|min:6',
        ], $request);

        $username = $request->input('username', null);
        $password = $request->input('password', null);
        $rememberMe = $request->input('remember_me', false);
        //登录
        $this->getService()->login($username, $password, $rememberMe);

        return $this->response($request,['msg'=>'管理员登录成功'], '/admin');
    }
}
