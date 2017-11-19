<?php
/**
 * Created by PhpStorm.
 * User: melo
 * Date: 2017/11/16
 * Time: 16:22
 */

namespace frontend\models;


use yii\db\ActiveRecord;

class Order extends ActiveRecord
{
    public $address_id;
    public function rules()
    {

         return [
             [['total', 'payment_id','address_id','delivery_id','payment_name','delivery_name','delivery_price'], 'safe'],
         ];

    }

    public static function getLogo($order_id){
        $pic = '';
        $orderGoods = OrderGoods::find()->where(['order_id'=>$order_id])->all();
        foreach ($orderGoods as $key=>$goods){
            if ($key<=2){
                $pic .='<img src="'.$goods->logo.'" alt="" /></a>';
            }
        }
        return $pic;
    }
}