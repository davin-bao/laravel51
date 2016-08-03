<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;

/**
 * Cache trait 支持使用缓存依赖
 *
 * @author davin.bao <davin.bao@gmail.com>
 * @package App\Models
 */
trait CacheTrait
{
    /**
     * Gets the cache engine instance. This method is used when the cache is referenced
     * with tags. In some cases like if the active cache driver is file, then the tags
     * method does not work and throws exception.
     *
     * @param array $cacheTags
     * @return mixed
     */
    protected static function getCacheInstance(array $cacheTags)
    {
        try {
            return Cache::tags($cacheTags);
        } catch (\ErrorException $e) {
            return Cache::getFacadeRoot();
        }
    }

    protected static $dependencyCacheKey = 'MODEL_DEPENDENCY_KEY';

    /**
     * 缓存数据
     * @param $cacheKey
     * @param $callback
     * @param int $minutes 默认 24小时
     * @return mixed
     */
    protected static function cache($cacheKey, $callback, $minutes = 1440){

        $dependencyKeys = Cache::get(self::$dependencyCacheKey);
//        \Log::info('Get DependencyKeys :' . json_encode($dependencyKeys));

        $dependencyKeys = is_array($dependencyKeys) ? $dependencyKeys : [];

        if(!is_array($dependencyKeys) || !in_array($cacheKey, $dependencyKeys)){
            array_push($dependencyKeys, $cacheKey);
            Cache::forever(self::$dependencyCacheKey, $dependencyKeys);
//            \Log::info('Saved DependencyKey :' . $cacheKey);
        }

        if(Cache::has(strtolower($cacheKey))){
//            \Log::info('Load data from cache :' . $cacheKey);
            //如果开启DEBUG模式，则关闭缓存
            if(!\Config::get('app.debug')) return Cache::get(strtolower($cacheKey));
        }

        $result = $callback();

        Cache::put(strtolower($cacheKey), $result, $minutes);
//        \Log::info('Save data to cache: ' . $cacheKey . ' times: ' . $minutes . ' minutes');

        return $result;
    }

    /**
     * 删除所有缓存
     */
    protected function forgetAllDependencyCache(){

        $dependencyKeys = Cache::get(self::$dependencyCacheKey);

        if(is_array($dependencyKeys)){
            foreach($dependencyKeys as $cacheKey){

//                \Log::info('Cache key: ' . $cacheKey . ' this class name: ' . strtolower(str_replace('App\\Models\\', '', get_class($this))));

                $modelName = strtolower(str_replace('App\\Models\\', '', get_class($this)));
                $cacheKeyNames = explode('|', $cacheKey);

                if(in_array($modelName, $cacheKeyNames)){
//                    \Log::info('forget cache key: ' . $cacheKey);
                    Cache::forget($cacheKey);
                    $newDependencyKeys = [];
                    foreach($dependencyKeys as $item){
                        if($item !== $cacheKey){
                            array_push($newDependencyKeys, $item);
                        }
                    }

                    Cache::forever(self::$dependencyCacheKey, $newDependencyKeys);
                }
            }
        }

    }

    /**
     * 添加事件
     */
    public static function bootCacheTrait()
    {
        static::saving(function($model){
            \Log::info(get_class($model).' saving ...');
            $model->forgetAllDependencyCache();
        });

        self::deleting(function($model){
            \Log::info(get_class($model).' deleting ...');
            $model->forgetAllDependencyCache();
        });
    }
}