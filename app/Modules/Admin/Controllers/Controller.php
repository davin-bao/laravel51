<?php

namespace App\Modules\Admin\Controllers;

use App\Modules\Common\Controller as BaseController;

abstract class Controller extends BaseController
{
    public function __construct() {
        parent::__construct();
        $this->middleware('staff_permissions');
    }

}
