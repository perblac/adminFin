<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%notification}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%user}}`
 */
class m240512_160219_create_notification_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%notification}}', [
            'id' => $this->primaryKey(),
            'message' => $this->text(),
            'sender_id' => $this->integer()->notNull(),
            'receiver_id' => $this->integer()->notNull(),
            'type' => $this->integer()->notNull()->defaultValue(1),
            'status' => $this->string()->notNull()->defaultValue('unread'),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'updated_by' => $this->integer()->notNull(),
        ]);

        // creates index for column `sender_id`
        $this->createIndex(
            '{{%idx-notification-sender_id}}',
            '{{%notification}}',
            'sender_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-notification-sender_id}}',
            '{{%notification}}',
            'sender_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-notification-updated_by}}',
            '{{%notification}}',
            'updated_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `receiver_id`
        $this->createIndex(
            '{{%idx-notification-receiver_id}}',
            '{{%notification}}',
            'receiver_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-notification-receiver_id}}',
            '{{%notification}}',
            'receiver_id',
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
            '{{%fk-notification-sender_id}}',
            '{{%notification}}'
        );

        // drops index for column `sender_id`
        $this->dropIndex(
            '{{%idx-notification-sender_id}}',
            '{{%notification}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-notification-updated_by}}',
            '{{%notification}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-notification-receiver_id}}',
            '{{%notification}}'
        );

        // drops index for column `receiver_id`
        $this->dropIndex(
            '{{%idx-notification-receiver_id}}',
            '{{%notification}}'
        );

        $this->dropTable('{{%notification}}');
    }
}
