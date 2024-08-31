<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%reported_content}}`.
 */
class m240831_055420_initial extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%reported_content}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%reported_content}}');
    }
}
