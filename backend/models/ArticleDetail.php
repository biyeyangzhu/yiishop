<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/3
 * Time: 18:47
 */

namespace backend\models;


use yii\db\ActiveRecord;

class ArticleDetail extends ActiveRecord
{
    public function attributeLabels()
    {
        return [
            'content'=>'文章内容',
        ];
    }

    public function rules()
    {
        return[
            ['content', 'required','message'=>'不能为空'],
        ];
    }
}