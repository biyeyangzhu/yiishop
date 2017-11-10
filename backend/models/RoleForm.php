<?php
/**
 * Created by PhpStorm.
 * User: melo
 * Date: 2017/11/9
 * Time: 11:48
 */

namespace backend\models;


use yii\base\Model;

class RoleForm extends Model
{
    public $name;
    public $description;
    public $permission;
    //添加一个新的变量用于保存需要修改的名字
    public $oldName;
    //添加场景  常量
    const SCENARIO_ADD= 'add';
    const SCENARIO_EDIT= 'edit';
    public function rules()
    {
        return [
            [['name','description','permission'],'required'],
            //在修改时场景下生效
            ['name','validateUpdate','on'=>self::SCENARIO_EDIT],
            //再添加场景下生效
            ['name','validateName','on'=>self::SCENARIO_ADD],
        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'角色名称',
            'description'=>'描述',
            'permission'=>'权限',
        ];
    }
    //自定义验证规则
    public function validateName()
    {
        $auth = \Yii::$app->authManager;
        //验证角色名重复
        $count = $auth->getRole($this->name);
        if ($count) {
            $this->addError('name', '角色已经存在');
        }
    }
    public function validateUpdate()
    {
        $auth = \Yii::$app->authManager;
        //只考虑验证错误
        //验证修改时修改成其他重复名字
        if($this->oldName!=$this->name){
            //验证角色名重复
            $count = $auth->getRole($this->name);
            if ($count) {
                $this->addError('name', '角色已经存在');
            }
        }

    }
}