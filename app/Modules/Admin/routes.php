<?php

// Admin routes
Route::group(array('prefix' => 'admin','module'=>'admin', 'namespace' => 'App\Modules\Admin\Controllers'), function() {

    Route::controller('role', 'RoleController', App\Modules\Admin\Controllers\RoleController::actionName());
    Route::controller('staff', 'StaffController', App\Modules\Admin\Controllers\StaffController::actionName());
    Route::controller('permission', 'PermissionController', App\Modules\Admin\Controllers\PermissionController::actionName());
    Route::controller('static', 'StaticController', \App\Modules\Admin\Controllers\StaticController::actionName());
    Route::controller('permission', 'PermissionController', App\Modules\Admin\Controllers\PermissionController::actionName());
    Route::controller('/', 'DashboardController', App\Modules\Admin\Controllers\DashboardController::actionName());
});

Route::group(array('prefix' => '','module'=>'admin', 'namespace' => 'App\Modules\Admin\Controllers'), function() {
    Route::controller('/', 'IndexController', \App\Modules\Admin\Controllers\IndexController::actionName());
});

