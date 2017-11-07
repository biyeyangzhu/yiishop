<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_day_count`.
 */
class m171106_025126_create_goods_day_count_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_day_count', [
            'day'=>$this->date(),
            'count'=>$this->integer()->unsigned(),
        ]);
        $this->addPrimaryKey('date','goods_day_count','day');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_day_count');
    }
}
