<?php
/**
 * Created by PhpStorm.
 * User: xushunbin
 * Date: 2018/3/4
 * Time: 下午6:58
 */

namespace app\controllers\base;
use app\controllers\base\BaseController;
use app\models\base\Users;
use yii\base\Module;
use yii\web\Response;

class AuthController extends BaseController
{
    public $uid = 0;
    public $account = '';
    private $unchecks = ['user/login','user/register'];
    public function __construct($id, Module $module, array $config = []) {
        parent::__construct($id, $module, $config);
        \Yii::$app->user->enableSession = false;
    }
    /**
     * 权限验证
     * 2018年03月04日19:04:49
     */
    public function beforeAction($action)
    {
        $c = \Yii::$app->controller->id;
        $a = \Yii::$app->controller->action->id;
        if (!in_array("$c/$a", $this->unchecks)) {
            // 验证权限
            $request     = \Yii::$app -> request;
            if($request -> isPost) {
                $account     = $request -> post('account','');
            } elseif($request -> isGet) {
                $account     = $request -> get('account','');
            }
            if($account) {
                $cache = \Yii::$app -> cache;
                $login = $cache -> get($account);
                if(isset($login['id'])){
                    $this -> uid = $login['id'];
                    $this -> account = $account;
                    return true;
                } else {
                    $cache -> delete($account);
                }
            }
            $this->returnFailedJson("对不起，您还没有登录哦或者登录过期啦，请重新登录。");
            return false;
        }
        return true;
    }
    /**
     * 生成签名
     * 2018年03月04日20:53:58
     */
    public function setAppSecret($length){
        $str = 'QWERTYUIOPASDFGHJKLZXCVBNMqwertyuopasdfghjklzxcvbnm1234567890000';
        $sign = '';
        $len = strlen($str);
        for($i=0;$i<$length;$i++){
            $index = random_int(0,$len);
            $tmp = substr($str,$index,1);
            $sign .= $tmp;
        }
        return $sign;
    }
}