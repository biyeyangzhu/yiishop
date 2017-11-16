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

    public static function GoodsCart($model){
        $html='';
        $count = 0;
        foreach ($model as $value):
            $goods = Goods::findOne(['id'=>$value['goods_id']]);
            $count +=$goods->shop_price*$value['amount'];
            $html .= '<tbody>
                    <tr>
        <td class="col1"><a href=""><img src='.$goods->logo.' alt="" /></a>  <strong><a href="">'.$goods->name.'</a></strong></td>
        <td class="col3">￥<span>'.$goods->shop_price.'</span></td>
        <td class="col4">
            <a href="javascript:;" class="reduce_num"></a>
            <input type="text" name="amount" value='.$value['amount'].' class="amount" id='.$value['id'].'/>
            <a href="javascript:;" class="add_num"></a>
        </td>
        <td class="col5">￥<span>'.$goods->shop_price*$value['amount'].'</span></td>
        <td class="col6"><a href="javascript:;"name="del" id='.$value['id'].'>删除</a></td>
    </tr>
     </tbody>';
        endforeach;
        return $html;
    }

}