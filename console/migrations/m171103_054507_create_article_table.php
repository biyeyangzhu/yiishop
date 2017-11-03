<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m171103_054507_create_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->notNull()->comment('名称'),
            'intro'=>$this->text()->comment('简介'),
            'sort'=>$this->integer(11)->unsigned()->comment('排序'),
            'article_category_id'=>$this->integer()->unsigned()->comment('文章分类id'),
            'status'=>$this->integer(2)->comment('状态 -1删除 0隐藏 1正常'),
            'create_time'=>$this->integer(11)->comment('创建时间')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article');
    }
}
