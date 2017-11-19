<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m171110_032757_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(20),
            'deep'=>$this->integer(2)->unsigned()->notNull(),
            'parent_id'=>$this->integer(10)->notNull(),
            'sort'=>$this->integer(10)->notNull(),
            'url'=>$this->string(50),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
