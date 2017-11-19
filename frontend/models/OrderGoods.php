<?php
/**
 * Created by PhpStorm.
 * User: melo
 * Date: 2017/11/17
 * Time: 14:42
 */

namespace frontend\models;


use yii\db\ActiveRecord;

class OrderGoods extends ActiveRecord
{
    public function rules()
    {

        return [
            [['total', 'order_id','goods_name','goods_id','logo','price','amount'], 'safe'],
        ];

    }
}