<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_category`.
 */
class m171103_054401_create_article_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_category', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->notNull()->comment('名称'),
            'intro'=>$this->text()->comment('简介'),
            'sort'=>$this->integer(11)->unsigned()->comment('排序'),
            'status'=>$this->integer(2)->comment('状态 -1删除 0隐藏 1正常')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_category');
    }
}
