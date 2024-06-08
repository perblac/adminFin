<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%conversation}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%user}}`
 * - `{{%notification}}`
 */
class m240512_160243_create_conversation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%conversation}}', [
            'id' => $this->primaryKey(),
            'client_id' => $this->integer()->notNull(),
            'status' => $this->string()->notNull()->defaultValue('open'),
            'first_notification' => $this->integer(),
            'created_at' => $this->integer(11),
            'created_by' => $this->integer()->notNull(),
        ]);

        // creates index for column `client_id`
        $this->createIndex(
            '{{%idx-conversation-client_id}}',
            '{{%conversation}}',
            'client_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-conversation-client_id}}',
            '{{%conversation}}',
            'client_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-conversation-created_by}}',
            '{{%conversation}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-conversation-created_by}}',
            '{{%conversation}}',
            'created_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `first_notification`
        $this->createIndex(
            '{{%idx-conversation-first_notification}}',
            '{{%conversation}}',
            'first_notification'
        );

        // add foreign key for table `{{%notification}}`
        $this->addForeignKey(
            '{{%fk-conversation-first_notification}}',
            '{{%conversation}}',
            'first_notification',
            '{{%notification}}',
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
            '{{%fk-conversation-client_id}}',
            '{{%conversation}}'
        );

        // drops index for column `client_id`
        $this->dropIndex(
            '{{%idx-conversation-client_id}}',
            '{{%conversation}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-conversation-created_by}}',
            '{{%conversation}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-conversation-created_by}}',
            '{{%conversation}}'
        );

        // drops foreign key for table `{{%notification}}`
        $this->dropForeignKey(
            '{{%fk-conversation-first_notification}}',
            '{{%conversation}}'
        );

        // drops index for column `first_notification`
        $this->dropIndex(
            '{{%idx-conversation-first_notification}}',
            '{{%conversation}}'
        );

        $this->dropTable('{{%conversation}}');
    }
}
