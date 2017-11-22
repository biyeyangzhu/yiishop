<?php
/**
 * Created by PhpStorm.
 * User: melo
 * Date: 2017/11/15
 * Time: 17:08
 */

namespace frontend\controllers;


use backend\models\Goods;
use frontend\models\Cart;
use yii\web\Controller;
use yii\web\Cookie;

class CartController extends Controller
{
    public $enableCsrfValidation=false;
    /**
     * 购物车页面
     */
    public function actionCart()
    {
        $cookies = \Yii::$app->request->cookies;
        //获取cookie中的值
        $carts = $cookies->getValue('carts');
        if(\Yii::$app->user->isGuest){
            // 未登录查询cookie进行展示
        //如果cookie中有值
        if($carts){
            $carts = unserialize($carts);//将cookie中的值进行反序列化转换为一个数组
        }else{
            //如果cookie中没有值那么就是一个空数组
            $carts=[];
        }

        }else{
            $cart_sql = new Cart();
            $member_id = \Yii::$app->user->id;
            if($carts){
                $carts = unserialize($carts);//这里是cookie中的数据保存goods_id和amount
                $goods_ids = array_keys($carts);

                foreach ($goods_ids as $goods_id){
                    $cart = Cart::findOne(['goods_id'=>$goods_id]);
                    //如果cookie中的数据存在于数据库进行修改没有就进行新添加
                    if($cart){
                        $cart->amount +=$carts[$goods_id];
                        $cart->save();
                    }else{
                        $cart_sql->member_id = $member_id;
                        $cart_sql->goods_id = $goods_id;
                        $cart_sql->amount =$carts[$goods_id];
                        $cart_sql->save();
                    }
                }
                //获取完cookie中的值后删除cookie
                $cookie = \Yii::$app->response->cookies;
                $cookie->remove('carts');
            }
            //登录后查询数据库进行展示
            $cartall = Cart::find()->where(['member_id' => $member_id])->asArray()->all();
            $carts=[];
            //处理数据 将goods_id作为键 amount作为值存入数组
            foreach ($cartall as $cart){
                $carts[$cart['goods_id']]=$cart['amount'];
            }
        }
        //查询所有的购物车的商品信息
        $model = Goods::find()->where(['in','id',array_keys($carts)])->all();
        return $this->render('cart', ['model' => $model,'carts'=>$carts]);
    }

    /**
     * 添加购物车
     */
    public function actionAddCart(){
        $request = \Yii::$app->request;
        if(\Yii::$app->user->isGuest){
            $goods_id =$request->post('goods_id');
            $amount =$request->post('amount');
            $cookies = \Yii::$app->request->cookies;
            //判断原来cookie是否有值有就修改没有就添加
            $carts = $cookies->getValue('carts');
            if($carts){
                $carts = unserialize($carts);
            }else{
                $carts = [];
            }

            //购物车中是否存在该商品,如果存在数量累加 不存在,直接添加
            if(array_key_exists($goods_id,$carts)){
                $carts[$goods_id]+=$amount;
            }else{
                $carts[$goods_id] =$amount;
            }
            //操作cookie保存购物车数据
            $cookies = \Yii::$app->response->cookies;
            $cookie= new Cookie();
            $cookie->name='carts';
            $cookie->value=serialize($carts);
            $cookie->expire=time()+3600*24*7;
            $cookies->add($cookie);
        }else{
            //获取登录用户的id
            $member_id = \Yii::$app->user->id;
            //判断这个商品是不是已经在购物车
            $goods_id = $request->post('goods_id');
            $count = Cart::findOne(['goods_id' => $goods_id,'member_id'=>$member_id]);
//            var_dump($count);die;
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
        }

            $this->redirect('cart');

    }

    /**
     * 删除购物车
     */

    public function actionDelete($goods_id){
        if(\Yii::$app->user->isGuest){
            $cookies = \Yii::$app->request->cookies;
            $carts = $cookies->getValue('carts');
            $carts = unserialize($carts);//$carts = ['1'=>'3','2'=>'2'];
            //删除数组中的一个值
          unset($carts[$goods_id]);
            //保存cookie
            $cookies = \Yii::$app->response->cookies;
            $cookie = new Cookie();
            $cookie->name = 'carts';
            $cookie->value = serialize($carts);
            $cookies->add($cookie);
            echo 1;
        }else{
            $model = Cart::findOne(['goods_id'=>$goods_id]);
            if( $model->delete()){
                echo 1;
            }else{
                echo 0;
            }
        }

    }

    /**
     * 数量的变化
     */
    public function actionReduce($amount,$goods_id){
        if(\Yii::$app->user->isGuest){
            $cookies = \Yii::$app->request->cookies;
            $carts = $cookies->getValue('carts');
            $carts = unserialize($carts);
            //修改购物车商品数量
            $carts[$goods_id] = $amount;
            //保存cookie
            $cookies = \Yii::$app->response->cookies;
            $cookie = new Cookie();
            $cookie->name = 'carts';
            $cookie->value = serialize($carts);
            $cookie->expire=time()+3600*24*7;
            $cookies->add($cookie);
        }else{
        $model = Cart::findOne(['goods_id'=>$goods_id]);
        $model->amount = $amount;
        $model->save();
        }
    }
}