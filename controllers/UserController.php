<?php
namespace app\controllers;

use app\controllers\base\AuthController;
use app\controllers\base\BaseController;
use app\models\base\Users;
use app\models\Common;
use app\models\Constant;

class UserController extends AuthController
{
        /**
         * 登录
         * 2018年03月06日00:24:38
         */
        public function actionLogin(){
            // 登录
            $request    = \Yii::$app -> request;
            $account    = $request -> post('account','');
            $password  = $request -> post('password','');
            if($password) $password = md5($password);
            if(empty($account) || empty($password)) return $this -> returnFailedJson("对不起，请输入账号或者密码。");
            $userInfo = Users::find() -> where(['account'=>$account]) ->asArray() -> one();
            if($userInfo) {
                if($userInfo['password']!=$password){
                    return $this -> returnFailedJson('密码错误');
                }
                $cache = \Yii::$app -> cache;
                $cache -> set($account,$userInfo);
                $userInfo['created']    = date('Y-m-d H:i:s',$userInfo['created']);
                return $this -> returnSuccessJson($userInfo);
            } else {
                $userInsert = [
                    'account'   => $account,
                    'password' => $password,
                    'created'   => time(),
                ];
                $ret = Users::insertItem($userInsert);
                $userInsert['created']      = date('Y-m-d H:i:s',$userInsert['created']);

                if($ret) {
                    $cache = \Yii::$app -> cache -> set($account,$password);
                    if(!$cache) \Yii::$app -> cache -> set($account,$password);
                    return $this -> returnSuccessJson($userInsert,Constant::SUCCESS,'账户注册成功。');
                }
                return $this -> returnFailedJson("对不起，用户名与密码不匹配，请重新输入。");
            }
        }
    /**
     * 用户注册
     * 2018年03月05日22:44:46
     */
    public function actionRegister() {
        $request        = \Yii::$app -> request;
        $account        = $request -> post('account','');
        $password_1   = $request -> post('password_1','');
        $password_2   = $request -> post('password_2','');
        if(empty($account)) {
            return $this -> returnFailedJson("对不起，您还没有填写用户名。");
        }
        if(empty($password_1) || empty($password_2)) {
            return $this -> returnFailedJson('对不起，您还没有填写用户密码。');
        }
        if($password_1 != $password_2) {
            return $this -> returnFailedJson('对不起，您输入的两次密码不相同，请重新输入。');
        }
        $userInsert = [
            'account'   => $account,
            'password' => $password_1,
            'created'   => time(),
        ];
        $ret = Users::insertItem($userInsert);
        $userInsert['created']      = date('Y-m-d H:i:s',$userInsert['created']);

        if($ret) {
            $cache = \Yii::$app -> cache -> set($account,$password_1);
            if(!$cache) \Yii::$app -> cache -> set($account,$password_1);
            return $this -> returnSuccessJson($userInsert);
        }

        return $this -> returnFailedJson("对不起，注册失败，服务器繁忙请稍后重试。");
    }
    /*
     * 登出系统
     * 2018年03月06日00:10:27
     */
    public function actionLogout() {
        $request    = \Yii::$app -> request;
        $account    = $request -> post('account','');
        $password  = $request -> post('password','');
        $cache = \Yii::$app -> cache;
        if($cache -> get('$account') == $password) {
            // 登录中
            if($cache -> delete($account)) return $this -> returnSuccessJson();
            else $cache -> delete($account);
        }
        $cache -> delete($account);
        return $this -> returnSuccessJson();
    }
        /**
         * 测试用
         * 2018年03月04日19:05:43
         */
        public function actionTest(){
            $account = \Yii::$app -> request -> post('account');
            $cache = \Yii::$app -> cache;
            var_dump($cache -> get($account));
            //Common::appRecord('哈哈哈哈哈哈哈哈哈哈','请求成功','success');
            return $this -> returnSuccessJson();
        }
}