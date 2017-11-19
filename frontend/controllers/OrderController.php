<?php
/**
 * Created by PhpStorm.
 * User: melo
 * Date: 2017/11/16
 * Time: 16:22
 */

namespace frontend\controllers;


use backend\models\Goods;
use frontend\models\address;
use frontend\models\Cart;
use frontend\models\Order;
use frontend\models\OrderGoods;
use Symfony\Component\Yaml\Yaml;
use yii\db\Exception;
use yii\web\Controller;

class OrderController extends Controller
{
    public $enableCsrfValidation;
    public function actionIndex(){
        if(\Yii::$app->user->isGuest){
            return $this->redirect(["member/login"]);
        }else{
            $request = \Yii::$app->request;
            $member_id =\Yii::$app->user->id;
            $address =address::find()->where(['member_id'=>$member_id])->all();
            $cartall = Cart::find()->where(['member_id' => $member_id])->asArray()->all();
            $carts=[];
            //处理数据 将goods_id作为键 amount作为值存入数组
            foreach ($cartall as $cart){
                $carts[$cart['goods_id']]=$cart['amount'];
            }
            $model = Goods::find()->where(['in','id',array_keys($carts)])->all();
            return $this->render('flow',['address'=>$address,'model'=>$model,'carts'=>$carts]);
        }

    }

    public function actionAdd(){
        $request = \Yii::$app->request;
        if($request->isPost){
            $member_id = \Yii::$app->user->id;
            $model= new Order();
            $model->load($request->post(),'');
            if($model->validate()){
                $model->member_id = $member_id;
                //查询出地址赋值给订单表
                $address = address::findOne(['id'=>$model->address_id]);
                $model->province = $address->province;
                $model->area = $address->area;
                $model->city = $address->city;
                $model->address = $address->address;
                $model->name = $address->name;
                $model->tel = $address->tel;
                $model->status = 1;
                $model->create_time =time();
                $transaction = \Yii::$app->db->beginTransaction();
                try{
                    if( $model->save()){
                        //保存订单详情表
                        //根据用户购物车来循环出商品详情
                        $carts = Cart::find()->where(['member_id'=>$member_id])->all();
                        foreach ($carts as $cart){
                            $order = new OrderGoods();
                            $goods =Goods::findOne(['id'=>$cart->goods_id]);
                            $order->goods_id = $cart->goods_id;
                            $order->goods_name=$goods->name;
                            $order->logo = $goods->logo;
                            $order->price = $goods->shop_price;
                            $order->amount = $cart->amount;
                            if($cart->amount>$goods->stock){
                                throw new Exception($goods->name."商品库存不足");
                            }
                            $order->total = $cart->amount*$goods->shop_price;
                            $order->order_id = $model->getOldAttribute('id');
                            Goods::updateAllCounters(['stock'=>-$cart->amount],['id'=>$cart->goods_id]);
                            $cart->delete();
                            $order->save();
                        }
                    }
                    $transaction->commit();

                }catch (Exception $e){
                    $transaction->rollBack();
                    return $e->getMessage();
                }
                return 1;
            }

        }
        return $this->redirect(['cart/cart']);
    }

    public function actionList(){
        $member_id = \Yii::$app->user->id;
        $order = Order::find()->where(['member_id'=>$member_id])->all();
        return $this->render('list',['order'=>$order]);
    }

    public function actionSuccess(){
        return $this->render('flow3');
    }

}