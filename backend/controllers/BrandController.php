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

class BrandController extends Controller
{
    //关闭crf  yii2跨域提交
    public $enableCsrfValidation =false;

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

    //ajax处理上传图片
    public function actionUpload(){
        //判断是不是post提交的
        if(\Yii::$app->request->isPost){
            $imgfile = UploadedFile::getInstanceByName('file');
            //判断是否有上传文件
            if($imgfile){
                $ext = $imgfile->extension;
                $file = '/upload/' . uniqid() . '.' . $ext;
                //上传文件永久保存到Uploads
                $imgfile->saveAs(\Yii::getAlias('@webroot') . $file, false);
                echo json_encode(['url'=>$file]);
            }
        }
    }
}