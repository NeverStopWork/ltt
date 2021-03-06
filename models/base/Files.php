<?php
/**
 * Created by PhpStorm.
 * User: xushunbin
 * Date: 2018/3/17
 * Time: 15:48
 */

namespace app\models\base;


use yii\db\ActiveRecord;

class Files extends ActiveRecord
{
    private static $saveFields = ['file_path','type','uid','airticle_id','created'];
    const TP_ORI    = 1; // 原图
    const TP_50     = 2; // 50x50缩略图
    const TP_WATER  = 3; // 水印图片
    public static function tableName()
    {
        return 'files'; // TODO: Change the autogenerated stub
    }
    /*
     * 插入数据
     * 2018年03月17日15:49:21
     */
    public static function insertItem($data){
        $m = new self();
        foreach(self::$saveFields as $v) {
            if(isset($data[$v])) $m -> $v = $data[$v];
        }
        $ret = $m -> save();
        if($ret) return $m -> attributes['id'];
        else return 0;
    }
}