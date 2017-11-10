<?php
/**
 * Created by PhpStorm.
 * User: melo
 * Date: 2017/11/10
 * Time: 11:33
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Menu extends ActiveRecord
{
    public function attributeLabels()
    {
        return [
            'name'=>'菜单名',
            'parent_id'=>'上级菜单',
            'url'=>'地址(路由)',
            'sort'=>'排序'
        ];

    }

    public function rules()
    {
        return [
            [['name','parent_id','sort'], 'required','message'=>'不能为空'],
           ['url','safe']
        ];
    }

    public function getChildren(){

        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }
}