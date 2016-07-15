<?php
namespace App\Services;

use Illuminate\Database\Query\Builder as Builder;

/**
 * QueryBuilder.
 *
 * 重写 QueryBuilder 类， 如果传递的参数无效，则无需生成相应的查询
 *
 * @author davin.bao
 * @since 2016/7/15 9:34
 */
class QueryBuilder extends Builder {
    /**
     * 如果值为［未定义］，则不需要加where条件
     * @param $column
     * @param null $operator
     * @param null $value
     * @param string $boolean
     * @return $this
     */
    public function queryWhere($column, $operator = null, $value = null, $boolean = 'and')
    {
        if (func_num_args() == 2) {
            list($value, $operator) = [$operator, '='];
        }

        if($value == '' || $value === -1 || $value === '-1'|| $value == null || $value === 'undefined') return $this;
        return $this->where($column, $operator, $value, $boolean);
    }

    public function queryWhereIn($column, $values, $boolean = 'and', $not = false)
    {
        if($values == '' ||
            $values === '-1'||
            $values === -1||
            $values == null ||
            $values === 'undefined' ||
            count($values) == 0 ||
            $values[0] === '' ||
            $values[0] === '-1' ||
            $values[0] === -1
        ) return $this;
        return $this->whereIn($column, $values, $boolean, $not);
    }

    public function queryDateWhere($column, $begin = null, $end = null, $boolean = 'and'){
        $query = $this;

        $query = $query->where($column, '>=', formatBeginDate($begin), $boolean);
        $query = $query->where($column, '<=', formatEndDate($end), $boolean);

        return $query;
    }

}


