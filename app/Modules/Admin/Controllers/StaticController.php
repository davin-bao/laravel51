<?php
namespace App\Modules\Admin\Controllers;

use App\Modules\Admin\Services\FileService;
use App\Modules\Common\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;

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
     * @return mixed
     */
    public function postUploadAvatar() {
        $avatar = Input::all();
        $response = FileService::uploadAvatar($avatar);
        return $response;
    }

    /**
     * 得到用户头像
     * @param Request $request
     * @return mixed
     */
    public function getAvatar(Request $request) {
        $id = $request->input('id');
        $response = FileService::getStaffAvatar($id);
        return $response;
    }
}