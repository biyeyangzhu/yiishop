<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/3
 * Time: 16:09
 */

namespace backend\controllers;


use backend\models\ArticleCategory;
use yii\data\Pagination;
use yii\web\Controller;

class ArticleCategoryController extends Controller
{
    public function actionIndex(){
    //查询数据
        $query = ArticleCategory::find()->where(['>=','status','0']);
        //创建分页工具类
        $page = new Pagination();
        //查询出所有条数
        $page->totalCount = $query->count();
        //每页显示得条数
        $page->pageSize=2;
        //limit(0,2);offset 偏移量
        $category = $query->limit($page->limit)->offset($page->offset)->all();
        //显示表单
        return $this->render('index',['category'=>$category,'page'=>$page]);
    }

    public function actionAdd(){
        //显示添加表单
        $model = new ArticleCategory();
        $request = \Yii::$app->request;
        //判断post提交
        if($request->isPost){
            //加载数据到模型
            $model->load($request->post());
            //对接收的数据进行判断
            if($model->validate()){
                //保存数据到数据库
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect('index');
            }
        }
        return $this->render("add",['model'=>$model]);
    }

    public function actionDelete($id){
        //修改数据
        $result = ArticleCategory::updateAll(['status'=>-1],['id'=>$id]);
        //修改成功返回数据
        if($result){
            return 1;
        }
    }

    public function actionEdit($id){
        //显示添加表单
        $model =ArticleCategory::findOne(['id'=>$id]);
        $request = \Yii::$app->request;
        //判断post提交
        if($request->isPost){
            //加载数据到模型
            $model->load($request->post());
            //对接收的数据进行判断
            if($model->validate()){
                //保存数据到数据库
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect('index');
            }
        }
        return $this->render("add",['model'=>$model]);
    }
}