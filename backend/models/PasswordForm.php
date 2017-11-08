<?php
/**
 * Created by PhpStorm.
 * User: melo
 * Date: 2017/11/8
 * Time: 16:21
 */

namespace backend\models;


use yii\base\Model;

class PasswordForm extends Model
{
    public $newpassword;
    public $repassword;
    public $oldpassword;
    public function rules()
    {
        return [
            [['newpassword', 'repassword','oldpassword'], 'required', 'message' => '不能为空'],
            ['repassword','compare','compareAttribute' => 'newpassword', 'operator' => '===','message'=>'两次密码不一致']
        ];
    }

    public function attributeLabels()
    {
        return [
            'newpassword' => '新密码',
            'repassword' => '确认密码',
            'oldpassword'=>'旧密码',
        ];
    }
}