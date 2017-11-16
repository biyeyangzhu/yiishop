<?php

namespace backend\models;


use creocoder\nestedsets\NestedSetsBehavior;
use yii\db\ActiveRecord;

class GoodsCategory extends ActiveRecord
{
    /**
     * 查询父id下的儿子
     * @param $id
     * @return array|ActiveRecord[]
     */
    public static function GetChild($id)
    {
        return self::find()->where(['parent_id' => $id])->all();
    }


    public function rules()
    {
        return [
            [['name', 'parent_id'], 'required'],
            [['tree', 'lft', 'rgt', 'depth', 'parent_id'], 'integer'],
            [['intro'], 'string'],
            [['name'], 'string', 'max' => 255],

        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tree' => 'Tree',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'depth' => 'Depth',
            'name' => '分类名',
            'parent_id' => 'Parent ID',
            'intro' => '简介',
        ];
    }

    public function behaviors()
    {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new GoodsCategoryQuery(get_called_class());
    }

//获取Ztree需要的数据
    public static function getZtreeNodes()
    {
        return self::find()->select(['id', 'name', 'parent_id'])->asArray()->all();
    }

}
