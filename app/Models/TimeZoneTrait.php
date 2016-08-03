<?php

namespace App\Models;

use App\Exceptions\InterruptMessageException;
use Auth;
use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

/**
 * Cache trait 支持使用int 替代 timestamp
 *
 * @author davin.bao <davin.bao@gmail.com>
 * @package App\Models
 */
trait TimezoneTrait
{
    /**
     * __get Magic function override
     * you need config the "dates" attribute if have custom date field, like below:
     * protected $dates = ['repaid_at'];
     *
     * @param $key
     * @return static
     */
    public function __get($key){
        $value = parent::__get($key);
        //直接访问字段， 则返回 Carbon 对象
        if(in_array($key, $this->getDates()) && is_numeric($value)) {
            return Carbon::createFromTimestamp($value);
        }
        return $value;
    }

    /**
     * __set Magic function override
     * default save "app.timezone" date value
     * @param $key
     * @param $value
     * @throws InterruptMessageException
     */
    public function __set($key, $value){

        if(in_array($key, $this->getDates())){
            if($value instanceof Carbon){
                $value = $value->timezone(Config::get('app.timezone'))->timestamp;
            }else if(!is_null($value)){
                throw new InterruptMessageException('The ' . get_called_class() . '->'. $key .' expect Carbon object!');
            }
        }

        parent::__set($key, $value);
    }

    /**
     * set save date format is timestamp
     * @return string
     */
    protected function getDateFormat(){
        return 'U';
    }

    /**
     * if use toArray, will return default date value
     *
     * @param DateTime $date
     * @return mixed
     */
    protected function serializeDate(DateTime $date){
        $timezone = Config::get('app.timezone');
        if(isset(Auth::staff()->get()->timezone) && !empty(Auth::staff()->get()->timezone)){
            $timezone = Auth::staff()->get()->timezone;
        }
        return $date->timezone($timezone)->format('Y-m-d H:i');
    }

}