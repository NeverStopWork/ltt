<?php
/**
 * Created by PhpStorm.
 * User: xushunbin
 * Date: 2018/3/10
 * Time: 15:11
 */

namespace app\models;


class Common
{
    /*
     * 记录日志
     */
    private static function _appRecord($log) {
        $route = \Yii::$app->requestedRoute;
        $time_str = date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME']);
        $file_str   = date('Y-m-d',$_SERVER['REQUEST_TIME']);
        $dir_str   = date('Y-m',$_SERVER['REQUEST_TIME']);
        $log_filename = $file_str . ".log";
        $pid = getmypid();
        $log_path = \Yii::$app->params['api.log.path'].'/'.$dir_str;
        if (!file_exists($log_path)) mkdir($log_path);
        $file = $log_path . '/' . $log_filename;
        file_put_contents($file, '【' .$time_str.'】' . "pid: $pid " . "router=" . $route .' '. $log . "\n", FILE_APPEND);
    }
    public static function appRecord($data,$msg,$type){
        self::_appRecord('Response '.$type.',data:'.json_encode($data,JSON_UNESCAPED_UNICODE).',msg:'.$msg);
    }
}