<?php

namespace App\Models;

use App\Components\QueryBuilder;
use App\Exceptions\NoticeMessageException;
use \DB;
use Illuminate\Database\Eloquent\Model as ModelBase;

/**
 * Model 基类
 * Class Model
 * @package App\Models
 *
 * @author davin.bao
 * @since 2016/7/15 9:34
 */
abstract class Model extends ModelBase
{
    /**
     * 创建Model 需要的验证规则
     *
     * 如：
     * return [
     *    'code' => 'required|min:4|max:30|unique:companies',
     *    'name' => 'required|min:3',
     *    'address' => 'required|min:6',
     *    'bank_name' => 'min:4',
     *    'bank_no' => 'min:9',
     *    'legal_person' => 'required|min:2',
     *    ];
     */
    protected static function createRules(){
        return [];
    }
    /**
     * 修改 Model 需要的验证规则
     *
     * 如：
     * return [
     *    'code' => 'required|min:4|max:30|unique:companies,code, ' . $self->id,
     *    'name' => 'required|min:3',
     *    'address' => 'required|min:6',
     *    'bank_name' => 'min:4',
     *    'bank_no' => 'min:9',
     *    'legal_person' => 'required|min:2',
     *    ];
     */
    protected static function updateRules(){
        return [];
    }

    /**
     * 重写 QueryBuilder
     * @return QueryBuilder
     */
    protected function newBaseQueryBuilder()
    {
        $conn = $this->getConnection();

        $grammar = $conn->getQueryGrammar();

        return new QueryBuilder($conn, $grammar, $conn->getPostProcessor());
    }

    /**
     * 创建 Model
     * @param array $attributes
     * @return static
     * @throws \App\Exceptions\NoticeMessageException
     */
    public static function create(array $attributes = array()) {
        $v = \Validator::make($attributes, self::createRules());

        if ($v->fails()){
            throw new NoticeMessageException($v->errors()->first(), 500);
        }

        $model = parent::create($attributes);

        return $model;
    }

    /**
     * 更新 Model
     * @param array $attributes
     * @return mixed
     * @throws \App\Exceptions\NoticeMessageException
     */
    public function update(array $attributes = array()) {
        $self = $this;

        $v = \Validator::make($attributes, self::updateRules());

        if ($v->fails()){
            throw new NoticeMessageException($v->errors()->first(), 500);
        }

        parent::update($attributes);

        return self::get($self->id, true);
    }
}