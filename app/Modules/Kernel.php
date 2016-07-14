<?php

namespace App\Modules;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \App\Modules\Common\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Modules\Common\Middleware\VerifyCsrfToken::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
//        'auth' => \App\Modules\Common\Middleware\Authenticate::class,
//        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest' => \App\Modules\Common\Middleware\RedirectIfAuthenticated::class,
        'last_activity' => \App\Modules\Common\Middleware\LastActivity::class,
        'staff_permissions' => 'App\Modules\Common\Middleware\StaffPermissions',

        'role' => Zizaco\Entrust\Middleware\EntrustRole::class,
        'permission' => Zizaco\Entrust\Middleware\EntrustPermission::class,
        'ability' => Zizaco\Entrust\Middleware\EntrustAbility::class,
    ];
}
