<?php
/**
 * Created by PhpStorm.
 * User: xushunbin
 * Date: 2018/3/4
 * Time: 下午8:09
 */

namespace app\models;


class Constant
{
    const SUCCESS = 0;// 请求成功返回码
    const FAILED    = 1;// 请求失败返回码
    const USER_KEY = "";


    public static $allow_img_mime = ['image/jpeg','image/jpeg','image/gif','image/png'];
}