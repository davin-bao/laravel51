<?php

namespace App\Modules\Admin\Controllers;

use App\Modules\Common\Controller as BaseController;

/**
 * Admin module Base Controller
 * Class Controller
 * @package App\Modules\Admin\Controllers
 *
 * @author davin.bao
 * @since 2016/7/19 9:34
 */
abstract class Controller extends BaseController
{
    public function __construct() {
        parent::__construct();
        $this->middleware('staff_permissions');
    }

    /**
     * Make a admin view render
     *
     * @param null $view
     * @param array $data
     * @param array $mergeData
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function render($view = null, $data = [], $mergeData = []){
        return view('Admin::'  . $view, $data, $mergeData);
    }
}
