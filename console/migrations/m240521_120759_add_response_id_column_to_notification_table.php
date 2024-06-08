<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%notification}}`.
 */
class m240521_120759_add_response_id_column_to_notification_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%notification}}', 'response_id', $this->integer()->after('status'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%notification}}', 'response_id');
    }
}
