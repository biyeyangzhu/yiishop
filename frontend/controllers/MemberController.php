<?php
/**
 * Created by PhpStorm.
 * memeber: melo
 * Date: 2017/11/12
 * Time: 11:59
 */

namespace frontend\controllers;


use frontend\models\LoginForm;
use frontend\models\Member;
use yii\web\Controller;

class MemberController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionRegister()
    {
        $model = new Member();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post(), '');
            if ($model->validate()) {
//                var_dump($model);die();
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
                $model->auth_key = \Yii::$app->security->generateRandomString();
                $model->save(false);
                echo '注册成功';
                die;
            }
        }
        return $this->render('register');
    }

    public function actionLogin()
    {
        $model = new LoginForm();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post(), '');
            if ($model->validate()) {

                //验证登录是否成功
                $memeber = Member::findOne(['username' => $model->username]);
                if (!$memeber) {
                    return "用户不存在";
                } else {
                    if (!(\Yii::$app->security->validatePassword($model->password, $memeber->password_hash))) {
                        return "密码错误";
                    } else {
                        $ip = \Yii::$app->request->userIP;
                        $memeber->last_login_ip = $ip;
                        $memeber->last_login_time = time();
                        \Yii::$app->user->login($memeber, $model->remember ? 3600 * 24 * 30 : 0);
                        $memeber->save(false);
                        //跳转到首页
                        \Yii::$app->session->setFlash('success', '登录成功');
                        return $this->redirect('index');
                    }
                }


            }
        }
        return $this->render('login');
    }

    /**
     * 验证用户名的唯一性
     */
    public function actionCheckName($memebername)
    {
        $count = Member::findOne(['memebername' => $memebername]);
        if ($count) {
            return 'false';
        } else {
            return 'true';
        }

    }

    /**
     * 验证手机号的唯一性
     */
    public function actionCheckTel($tel)
    {
        $count = Member::findOne(['memebername' => $tel]);
        if ($count) {
            return 'false';
        } else {
            return 'true';
        }
    }

    /**
     * 验证邮箱的唯一性
     */
    public function actionCheckEmail($email)
    {
        $count = Member::findOne(['memebername' => $email]);
        if ($count) {
            return 'false';
        } else {
            return 'true';
        }
    }

    public function actionIndex()
    {

    }
}