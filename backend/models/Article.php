<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/3
 * Time: 16:14
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Article extends ActiveRecord
{
    //配置一对多的关系
    public function getCategory(){
        return $this->hasOne(ArticleCategory::className(),['id'=>'article_category_id']);
    }
    public function attributeLabels()
    {
        return [
            'name'=>'文章名称',
            'intro'=>'简介',
            'sort'=>'排序',
            'status'=>'状态',
            'article_category_id'=>'文章分类',
        ];
    }

    public function rules()
    {
        return[
            [['name','intro','sort','status','article_category_id'], 'required','message'=>'不能为空'],
        ];
    }
}