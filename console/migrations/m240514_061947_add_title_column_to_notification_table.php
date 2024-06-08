<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%notification}}`.
 */
class m240514_061947_add_title_column_to_notification_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%notification}}', 'title', $this->string(255)->notNull()->after('id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%notification}}', 'title');
    }
}
