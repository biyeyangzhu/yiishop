<?php
/**
 * Created by PhpStorm.
 * memeber: melo
 * Date: 2017/11/12
 * Time: 11:59
 */

namespace frontend\controllers;


use frontend\models\address;
use frontend\models\LoginForm;
use frontend\models\Member;
use yii\web\Controller;
use frontend\components\Sms;
class MemberController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * 注册
     * @return string
     */
    public function actionRegister()
    {
        $model = new Member();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post(), '');
            if ($model->validate()) {
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
                $model->auth_key = \Yii::$app->security->generateRandomString();
                $model->save(false);
                echo '注册成功';
                die;
            }
        }
        return $this->render('register');
    }

    /**
     * 登录
     * @return string|\yii\web\Response
     */
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
                        return $this->redirect(['shop/index']);
                    }
                }


            }
        }
        return $this->render('login');
    }

    /**
     * 验证用户名的唯一性
     */
    public function actionCheckName($username)
    {
        $count = Member::findOne(['username' => $username]);
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
        $count = Member::findOne(['tel' => $tel]);
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
        $count = Member::findOne(['email' => $email]);
        if ($count) {
            return 'false';
        } else {
            return 'true';
        }
    }

    /**
     *  发送短信验证码
     *
     */
    public function actionSms($phone){
        $code = rand(1000,99999);
        $response = Sms::sendSms(
            "金叫唤", // 短信签名
            "SMS_109560458", // 短信模板编号
            $phone, // 短信接收者
            Array(  // 短信模板中字段的值
                "code"=>$code,
               )

        );
        if($response->Code=='OK'){
            $redis = new \Redis();
            $redis->connect('127.0.0.1');
            $redis->set('captcha_'.$phone,$code,10*60);
            return 'success';
        }

    }

    /**
     * 验证手机验证码
     * @param $tel
     * @param $captcha
     * @return string
     */
    public function actionCheckCaptcha($tel,$captcha)
    {
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $code = $redis->get('captcha_'.$tel);
        if($code){
            if($captcha==$code){
                return "true";
            }else{
                return "false";
            }
        }else{
            return 'false';
        }
    }

    /**
     * 添加地址
     * @return string
     */
    public function actionAddress()
    {
        $model = new address();
        $id = \Yii::$app->user->id;
        $request = \Yii::$app->request;
        $content = address::find()->where(['member_id'=>$id])->all();
        if($request->isPost){
            $model->load($request->post(),'');
            if($model->validate()){
                $model->member_id = $id;
                //如果设置了默认地址将原来默认地址进行修改
                $model->default=$model->default?1:0;
                if($model->default){
                    $default =address::findOne(['default'=>1]);
                    $default->default = 0;
                    $default->save();
                }
               $model->save();
                return $this->redirect('address');
            }else{
                var_dump($model->getErrors());
            }
        }
        return $this->render('address',['address'=>$content,'model'=>$model]);
    }

    /**
     * 注销
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->redirect(['login']);
    }

    /**
     * 地址删除
     */
    public function actionAddressDelete($id){
        $address = address::findOne(['id'=>$id]);
        if($address){
            $address->delete();
            echo 1;
        }else{
            echo 0;
        }
    }

    /**
     * 地址修改
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionAddressEdit($id)
    {
        $address = address::findOne(['id' => $id]);
        $request = \Yii::$app->request;
        $content = address::find()->where(['member_id'=>\Yii::$app->user->id])->all();
        if ($request->isPost) {
            $address->load($request->post(), '');
            if ($address->validate()) {
                $address->save();
                return $this->redirect('address');
            } else {
                var_dump($address->getErrors());
            }
        }
        return $this->render('address', ['model' => $address,'address'=>$content]);
    }

    /**
     * 设置默认地址
     * @param $id
     */
    public function actionAddressDefault($id){
        $address = address::findOne(['id' => $id]);
        $default =address::findOne(['default'=>1]);
        $address->default = 1;
        $default->default = 0;
        if($address->save()&& $default->save()){
            echo 1;
        }else{
            echo 0;
        }
    }

}