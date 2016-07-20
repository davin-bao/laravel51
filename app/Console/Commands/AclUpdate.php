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
use Mockery\CountValidator\Exception;

/**
 * 重建ACL列表，并保留以前的设置
 *
 * @package App\Console\Commands
 *
 * @author davin.bao
 * @since 2016/7/15 9:34
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

        try {
            $routeData = $this->getRouteData();

            DB::beginTransaction();
            foreach ($routeData as $action => $item) {
                $attributes = null;
                $attributes = json_decode($item['name'], true);
                $attributes['action'] = $action;
                $attributes['uri'] = $item['uri'];
                $parent = array_get($attributes, 'parent', null);

                if(($parent === 0)  || !isset($attributes['display_name'])){
                    $attributes['fid'] = 0;
                }else{
                    $parentPermission = Permission::where('action', "like", '%'.$parent)->first();
                    if($parentPermission){
                        $attributes['fid'] = $parentPermission->id;
                    }
                }

                if(!isset($attributes['fid'])){
                   throw new \Exception('Action '.$action.' 未定义 parent 或定义的 parent 不合法');
                }

                $oldPermission = Permission::where('action', $action)->first();
                if($oldPermission){
                    $roles = $oldPermission->roles;
                    $oldPermission->delete();
                }else{
                    $roles = Role::where('name', Role::NAME_ADMINISTRATOR)->get();
                }

                $permission = Permission::where('action', $action)->first();
                if(!$permission){
                    $permission = new Permission($attributes);
                }
                $permission->save();

                $roles->each(function($role) use ($permission) {
                    $permission->roles()->sync([$role->id]);
                });

                $this->comment("Added perimission " . $action . "\n");
            }

            $cache = $this->getCacheInstance(['permissions']);
            $cache->flush();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error($e->getMessage(). "\n" . $e->getTraceAsString() . "\n");
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