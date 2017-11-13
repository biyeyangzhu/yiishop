<?php
/**
 * Created by PhpStorm.
 * User: melo
 * Date: 2017/11/12
 * Time: 15:51
 */

namespace frontend\models;


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
            [['username','password','remember'], 'safe'],
        ];
    }
}