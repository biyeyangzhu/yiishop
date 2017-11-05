<?php

namespace backend\models;
use \yii\db\ActiveRecord;
class Brand extends ActiveRecord
{
    public function attributeLabels()
    {
        return [
            'name'=>'品牌名称',
            'intro'=>'简介',
            'logo'=>'Logo',
            'sort'=>'排序',
            'status'=>'状态'
        ];

    }

    public function rules()
    {
        return [
            [['name','intro','sort','status','logo'], 'required','message'=>'不能为空'],
        ];
    }
}