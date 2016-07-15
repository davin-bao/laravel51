<?php
namespace App\Modules\Admin\Controllers;

use App\Models\Permission;
use App\Modules\Common\Controller as BaseController;
use Illuminate\Support\Facades\DB;

/**
 * 登录
 * Class LoginController
 * @package App\Modules\Admin\Controllers
 *
 * @author davin.bao
 * @since 2016/7/15 9:34
 */
class LoginController extends BaseController {
    /**
     * 定义 permission list
     * @return array
     */
    public static function getActionName()
    {
        return [
            'getIndex'=> json_encode(['fid'=>0, 'icon'=>'home', 'display_name'=>'登录', 'is_menu'=>1, 'sort'=>0, 'allow'=>1, 'description'=>''])
        ];
    }
    public function getIndex(){

        $p = Permission::find(1);
        Permission::create(['name'=> $p->name]);

        DB::transaction(function () {
            throw  new \App\Exceptions\ErrorMessageException('sdfsd', null, 600);
        });
        echo 'login';
    }
}
