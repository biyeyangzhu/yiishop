<?php
/**
 * Created by PhpStorm.
 * User: melo
 * Date: 2017/11/15
 * Time: 17:08
 */

namespace frontend\controllers;


use frontend\models\Cart;
use yii\web\Controller;

class CartController extends Controller
{
    public $enableCsrfValidation=false;
    /**
     * 购物车页面
     */
    public function actionCart()
    {
        $request = \Yii::$app->request;
        $member_id = \Yii::$app->user->id;
        $model = Cart::find()->where(['member_id' => $member_id])->all();
        if ($request->isPost) {
            //判断这个商品是不是已经在购物车
            $goods_id = $request->post('goods_id');
            $count = Cart::findOne(['goods_id' => $goods_id]);
            if ($count) {
                //在购物车就对数量进行修改
                $amount = $count->amount;
                $count->load($request->post(), '');
                if ($count->validate()) {
                    $count->amount += $amount;
                    $count->save();
                }
            } else {
                //没有就新添加一个数据
                $cart = new Cart();
                $cart->load($request->post(), '');
                if ($cart->validate()) {
                    $cart->member_id = $member_id;
//                var_dump($cart);die;
                    $cart->save();
                }
            }
            $this->redirect('cart');
        }
        return $this->render('cart', ['model' => $model]);
    }

    /**
     * 删除购物车
     */

    public function actionDelete($id){
        $model = Cart::findOne(['id'=>$id]);
        if( $model->delete()){
            echo 1;
        }else{
            echo 0;
        }
    }

    /**
     * 数量的变化
     */
    public function actionReduce($amount,$id){
        $model = Cart::findOne(['id'=>$id]);
        $model->amount = $amount;
        $model->save();
    }
}