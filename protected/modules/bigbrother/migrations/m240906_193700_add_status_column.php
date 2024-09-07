<?php

declare(strict_types=1);

use yii\db\Migration;

class m240906_193700_add_status_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%reported_content}}', 'status', $this->tinyInteger()->unsigned()->after('id')->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%reported_content}}');
    }
}
