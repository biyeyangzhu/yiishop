<?php
/**
 * Created by PhpStorm.
 * User: melo
 * Date: 2017/11/9
 * Time: 10:36
 */

namespace backend\controllers;


use backend\models\PermissionForm;
use yii\web\Controller;

class AuthController extends Controller
{
    /**
     * 权限的添加
     */
    public function actionAddPermission(){
        //实例化authManager组件操作rbac
        $auth = \Yii::$app->authManager;
        //实例化表单模型显示表单
        $model = new PermissionForm();
        $request = \Yii::$app->request;
        if($request->isPost){
            
        }
        return $this->render('add-per',['model'=>$model]);
    }
}