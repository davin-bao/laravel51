<?php
/**
 * 当为调试模式时， 记录所有 SQL 日志到 storage/logs/Y-m-d_query.log
 *
 * @author davin.bao
 * @since 2016/7/15 9:34
 */
Event::listen('illuminate.query', function($query, $bindings) {
    if(!\Config::get('app.debug')){
        return;
    }
        $query =  $query.'';
        for($j=0; $j<sizeof($bindings); $j++)
        {
            $bindings[$j] = $bindings[$j] == '' ? "''" : $bindings[$j];
            $query =$str=preg_replace('/\?/','\''.$bindings[$j].'\'', $query, 1);
        }
        $query = trim(preg_replace( "/\r\n|\n/", "", $query));
        $newArr = array(date('Y-m-d H:i:s'), $query);
        $logData = implode("\t",$newArr) . "\n";

    if($logData != ''){
        $logFile = fopen(storage_path('logs/'.date('Y-m-d').'_query.log'), 'a+');
        fwrite($logFile, $logData);
        fclose($logFile);
    }
});