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
        $model = DB::transaction(function() use($attributes) {
            $model = parent::create($attributes);

            return $model;
        });

        return $model;
    }

    public function update(array $attributes = array()){
        $model = DB::transaction(function() use($attributes) {
            $model = parent::update($attributes);
            $model->permissions()->attach(array_values($attributes['permissions']));

            return $model;
        });

        return $model;
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
            foreach(self::$searchColumns as $searchColumn) {
                $query->orWhere($query->getModel()->table.'.'.$searchColumn, "like", '%'.$match.'%');
            }

            foreach($otherFields as $searchColumn) {
                $query->orWhere($searchColumn, "like", '%'.$match.'%');
            }
        });

        return $query;
    }
}

