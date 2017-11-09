<?php
/**
 * Created by PhpStorm.
 * User: melo
 * Date: 2017/11/7
 * Time: 15:26
 */

namespace backend\controllers;


use backend\models\LoginForm;
use backend\models\PasswordForm;
use backend\models\User;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;

class UserController extends Controller
{
    public function actionIndex()
    {
        //查询不是删除状态的
        $query = User::find()->where(['>', 'status', '0']);
        //使用分页工具
        $page = new Pagination();
        $page->totalCount = $query->count();
        //设置每页显示条数
        $page->pageSize = 2;
        //查询数据limit
        $users = $query->limit($page->limit)->offset($page->offset)->all();
        //显示页面
        return $this->render("index", ['model' => $users, 'page' => $page]);
    }

    public function actionAdd()
    {
        //实例化auth_manager组件进行管理rbac
        $auth = \Yii::$app->authManager;
        //查询出所有的角色
        $roles = $auth->getRoles();
        $roles = ArrayHelper::map($roles,'name','name');
        $model = new User();
        $request = new Request();
        if($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
                $model->created_at = time();
                $model->auth_key = \Yii::$app->security->generateRandomString();
                $model->save();
                //获取添加用户的id
                $id = $model->getOldAttribute('id');
                //获取对应的角色
                foreach ($model->role as $value){
                    //逐个获取多个角色名的角色对象
                    $role = $auth->getRole($value);
                    $auth->assign($role,$id);
                }
                //跳转到首页
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect('index');
            } else {
                //打印错误信息
                var_dump($model->getErrors());
            }
        }
        return $this->render('add', ['model' => $model,'roles'=>$roles]);
    }

    public function actionDelete($id)
    {
    //删除将status状态改为0表名为删除
        $result = User::updateAll(['status' => 0], ['id' => $id]);
        if ($result) {
            //修改成功 返回数据
            echo 1;
        } else {
            echo "删除失败";
        }
    }

    public function actionEdit($id)
    {
        //实例化auth_manager组件进行管理rbac
        $auth = \Yii::$app->authManager;
        //查询出所有的角色
        $roles = $auth->getRoles();
        $roles = ArrayHelper::map($roles,'name','name');
        $model = User::findOne(['id'=>$id]);
        //将获取到的角色下的所有权限赋值给model
        $model->role = array_keys($auth->getRolesByUser($id));
        $request = new Request();
        $model->password_hash ='';
        if($request->isPost){
            $model->load($request->post());
            $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
//            $model->updated_at = time();
            if($model->validate()){
                $model->save();
                $auth->revokeAll($id);
                //获取添加用户的id
                $id = $model->getOldAttribute('id');
                //获取对应的角色
                foreach ($model->role as $value){
                    //逐个获取多个角色名的角色对象
                    $role = $auth->getRole($value);
                    $auth->assign($role,$id);
                }
                //跳转到首页
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect('index');
            }else {
                //打印错误信息
                var_dump($model->getErrors());
        }
        }
        return $this->render('add', ['model' => $model,'roles'=>$roles]);
    }

    /**
     * 登录
     * @return string|\yii\web\Response
     */
    public function actionLogin(){
        if(\Yii::$app->user->isGuest){
            $model = new LoginForm();
            $request = new Request();
            if($request->isPost){
                $model->load($request->post());
                if($model->validate()){
                    //验证登录是否成功
//                    var_dump($model);die;
                    if($model->Login()){
                        //成功跳转保存最后登录时间和最后登录的ip
                        $user = User::findOne(['username'=>$model->username]);
                        $ip = \Yii::$app->request->userIP;
                        $user->last_login_ip=$ip;
                        $user->last_login_time=time();
                        //将登录标识保存到session验证是否记住我
                        \Yii::$app->user->login($user,$model->remember?3600*24*30:0);
                        $user->save();
                        //跳转到首页
//                    var_dump(\Yii::$app->user->isGuest);die();
                        \Yii::$app->session->setFlash('success', '登录成功');
                        return $this->redirect('index');
                    }
                }
            }
            return $this->render('login',['model'=>$model]);
        }else{
            return $this->redirect('index');
        }

    }

    /**
     * 注销登录
     * @return \yii\web\Response
     */
    public function actionLogout(){
        \Yii::$app->user->logout();
        return $this->redirect(['login']);
    }

    /**
     * 修改个人账号的密码
     */
    public function actionUpdate(){
        $model = new PasswordForm();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                //验证旧密码 新密码和确认新密码一致
                $password_hash = \Yii::$app->user->identity->password_hash;
                if (\Yii::$app->security->validatePassword($model->oldpassword, $password_hash)) {
                    //旧密码正确//3 更新当前用户的密码
                    User::updateAll([
                        'password_hash' => \Yii::$app->security->generatePasswordHash($model->newpassword)
                    ],
                        ['id' => \Yii::$app->user->id]
                    );
                    \Yii::$app->user->logout();
                    \Yii::$app->session->setFlash('success', '密码修改成功,请重新登录');
                    return $this->redirect('login');
                } else {
                    //旧密码不正确
                    $model->addError('oldpassword', '旧密码不正确');
                }


            }
        }
        return $this->render('password',['model'=>$model]);
    }

}