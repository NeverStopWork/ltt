<?php
/**
 * Created by PhpStorm.
 * User: xushunbin
 * Date: 2018/3/6
 * Time: 21:52
 */

namespace app\controllers;


use app\controllers\base\AuthController;
use yii\helpers\ArrayHelper;

class IndexController extends AuthController
{
    /*
     * 登录后进图app首页
     * 2018年03月06日21:53:13
     */
    public function actionList() {
        $arr[] = [
            'id'          => '1',
            'lat'         => '40.05891800',
            'lng'         => '116.31262100',
        ];
        $arr[] = [
            'id'          => '2',
            'lat'         => '40.05891800',
            'lng'         => '116.31262100',
        ];
        ArrayHelper::multisort($arr,'id',SORT_DESC);
        return $this -> returnSuccessJson($arr);
    }
   public function actionTest(){
	phpinfo();	
  }

}
