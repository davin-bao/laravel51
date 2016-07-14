<?php

namespace App\Models;

//use Prettus\Repository\Contracts\Transformable;
//use Prettus\Repository\Traits\TransformableTrait;
use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    use CacheTrait;

    protected $table = 'permissions';

    protected $fillable = ['fid', 'icon', 'uri', 'action', 'display_name', 'description', 'is_menu', 'sort'];

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
        return ($this->attributes['fid'] == 0) ? $this->where('fid',$this->attributes['id'])->orderBy('sort', 'asc')->get() : null;
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
}
