<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%notification_conversation}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%notification}}`
 * - `{{%conversation}}`
 */
class m240512_160252_create_junction_table_for_notification_and_conversation_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%notification_conversation}}', [
            'notification_id' => $this->integer(),
            'conversation_id' => $this->integer(),
            'PRIMARY KEY(notification_id, conversation_id)',
        ]);

        // creates index for column `notification_id`
        $this->createIndex(
            '{{%idx-notification_conversation-notification_id}}',
            '{{%notification_conversation}}',
            'notification_id'
        );

        // add foreign key for table `{{%notification}}`
        $this->addForeignKey(
            '{{%fk-notification_conversation-notification_id}}',
            '{{%notification_conversation}}',
            'notification_id',
            '{{%notification}}',
            'id',
            'CASCADE'
        );

        // creates index for column `conversation_id`
        $this->createIndex(
            '{{%idx-notification_conversation-conversation_id}}',
            '{{%notification_conversation}}',
            'conversation_id'
        );

        // add foreign key for table `{{%conversation}}`
        $this->addForeignKey(
            '{{%fk-notification_conversation-conversation_id}}',
            '{{%notification_conversation}}',
            'conversation_id',
            '{{%conversation}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%notification}}`
        $this->dropForeignKey(
            '{{%fk-notification_conversation-notification_id}}',
            '{{%notification_conversation}}'
        );

        // drops index for column `notification_id`
        $this->dropIndex(
            '{{%idx-notification_conversation-notification_id}}',
            '{{%notification_conversation}}'
        );

        // drops foreign key for table `{{%conversation}}`
        $this->dropForeignKey(
            '{{%fk-notification_conversation-conversation_id}}',
            '{{%notification_conversation}}'
        );

        // drops index for column `conversation_id`
        $this->dropIndex(
            '{{%idx-notification_conversation-conversation_id}}',
            '{{%notification_conversation}}'
        );

        $this->dropTable('{{%notification_conversation}}');
    }
}
