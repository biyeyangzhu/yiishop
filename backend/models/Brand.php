<?php

namespace backend\models;
use \yii\db\ActiveRecord;
class Brand extends ActiveRecord
{
    public $imgfile;

    public function attributeLabels()
    {
        return [
            'name'=>'品牌名称',
            'intro'=>'简介',
            'imgfile'=>'Logo',
            'sort'=>'排序',
            'status'=>'状态'
        ];

    }

    public function rules()
    {
        return [
            [['name','intro','imgfile','sort','status'], 'required','message'=>'不能为空'],
            ["imgfile","file",'extensions'=>['jpg','png','gif','jpeg'],'skipOnEmpty'=>false]
        ];
    }
}