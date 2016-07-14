<?php

namespace App\Console\Commands;

use App\Models\Action;
use App\Models\ActionRoles;
use App\Models\CacheTrait;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/**
 * Command will rebuild the permissions table. This will cause all routes
 * and actions to become public. Be aware when running this command on
 * production. It will destroy all your permissions data.
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package App\Console\Commands
 */
class AclUpdate extends Command
{
    use CacheTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'acl:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the access control list.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("Clearing permissions... \n");

        // Delete old data
        //$response = Action::deleteAllData();

        //(null !== $response) ? $this->error("\n" . $response . "\n") : null;


        try {
            $routeData = $this->getRouteData();

            DB::beginTransaction();
            foreach ($routeData as $action => $item) {
                $attributes = json_decode($item['name'], true);
                $attributes['action'] = $action;
                $attributes['uri'] = $item['uri'];

                $permission = Permission::where('action', $action)->first();
                if(!$permission){
                    $permission = new Permission($attributes);
                }

                $oldPermission = Permission::where('action', $action)->first();
                if($oldPermission){
                    $roles = $oldPermission->roles;
                    $oldPermission->delete();
                }else{
                    $roles = Role::where('name', Role::NAME_ADMINISTRATOR)->get();
                }

                $permission->save();

                $roles->each(function($role) use ($permission) {
                    $permission->role()->associate($role);
                });

                $this->comment("Added perimission " . $action . "\n");
            }

            $cache = $this->getCacheInstance(['permissions']);
            $cache->flush();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("\n" . $e->getTraceAsString() . "\n");
        }

    }

    /**
     * Gets the route data
     *
     * @return array
     */
    protected function getRouteData()
    {
        $allRoutes = Route::getRoutes();

        $routeData = [];

        foreach ($allRoutes as $route) {
            if (!preg_match('/_debugbar/', $route->getUri()) && !preg_match('/terminal/', $route->getUri())) {
                $uri = preg_replace('#/{[a-z\?]+}|{_missing}|/{_missing}#', '', $route->getUri());
                $routeData[$route->getActionName()]['name'] = $route->getName();
                $routeData[$route->getActionName()]['uri'] = $uri;
            }
        }

        return $routeData;
    }

}