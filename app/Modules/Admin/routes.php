<?php


// Admin routes
Route::group(array('prefix' => 'admin','module'=>'admin', 'namespace' => 'App\Modules\Admin\Controllers'), function() {
    Route::controller('/', 'DashboardController', App\Modules\Admin\Controllers\DashboardController::getActionName());
});

Route::group(array('prefix' => '','module'=>'admin', 'namespace' => 'App\Modules\Admin\Controllers'), function() {
    Route::controller('login', 'LoginController', App\Modules\Admin\Controllers\LoginController::getActionName());
});
