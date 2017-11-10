<?php
/**
 * Created by PhpStorm.
 * User: melo
 * Date: 2017/11/10
 * Time: 11:32
 */

namespace backend\controllers;


use backend\models\Menu;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class MenuController extends Controller
{
    public function actionAdd(){
        $auth = \Yii::$app->authManager;
        $model = new Menu();
        $request  =\Yii::$app->request;
        //取出所有的路由
        $url = $auth->getPermissions();
        $url = ArrayHelper::map($url,'name','description');
        $url = ArrayHelper::merge([''=>'==请选择地址=='],$url);
        //取出所有的一级菜单  parent_id为0
        $parent = Menu::find()->where(['parent_id'=>0])->all();
        $parent = ArrayHelper::map($parent,'id','name');
        $parent =ArrayHelper::merge([''=>'==请选择上级菜单==',0=>'顶级菜单'],$parent);
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //文章parent_id为0深度就为1耳机菜单深度为2
                if($model->parent_id ==0){
                    $model->deep = 0;
                }else{
                    $model->deep =1;
                }
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                $this->redirect('index');
            }
        }
        return $this->render('add',['model'=>$model,'url'=>$url,'parent_id'=>$parent]);
    }

    public function actionIndex(){
       $model =Menu::find()->orderBy('parent_id asc','id desc')->all();
        return $this->render('index',['menu'=>$model]);
    }

    public function actionDelete($id){
        $count = Menu::findOne(['id'=>$id]);
        $result = Menu::find()->where(['parent_id'=>$id])->all();
        if($result==null){
            $count->delete();
            echo 1;
        }else{
            echo 0;
        }
    }

    public function actionEdit($id){
        $auth = \Yii::$app->authManager;
        $model = Menu::findOne(['id'=>$id]);
        $request  =\Yii::$app->request;
        //取出所有的路由
        $url = $auth->getPermissions();
        $url = ArrayHelper::map($url,'name','description');
        $url = ArrayHelper::merge([''=>'==请选择地址=='],$url);
        //取出所有的一级菜单  parent_id为0
        $parent = Menu::find()->where(['parent_id'=>0])->all();
        $parent = ArrayHelper::map($parent,'id','name');
        $parent =ArrayHelper::merge([''=>'==请选择上级菜单==',0=>'顶级菜单'],$parent);
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                $this->redirect('index');
            }
        }
        return $this->render('add',['model'=>$model,'url'=>$url,'parent_id'=>$parent]);
    }

}