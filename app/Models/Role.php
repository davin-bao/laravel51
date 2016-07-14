<?php

namespace App\Models;

use Zizaco\Entrust\EntrustRole;
//use Prettus\Repository\Contracts\Transformable;
//use Prettus\Repository\Traits\TransformableTrait;

class Role extends EntrustRole
{
    const NAME_ADMINISTRATOR = 'administrator';

    protected $fillable = ['name', 'display_name', 'description'];

    /**
     * 用户列表
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\AdminUser');
    }

    /**
     * 权限列表
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany('App\Models\Permission', 'permission_role');
    }
}

