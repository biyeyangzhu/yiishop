<?php
/**
 * Created by PhpStorm.
 * User: melo
 * Date: 2017/11/6
 * Time: 14:53
 */

namespace backend\models;


use yii\db\ActiveRecord;

class GoodsIntro extends ActiveRecord
{
    public function attributeLabels()
    {
        return [
            'content'=>'商品详情'
        ];

    }

    public function rules()
    {
        return [
            [['content'], 'required','message'=>'不能为空'],
        ];
    }
}