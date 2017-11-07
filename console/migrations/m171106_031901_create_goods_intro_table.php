<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_intro`.
 */
class m171106_031901_create_goods_intro_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_intro', [
            'goods_id' => $this->integer(),
            'content'=>$this->text(),
        ]);
        $this->addPrimaryKey('id','goods_intro','goods_id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_intro');
    }
}
