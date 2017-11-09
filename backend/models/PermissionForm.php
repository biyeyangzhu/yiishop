<?php
/**
 * Created by PhpStorm.
 * User: melo
 * Date: 2017/11/9
 * Time: 10:41
 */

namespace backend\models;


use yii\base\Model;

class PermissionForm extends Model
{
    public $name;
    public $description;
    public function rules()
    {
        return [
            [['name','description'],'required'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'名称',
            'description'=>'描述',
        ];
    }
}