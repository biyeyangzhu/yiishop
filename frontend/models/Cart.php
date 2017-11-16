<?php
/**
 * Created by PhpStorm.
 * User: melo
 * Date: 2017/11/15
 * Time: 17:07
 */

namespace frontend\models;


use backend\models\Goods;
use yii\db\ActiveRecord;

class Cart extends ActiveRecord
{
    public function rules()
    {
        return [
            [['goods_id', 'amount','member_id'], 'safe'],
        ];
    }

    public static function GoodsCart($model,$carts){
        $html='';
        foreach ($model as $value):
            $html .= '<tbody>
                    <tr data-id='.$value->id.'>
        <td class="col1"><a href=""><img src='.$value->logo.' alt="" /></a>  <strong><a href="">'.$value->name.'</a></strong></td>
        <td class="col3">￥<span>'.$value->shop_price.'</span></td>
        <td class="col4">
            <a href="javascript:;" class="reduce_num"></a>
            <input type="text" name="amount" value='.$carts[$value->id].' class="amount" />
            <a href="javascript:;" class="add_num"></a>
        </td>
        <td class="col5">￥<span>'.$value->shop_price*$carts[$value->id].'.00</span></td>
        <td class="col6"><a href="javascript:;"name="del">删除</a></td>
    </tr>
     </tbody>';
        endforeach;
        return $html;
    }

    /**
     * 封装操作cookie的方法
     */
    public function Cookie(){

    }
}