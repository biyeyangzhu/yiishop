<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/3
 * Time: 16:14
 */

namespace backend\models;


use yii\db\ActiveRecord;

class ArticleCategory extends ActiveRecord
{
    public function attributeLabels()
    {
        return [
            'name'=>'分类名称',
            'intro'=>'简介',
            'sort'=>'排序',
            'status'=>'状态'
        ];
    }

    public function rules()
    {
        return[
            [['name','intro','sort','status'], 'required','message'=>'不能为空'],
        ];
    }
}