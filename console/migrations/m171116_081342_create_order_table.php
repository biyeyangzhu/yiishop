<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order`.
 */
class m171116_081342_create_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order', [
            'id' => $this->primaryKey(),
            'member_id'=>$this->integer(),
            'name'=>$this->string(50),
            'province'=>$this->string(20),
            'city'=>$this->string(20),
            'area'=>$this->string(20),
            'address'=>$this->string(20),
            'tel'=>$this->char(11),
            'delivery_id'=>$this->integer(),
            'delivery_name'=>$this->string(),
            'delivery_price'=>$this->float(),
            'payment_id'=>$this->integer(),
            'payment_name'=>$this->string(),
            'total'=>$this->decimal(),
            'status'=>$this->integer(),
            'trade_no'=>$this->string(),
            'create_time'=>$this->integer()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order');
    }
}
