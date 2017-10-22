<?php

use yii\db\Migration;

/**
 * Handles the creation of table `category`.
 */
class m170826_173727_create_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('category', [
            'id' => $this->primaryKey(),
            "is_active"  => $this->boolean()->defaultValue(0),
            'created_on' => $this->dateTime(),
            'updated_on' => $this->dateTime(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('category');
    }
}
