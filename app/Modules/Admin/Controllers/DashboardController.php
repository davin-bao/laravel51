<?php
namespace App\Modules\Admin\Controllers;

use App\Modules\Admin\Controllers\Controller as BaseController;

/**
 * 控制面板
 * Class DashboardController
 * @package App\Modules\Admin\Controllers
 *
 * @author davin.bao
 * @since 2016/7/15 9:34
 */
class DashboardController extends BaseController {
    /**
     * 定义 permission list
     * @return array
     */
    public static function actionName()
    {
        return [
            'getIndex'=> json_encode(['parent'=>0, 'icon'=>'home', 'display_name'=>'控制面板', 'is_menu'=>1, 'sort'=>0, 'allow'=>1, 'description'=>''])
        ];
    }

    public function getIndex(){
        return $this->render('dashboard');
    }
}
