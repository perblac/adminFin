<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%conversation}}`.
 */
class m240512_163814_add_updated_at_column_to_conversation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%conversation}}', 'updated_at', $this->integer(11));
        $this->addColumn('{{%conversation}}', 'updated_by', $this->integer()->notNull());

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-conversation-updated_by}}',
            '{{%conversation}}',
            'updated_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-conversation-updated_by}}',
            '{{%conversation}}'
        );

        $this->dropColumn('{{%conversation}}', 'updated_at');
        $this->dropColumn('{{%conversation}}', 'updated_by');
    }
}
