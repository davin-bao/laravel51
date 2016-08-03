<?php

namespace App\Models;

class Role extends Model
{
    const NAME_ADMINISTRATOR = 'administrator';

    protected $fillable = ['name', 'display_name', 'description'];

    protected static $searchColumns = ['name', 'display_name', 'description'];

    /**
     * 用户列表
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function staff()
    {
        return $this->belongsToMany('App\Models\Staff', 'staff_role');
    }

    /**
     * 权限列表
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany('App\Models\Permission', 'permission_role');
    }

    public static function create(array $attributes = array()) {
        $model = \DB::transaction(function() use($attributes) {
            $model = parent::create($attributes);

            return $model;
        });

        return $model;
    }

    public function update(array $attributes = array()){
        $model = \DB::transaction(function() use($attributes) {
            $model = parent::update($attributes);
            //更新权限列表
            if(isset($attributes['permissions'])){
                $model->permissions()->attach(array_values($attributes['permissions']));
            }

            return $model;
        });

        return $model;
    }

    /**
     * 更新角色的权限
     * @param $permissions
     * @return array
     *
     * @author chuanhangyu
     * @since 2016/8/2 14:30
     */
    public function updatePermission($permissions) {
        return $this->permissions()->sync($permissions);
    }

}

