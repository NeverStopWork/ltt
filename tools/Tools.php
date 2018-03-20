<?php
namespace app\tools;


use app\models\Constant;
use yii\imagine\Image;

class Tools
{
    /*
     * 文件上传
     * 2018年03月17日09:43:47
     * @param   $filepath   上传文件的目的文件路径
     * @param   $file       上传文件时用的字段名称
     * @param   $onlyimg    只验证是否是图片
     */
    public static function uploadFile($filepath,$file='file',$onlyimg=1){
        if (count($_FILES) == 0) return false;
        if (!file_exists($filepath)) mkdir($filepath, 0777, true);
        $fileInfo = $_FILES[$file];
        $len = count($fileInfo['name']);
        $uploaded_files = [];
        $filename = '';
        for($i=0;$i<$len;++$i){
            if($onlyimg) {
                $type = $fileInfo['type'][$i];
                if(!in_array($type,Constant::$allow_img_mime)) continue;
            }
            $upload_file_name = $fileInfo['name'][$i];

            $filename = self::getFileName($upload_file_name);
            $real_file_path = $filepath.'/'.$filename;
            $tmp_name = $fileInfo['tmp_name'][$i];
            if(is_uploaded_file($tmp_name)){
                if(move_uploaded_file($tmp_name,$real_file_path)){
                    if(file_exists($real_file_path)) {
                        $uploaded_files[] = $filename;
                        chmod($real_file_path,'0777');
                    }
                }
            }
        }
        return $uploaded_files;
    }
    /*
     * 生成服务器文件名称
     * 2018年03月17日09:47:59
     * @param   $filename   原文件名称
     * @param   $des        给生成的文件名添加的描述 例：100x100
     */
    public static function getFileName($filename,$des=''){
        $tmpArr = explode('.',$filename);
        $ext = strtolower($tmpArr[count($tmpArr)-1]);
        return  uniqid(microtime(true)).$des.'.'.$ext;
    }

}