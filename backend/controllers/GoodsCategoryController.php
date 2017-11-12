<?php
/**
 * Created by PhpStorm.
 * User: melo
 * Date: 2017/11/5
 * Time: 14:59
 */

namespace backend\controllers;


use backend\models\GoodsCategory;
use yii\web\Controller;
use yii\web\Request;

class GoodsCategoryController extends CommonController
{

    public function actionAdd()
    {
        $model = new GoodsCategory();
        $request = new Request();
        $model->parent_id = 0;
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                //判断是parent_id是不是0 新建树
                if ($model->parent_id == 0) {
                    $model->makeRoot();
                } else {
                    $parent = GoodsCategory::findOne(['id' => $model->parent_id]);
                    $model->prependTo($parent);
                }
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect('index');
            }
        }
        return $this->render('add', ['model' => $model]);
    }

    public function actionIndex()
    {
        //查询数据
        $query = GoodsCategory::find()->orderBy('tree asc,lft asc')->all();
        return $this->render('index', ['category' => $query]);
    }

    public function actionDelete($id)
    {
        $count = GoodsCategory::findOne(['id'=>$id]);
       if($count->isLeaf()){
            if($count->parent_id !=0){
                $count->delete();
            }else{
                $count->deleteWithChildren();
            }
            echo 1;
        }else{
            echo 0;
       }


    }

    public function actionEdit($id)
    {
        $model = GoodsCategory::findOne(['id' => $id]);
        $parent_id = $model->parent_id;
        $request = new Request();
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                //判断是parent_id是不是0 新建树
                if ($model->parent_id == 0) {
                    //修改根节点会报错需要单独处理 根节点的id为0时
                    ////判断是parent_id是不是0 新建树
                    if ($parent_id == 0) {
                        $model->save();
                    }else{
                        $model->makeRoot();
                    }
                } else {
                    $parent = GoodsCategory::findOne(['id' => $model->parent_id]);
                    $model->prependTo($parent);
                }
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect('index');
            }
        }
//        var_dump($model);die;
        return $this->render('add', ['model' => $model]);
    }
}