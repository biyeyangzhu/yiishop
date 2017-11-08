<?php
/**
 * Created by PhpStorm.
 * User: melo
 * Date: 2017/11/8
 * Time: 10:55
 */

namespace backend\models;


use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $code;
    public $remember;
    public function rules()
    {
        return [
            [['username', 'password'], 'required', 'message' => '不能为空'],
            ['code','captcha','message'=>'验证码不正确'],//验证码
            ['remember','safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            'code'=>'验证码',
            'remember'=>'记住我'
        ];
    }

    public function Login()
    {
        //查询是否有表单提交过来的用户
        $user = User::findOne(['username'=>$this->username]);
//        var_dump($user);die;
        //有就判断密码是否正确
        if($user){
           if(\Yii::$app->security->validatePassword($this->password,$user->password_hash)){

               return true;
           }else{
               //给模型添加错误信息
               $this->addError('password','密码错误');
           }
        }else{
            //给模型添加错误信息
            $this->addError('username','用户不存在');
        }
    }
}