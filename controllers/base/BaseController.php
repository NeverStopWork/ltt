<?php
namespace app\controllers\base;

use app\models\Common;
use app\models\Constant;
use yii\base\Module;
use yii\web\Controller;
use yii\web\Response;

class BaseController extends Controller {
    public function __construct($id, Module $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
    }
    /**
     * 个数化成功或者失败请求返回数据格式'
     * 2018年03月04日20:16:17
     */
    private function _formatSuccessFailedArray($data,$status,$msg) {
        return [
            'status'    => "$status",
            'message'  => "$msg",
            'data'       => $data
        ];
    }
    /**
     * 成功返回json格式数据
     * 2018年03月04日19:55:06
     */
    public function returnSuccessJson($data=[],$status=0,$msg="请求成功"){
        Common::appRecord($data,$msg, 'success');
        $data = $this -> _formatSuccessFailedArray($data,$status,$msg);
        return $this -> _responseData($data);
    }
    /**
     * 响应数据
     * 2018年03月04日20:19:53
     */
    private function _responseData($data) {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        \Yii::$app->response->data = $data;
        return \Yii::$app->response;
    }
    /**
     * 失败返回json格式数据
     * 2018年03月04日19:55:06
     */
    public function returnFailedJson($msg="请求失败",$status=Constant::FAILED,$data=[]){
        Common::appRecord($data,$msg,'failed');
	$data = (object)$data;
        $data = $this -> _formatSuccessFailedArray($data,$status,$msg);
        return $this -> _responseData($data);
    }
}
