<?php
namespace App\Modules\Admin\Controllers;

use App\Modules\Admin\Services\FileService;
use App\Modules\Common\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

/**
 * Class StaticController
 * @package App\Modules\Admin\Controllers
 *
 * @author chuanhangyu
 * @since 2016/7/29 8:30
 */
class StaticController extends BaseController {

    public static function actionName(){
        return [
            'postUpload'=> json_encode(['parent'=>null, 'icon'=>'upload', 'display_name'=>'文件上传', 'is_menu'=>0, 'sort'=>0, 'allow'=>1, 'description'=>'']),
        ];
    }

    /**
     * 提交头像上传
     * @param Request $request
     * @return $this|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function postUploadAvatar(Request $request) {
        $avatar = Input::all();
        FileService::uploadAvatar($avatar);
        return $this->response($request, ['msg' => '设置成功！'], '/admin/profile');
    }

    /**
     * 得到用户头像
     * @param Request $request
     * @return mixed
     */
    public function getAvatar(Request $request) {
        $id = $request->input('id');
        $response = FileService::getStaffAvatar($id);
        return $this->response($request, ['msg'=>$response], '/admin/profile');
    }
}