<?php


// Admin routes
Route::group(array('prefix' => 'admin','module'=>'admin', 'namespace' => 'App\Modules\Admin\Controllers'), function() {
    Route::controller('/', 'DashboardController', App\Modules\Admin\Controllers\DashboardController::getActionName());
});

Route::group(array('prefix' => 'index','module'=>'admin', 'namespace' => 'App\Modules\Admin\Controllers'), function() {
    Route::controller('/', 'IndexController', App\Modules\Admin\Controllers\IndexController::getActionName());
});
