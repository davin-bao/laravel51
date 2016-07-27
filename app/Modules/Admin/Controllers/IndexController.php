<?php
namespace App\Modules\Admin\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use App\Modules\Common\Controller as BaseController;

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
            'getLogin'=> json_encode(['parent'=>null, 'icon'=>'home', 'display_name'=>'登录', 'is_menu'=>0, 'sort'=>0, 'allow'=>1, 'description'=>'']),
            'getLogout' => json_encode(['parent'=>null, 'icon'=>'home', 'display_name'=>'登出', 'is_menu'=>0, 'sort'=>0, 'allow'=>1, 'description'=>'']),
        ];
    }

    /**
     * 如果未登录，跳转到登陆页
     * 如果已登录，跳转到控制面板
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getIndex(){
        if (\Auth::staff()->guest()) {
            return redirect()->guest('/login');
        }else{

            return redirect()->guest('/admin');
        }
    }

    public function getLogin(){
        return view('Admin::login');
    }

    /**
     * 登录操作
     * @param Request $request
     * @return $this|\Symfony\Component\HttpFoundation\JsonResponse
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

    /**
     * 登出操作
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getLogout()
    {
        $staff = Auth::staff()->get();

        if($staff){
            $staff->last_seen = Cache::get('last_seen_' . $staff->id);
            Cache::forget('last_seen_' . $staff->id);
            Session::pull('locale');
            $staff->save();

            Auth::logout();
        }

        \Html::success('安全退出成功', 200);

        return redirect('/');
    }

}
