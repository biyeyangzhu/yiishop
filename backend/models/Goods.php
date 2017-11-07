<?php

namespace backend\models;


class Goods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'logo', 'goods_category_id', 'brand_id', 'market_price', 'shop_price', 'stock', 'is_on_sale', 'sort'], 'required','message'=>'不能为空'],
            [['goods_category_id', 'brand_id', 'stock', 'is_on_sale', 'status', 'sort', 'view_times'], 'integer'],
            [['market_price', 'shop_price'], 'number'],
           [['view_times', 'status','create_time','sn'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '商品名称',
            'sn' => '货号',
            'logo' => 'Logo',
            'goods_category_id' => '商品分类',
            'brand_id' => '品牌名称',
            'market_price' => '市场价格',
            'shop_price' => '商品价格',
            'stock' => '库存',
            'is_on_sale' => '是否在售',
            'sort' => '排序',
        ];
    }
}
