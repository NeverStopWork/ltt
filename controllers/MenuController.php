<?php
/**
 * Created by PhpStorm.
 * User: xushunbin
 * Date: 2018/3/6
 * Time: 22:50
 */

namespace app\controllers;


use app\controllers\base\AuthController;

class MenuController extends AuthController
{
    /*
     * 菜单列表
     * 2018年03月06日22:50:50
     */
    public function actionList() {
        $account = \Yii::$app -> request -> get('account');
        $array = [
            'avatar'        => \Yii::$app -> params['WebHost'].'/img/001.jpg',
            'account'       => $account,
            'menu'          => [
                ['title' => ''],
                [],
                [],
                []
            ],
        ];
    }
}