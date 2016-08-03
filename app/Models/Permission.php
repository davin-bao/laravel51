<?php

namespace App\Models;

//use Prettus\Repository\Contracts\Transformable;
//use Prettus\Repository\Traits\TransformableTrait;
use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    use CacheTrait;

    const IS_MENU_YES = 1;  //菜单
    const IS_MENU_NO = 0;   //非菜单

    protected $table = 'permissions';

    protected $fillable = ['id', 'parent', 'icon', 'uri', 'action', 'display_name', 'description', 'is_menu', 'allow', 'sort'];

    protected $appends = ['icon_html', 'sub_permission'];

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'permission_role');
    }

    public function getIconHtmlAttribute()
    {
        return $this->attributes['icon'] ? '<i class="fa fa-' . $this->attributes['icon'] . '"></i>' : '';
    }

    public function getNameAttribute($value)
    {
        if(starts_with($value, '#')) {
            return head(explode('-', $value));
        }
        return $value;
    }

    public function setNameAttribute($value)
    {
        $this->attributes['uri'] = ($value == '#') ? '#-' . time() : $value;
    }

    public function getSubPermissionAttribute()
    {
        return (empty($this->attributes['parent'])) ? $this->where('parent', basename($this->attributes['action']))->orderBy('sort', 'asc')->get() : null;
    }


    /**
     * Get permission
     *
     * @param string $action
     * @param mixed $roleIds
     * @return mixed
     */
    public static function getPermission($action, $roleIds)
    {
        $cacheKey = md5($action . serialize($roleIds));
        $permission = self::cache('permission|_'.$cacheKey, function() use($action, $roleIds) {
            $result = self::select('permissions.*')
                ->join('permission_role', 'permission_role.permission_id', '=', 'permissions.id', 'left')
                ->join('roles as roles', 'roles.id', '=', 'permission_role.role_id', 'left')
                ->where('permissions.action', $action)
                ->whereIn('roles.id', $roleIds)
                ->orWhere('permissions.allow', '1');

            if ($roleIds) {
                $result->whereIn('roles.id', $roleIds);
            } else {
                $result->where('roles.id', null);
            }

            $permission = $result->first();

            return $permission;
        });

        return $permission;
    }

    /**
     * 判断当前用户是否有 action 权限
     * @param $uri
     * @return mixed
     */
    public static function can($action){
        $roleIds = (\Auth::staff()->check()) ? \Auth::staff()->get()->getRoleIds() : null;
        return self::getPermission($action, $roleIds);
    }


    /**
     * 获取检索query
     * @param $query
     * @param $match 检索信息
     * @return mixed query
     */
    public static function getSearchQuery($query, $match, $otherFields = array(), $hasAccess = false){
        //添加数据访问权限
        if($hasAccess) {
            $query = self::access($query);
        }

        if(empty($match)) {
            return $query;
        }
        $query = $query->where(function($query) use ($match, $otherFields) {
            foreach(static::$searchColumns as $searchColumn) {
                $query->orWhere($query->getModel()->table.'.'.$searchColumn, "like", '%'.$match.'%');
            }

            foreach($otherFields as $searchColumn) {
                $query->orWhere($searchColumn, "like", '%'.$match.'%');
            }
        });

        return $query;
    }

}