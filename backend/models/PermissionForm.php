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
    public $oldName;
    //定义场景
    const SCENARIO_ADD = 'add';
    const SCENARIO_EDIT = 'edit';

    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            ['name', 'validateName','on'=>self::SCENARIO_ADD],
            ['name', 'validateUpdate','on'=>self::SCENARIO_EDIT],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '名称(路由)',
            'description' => '描述',
        ];
    }

    public function validateName()
    {
        $auth = \Yii::$app->authManager;
        //验证角色名重复
        $count = $auth->getPermission($this->name);
        if ($count) {
            $this->addError('name', '权限已经存在');
        }
    }
    public function validateUpdate()
    {
        $auth = \Yii::$app->authManager;
        //只考虑验证错误
        //验证修改时修改成其他重复名字
        if($this->oldName!=$this->name){
            //验证角色名重复
            $count = $auth->getPermission($this->name);
            if ($count) {
                $this->addError('name', '权限已经存在');
            }
        }

    }
}