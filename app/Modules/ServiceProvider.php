<?php namespace App\Modules;

use Illuminate\Routing\Router;
use Illuminate\Filesystem\Filesystem;

class ServiceProvider extends  \Illuminate\Support\ServiceProvider
{
    protected $files;

    public function boot(Router $router)
    {

        if(is_dir(app_path().'/Modules/')) {

            $modules = config("module.modules");

            foreach($modules as $module)  {

                $routes = app_path().'/Modules/'.$module.'/routes.php';
                $helper = app_path().'/Modules/'.$module.'/helper.php';
                $views  = app_path().'/Modules/'.$module.'/Views';
                $trans  = app_path().'/Modules/'.$module.'/Translations';

                if($this->files->exists($routes)) include $routes;
                if($this->files->exists($helper)) include $helper;
                if($this->files->isDirectory($views)) $this->loadViewsFrom($views, $module);
                if($this->files->isDirectory($trans)) $this->loadTranslationsFrom($trans, $module);
            }
        }

        parent::boot($router);
    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {

        $this->files = new Filesystem;
    }

}