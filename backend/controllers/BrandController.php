<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/3
 * Time: 13:56
 */

namespace backend\controllers;


use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;

class BrandController extends Controller
{
    //关闭crf  yii2跨域提交
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        //查询不是删除状态的
        $query = Brand::find()->where(['>=', 'status', '0']);
        //使用分页工具
        $page = new Pagination();
        $page->totalCount = $query->count();
        //设置每页显示条数
        $page->pageSize = 2;
        //查询数据limit
        $brands = $query->limit($page->limit)->offset($page->offset)->all();
        //显示页面
        return $this->render("index", ['model' => $brands, 'page' => $page]);
    }

    /**
     * 添加功能
     * @return string|\yii\web\Response
     */
    public function actionAdd()
    {
        $model = new Brand();
        $request = new Request();
        //验证post提交
        if ($request->isPost) {
            $model->load($request->post());
            //验证数据
            if ($model->validate()) {
                //获取文件的后缀
                $model->save();
                //跳转到首页
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect('index');
            } else {
                //打印错误信息
                var_dump($model->getErrors());
            }
        }
        //显示添加表单
        return $this->render('add', ['model' => $model]);
    }

    /**
     * 删除功能
     */
    public function actionDelete($id)
    {
        //删除将status状态改为-1表名为删除
        $result = Brand::updateAll(['status' => -1], ['id' => $id]);
        if ($result) {
            //修改成功 返回数据
            echo 1;
        }
    }

    public function actionEdit($id)
    {
        $request = new Request();
        //获取修改的数据
        $model = Brand::findOne(['id' => $id]);
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                $model->save();
                //跳转
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect('index');
            } else {
                //打印错误信息
                var_dump($model->getErrors());
            }
        }
        //显示修改表单
        return $this->render('add', ['model' => $model]);
    }

    //ajax处理上传图片(七牛云)
    public function actionUpload()
    {
        //判断是不是post提交的
        if (\Yii::$app->request->isPost) {
            $imgfile = UploadedFile::getInstanceByName('file');
            //判断是否有上传文件
            if ($imgfile) {
                $ext = $imgfile->extension;
                $file = '/upload/' . uniqid() . '.' . $ext;
                //上传文件永久保存到Uploads
                $imgfile->saveAs(\Yii::getAlias('@webroot') . $file, false);
                // 需要填写你的 Access Key 和 Secret Key
                $accessKey = "GmwzGPby8zB4kwx9VCIMo2-pyQwr7mehcKp8uRCx";
                $secretKey = "tqMfgKG8RtYLqyRWE_G1wquhUTm689rMU23iv3GP";
                $bucket = "yii2shop";
                $url = "oyxeyx2in.bkt.clouddn.com";
// 构建鉴权对象
                $auth = new Auth($accessKey, $secretKey);

// 生成上传 Token
                $token = $auth->uploadToken($bucket);

// 要上传文件的本地路径
                $filePath = \Yii::getAlias('@webroot') . $file;

// 上传到七牛后保存的文件名
                $key = $file;

// 初始化 UploadManager 对象并进行文件的上传。
                $uploadMgr = new UploadManager();

// 调用 UploadManager 的 putFile 方法进行文件的上传。
                list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
//                echo "\n====> putFile result: \n";


                if ($err !== null) {
//                    var_dump($err);
                    echo json_encode(['error' => $err]);
                } else {
//                    var_dump($ret);
                    echo json_encode(['url' => 'http://' . $url . "/" . $file]);
                }

            }
//                echo json_encode(['url' => $file]);
        }
    }



/**
 * 七牛云上传测试
 */
   /* public function actionTest()
    {


// 需要填写你的 Access Key 和 Secret Key
        $accessKey = "GmwzGPby8zB4kwx9VCIMo2-pyQwr7mehcKp8uRCx";
        $secretKey = "tqMfgKG8RtYLqyRWE_G1wquhUTm689rMU23iv3GP";
        $bucket = "yii2shop";

// 构建鉴权对象
        $auth = new Auth($accessKey, $secretKey);

// 生成上传 Token
        $token = $auth->uploadToken($bucket);

// 要上传文件的本地路径
        $filePath = \Yii::getAlias('@webroot') . '/upload/59fe81b0b9d77.jpg';

// 上传到七牛后保存的文件名
        $key = '/upload/59fe81b0b9d77.jpg';

// 初始化 UploadManager 对象并进行文件的上传。
        $uploadMgr = new UploadManager();

// 调用 UploadManager 的 putFile 方法进行文件的上传。
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        echo "\n====> putFile result: \n";
        if ($err !== null) {
            var_dump($err);
        } else {
            var_dump($ret);
        }
    }*/
}