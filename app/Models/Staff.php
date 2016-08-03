<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

use Kbwebs\MultiAuth\PasswordResets\CanResetPassword;
use Kbwebs\MultiAuth\PasswordResets\Contracts\CanResetPassword as CanResetPasswordContract;

/**
 * 职员 Model
 * Class Staff
 * @package App\Models
 *
 * @author davin.bao
 * @since 2016/7/20 9:34
 */
class Staff extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use SoftDeletes,
        Authenticatable,
        CanResetPassword,
        CacheTrait;

    protected $table = 'staff';
    protected $fillable = ['username', 'email', 'password', 'timezone', 'name','deleted_at','mobile', 'dept_id','confirm_token','avatar', 'confirmed_at'];
    protected $dates = ['deleted_at', 'confirmed_at'];
    protected $hidden = ['password', 'remember_token'];
    protected static $searchColumns = ['username', 'name'];


    public static function create(array $attributes = array()) {

        isset($attributes['password']) && empty($attributes['password']) ? $attributes = array_except($attributes, 'password') :'';
        isset($attributes['password_confirmation']) && empty($attributes['password_confirmation']) ? $attributes = array_except($attributes, 'password_confirmation') :'';
        if(!empty($attributes['password'])) $attributes['password'] = Hash::make($attributes['password']);

        return DB::transaction(function() use($attributes) {
            $model = parent::create($attributes);
            $model->roles()->attach(array_values($attributes['roles']));
            return $model;
        });
    }

    public function update(array $attributes = array()) {
        $self = $this;

        isset($attributes['password']) && empty($attributes['password']) ? $attributes = array_except($attributes, 'password') :'';
        isset($attributes['password_confirmation']) && empty($attributes['password_confirmation']) ? $attributes = array_except($attributes, 'password_confirmation') :'';
        if(!empty($attributes['password'])) $attributes['password'] = Hash::make($attributes['password']);

        if(isset($attributes['roles'])){
            DB::transaction(function() use($attributes, $self) {
                parent::update($attributes);
                $self->roles()->sync(array_values($attributes['roles']));
            });
        }else{
            return parent::update($attributes);
        }

    }

    public function remove($id){
        $self = $this;
        DB::transaction(function () use ($id, $self) {
            $roleIdList = array_column($this->roles->toArray(), 'id');
            if (is_array($roleIdList) && count($roleIdList)) {
                $self->roles()->detach($roleIdList);
            }
            parent::delete($id);
        });

    }
    /**
     * Get last seen
     *
     * @return String
     */
    public function lastSeen() {
        if (Cache::has('last_seen_' . $this->id)) {
            return Cache::get('last_seen_' . $this->id);
        } elseif ($this->last_seen) {
            return $this->last_seen;
        } else {
            return null;
        }
    }

    /**
     * Roles relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles() {
        return $this->belongsToMany('App\Models\Role', 'staff_role');
    }

    /**
     * Handles user account confirmation
     *
     * @return Array
     * @internal param \App\Models\User $user
     */
    public function confirmRegistration() {
        $this->confirm_token = null;

        $this->confirmed_at = date('Y-m-d H:i:s');
        $this->save();

        $data = [
            'username' => $this->username,
            'user_email' => $this->email,
            'created_at' => $this->created_at,
            'confirmed_at' => $this->confirmed_at
        ];

        return $data;
    }

    /**
     * 是否为超级管理员
     */
    public function isAdministrator(){
//        return $this->hasRole(Role::ROLE_ADMIN);
        return ($this->username == 'admin') ? true : false;
    }

    /**
     * 得到当前登录的用户的所有角色ID数字数组
     * @return mixed
     */
    public function getRoleIds() {
        $roleIds = self::select('staff_role.role_id')
            ->join('staff_role', 'staff.id', '=', 'staff_role.staff_id')
            ->where('staff.id', $this->id)
            ->get()
            ->toArray();
        return $roleIds;
    }

    /**
     * 得到当前登录的用户的所有权限
     * @author chuanhangyu
     * @version 4.0
     * @since 2016/7/21 14:00
     * @return array
     */
    public function getPermissions() {
        $permissions = self::select('permissions.*')
                ->join('staff_role', 'staff.id', '=', 'staff_role.staff_id')
                ->join('permission_role', 'permission_role.role_id', '=', 'staff_role.role_id')
                ->join('permissions', 'permissions.id', '=', 'permission_role.permission_id')
                ->where('staff.id', $this->id)
                ->orderBy('sort', 'asc')
                ->distinct('permissions.id')
                ->get();
        return $permissions;
    }
}
