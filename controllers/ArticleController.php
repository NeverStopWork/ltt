<?php
/**
 * Created by PhpStorm.
 * User: xushunbin
 * Date: 2018/3/10
 * Time: 12:47
 */

namespace app\controllers;


use app\controllers\base\AuthController;
use app\models\base\Articles;
use app\models\base\Files;
use app\models\base\Users;
use app\models\Common;
use app\tools\Tools;
use Faker\Provider\File;
use yii\helpers\ArrayHelper;
use yii\imagine\Image;

class ArticleController extends AuthController
{
    /*
     *  发表文章
     */
    public function actionCreate(){
        $request = \Yii::$app -> request;
        $content = $request -> post('content','');
        if(empty($content)) return $this->returnFailedJson('请输入内容。');
        $lat = $request -> post('lat');
        $lng = $request -> post('lng');
        if(empty($lat)||empty($lng)) return $this -> returnFailedJson('缺少经纬度');
        $address = $request -> post('address');
        if(empty($address)) return $this -> returnFailedJson('缺少地址名称');
        // 新建文章
        $article = [];
        $article['content']     = $content;
        $article['uid']         = $this -> uid;
        $article['lat']         = $lat;
        $article['lng']         = $lng;
        $article['address']     = $address;
        $article['created']     = $_SERVER['REQUEST_TIME'];
        $ret1 = Articles::insertItem($article);
        if($ret1) {
            if(!empty($_FILES)) {
                if(!isset($_FILES['file'])) return $this -> returnFailedJson('图片文件参数名称为file');
                $dest = \Yii::$app -> params['img_path'];
                $subDir = '/article';
                $subDir .= '/'.date('Y',$_SERVER['REQUEST_TIME']);
                $subDir .= '/'.date('m',$_SERVER['REQUEST_TIME']);
                $subDir .= '/'.date('d',$_SERVER['REQUEST_TIME']);
                $dest   .= $subDir;
                $file_names = Tools::uploadFile($dest);
                // 生成 50x50缩略图
                foreach($file_names as $name) {
                    // 原图
                    if(file_exists($dest.'/'.$name)) {
                        $data = [];
                        $data['file_path']  = $subDir.'/'.$name;
                        $data['type']       = Files::TP_ORI; // 50X50
                        $data['uid']        = $this -> uid;
                        $data['airticle_id']= $ret1;
                        $data['created']    = $_SERVER['REQUEST_TIME'];
                        Files::insertItem($data);
                    }
                    $thumb_name = Tools::getFileName($name,'.50x50');
                    Image::thumbnail($dest.'/'.$name,100,100) -> save($dest.'/'.$thumb_name,['quality'=>80]);
                    if(file_exists($dest.'/'.$thumb_name)) {
                        // 入库缩略图
                        $data = [];
                        $data['file_path']  = $subDir.'/'.$thumb_name;
                        $data['type']       = Files::TP_50; // 50X50
                        $data['uid']        = $this -> uid;
                        $data['airticle_id']= $ret1;
                        $data['created']    = $_SERVER['REQUEST_TIME'];
                        Files::insertItem($data);
                    }
                    // 制作水印
                    $watered_name = Tools::getFileName($name,'.water');

                    Image::text($dest.'/'.$name,'@'.$this -> account,\Yii::$app->params['web_path'].'/fonts/st.ttf')
                        -> save($dest.'/'.$watered_name);
                    if(file_exists($dest.'/'.$watered_name)) {
                        // 入库水印图片
                        $data = [];
                        $data['file_path']  = $subDir.'/'.$watered_name;
                        $data['type']       = Files::TP_WATER; // 50X50
                        $data['uid']        = $this -> uid;
                        $data['airticle_id']= $ret1;
                        $data['created']    = $_SERVER['REQUEST_TIME'];
                        Files::insertItem($data);
                    }
                }
            }
            return $this -> returnSuccessJson();
        }
        return $this -> returnFailedJson('对不起，系统繁忙请稍后重试。');
    }
    /*
     * 文章列表
     * 2018年03月10日13:19:02
     */
    public function actionList() {
        $requset = \Yii::$app -> request;
        $arr  = [];
        if($requset -> isAjax) {
            sleep(2);
            $since_id = $requset -> get('since_id','');
            $count    = $requset -> get('count','10');
            $index    = $requset -> get('index','1');
            $uid      = $requset -> get('uid','');
            $model = Articles::find();
            if($since_id>0) $model  -> andWhere(['<','id',$since_id]);
            if($count)    $model -> limit($count);
            if($index.''==='2') $model -> andWhere(['uid'=>$this->uid]);
            if($index.''==='3'&&$uid>0) $model -> andWhere(['uid'=>$uid]);
            $model -> orderBy('id DESC');
            $res = $model -> asArray() -> all();
            $allAids = [];
            $allUids = [];
            foreach($res as $v){
                $allAids[] = $v['id'];
                $allUids[] = $v['uid'];
            }
            $allUsersInfo = Users::find()->select('id,account')->where(['IN','id',$allUids]) -> asArray() ->all();
            $allUsersInfo = ArrayHelper::index($allUsersInfo,'id');
            foreach($res as &$v){
                $v['created'] = $v['created']>100000?date('Y-m-d H:i',$v['created']):'';
                if($v['comments']>99) $v['comments'] = '99+';
                if($v['thumbs']>99) $v['thumbs'] = '99+';
                if(mb_strlen($v['content'])>=25){
                    $v['content'] = mb_substr($v['content'],0,25).'...';
                }
                $userName = isset($allUsersInfo[$v['uid']])?$allUsersInfo[$v['uid']]['account']:'';
                //$ac = "<a href='/article/list?account=".$this->account."&uid=".$v['uid']."&index=3'><span style='font-size: smaller;color: #007DDB;text-decoration:underline;'>".$userName."：</span></a>";
                $ac = "<span style='font-size: smaller;color: #007DDB;font-weight: 700;;'>".$userName.": </span>";
                if($userName) $v['content'] = "".$ac." ".$v['content'];
            }
            return $this -> returnSuccessJson($res);
        }
        $index = $requset -> get('index');
        //Common::appRecord();
        return $this -> renderPartial('list',['account'=>$this->account,'index'=>$index]);
    }
    /*
     * 文章详情
     */
    public function actionDetail() {
        sleep(3);
        $id = \Yii::$app -> request -> get('id');
        if(empty($id)) $id = \Yii::$app -> request -> post('id');
        //if(empty($id)) return $this ->returnFailedJson('请输入文章ID');
        echo 'account: '.\Yii::$app -> request -> get('account').'<br>';
        echo 'id: '.\Yii::$app -> request -> get('id').'<br>';
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<h1>文章详情页</h1>";
    }
}