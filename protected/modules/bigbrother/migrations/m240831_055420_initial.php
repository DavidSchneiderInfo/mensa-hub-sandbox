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
            'content_id' => $this->integer()->notNull(),
            'comment_id' => $this->integer(),
            'reason' => 'tinyint(4) UNSIGNED NOT NULL',
            'message' => $this->text(),
            'created_at' => $this->dateTime()->defaultValue(null),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->dateTime()->defaultValue(null),
            'updated_by' => $this->integer()->notNull()
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
