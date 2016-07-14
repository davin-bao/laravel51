<?php
namespace App\Modules\Admin\Controllers;

use App\Modules\Admin\Controllers\Controller as BaseController;

class DashboardController extends BaseController {
    /**
     * 定义 permission list
     * @return array
     */
    public static function getActionName()
    {
        return [
            'getIndex'=> json_encode(['fid'=>0, 'icon'=>'home', 'display_name'=>'控制面板', 'is_menu'=>1, 'sort'=>0, 'allow'=>1, 'description'=>''])
        ];
    }

    public function getIndex(){
        echo 'dashboard';
    }
}
