<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m171113_081250_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50),
            'member_id'=>$this->integer(10),
            'address'=>$this->string(),
            'tel'=>$this->char(11),
            'province'=>$this->string(),
            'city'=>$this->string(),
            'area'=>$this->string(),
            'default'=>$this->integer(2)->notNull()->defaultValue(0),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
