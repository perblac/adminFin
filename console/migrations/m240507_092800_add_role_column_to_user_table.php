<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user}}`.
 */
class m240507_092800_add_role_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'role', $this->string(8)->after('email'));
        $this->addColumn('{{%user}}', 'phone2', $this->string(32)->after('email'));
        $this->addColumn('{{%user}}', 'phone1', $this->string(32)->after('email'));
        $this->addColumn('{{%user}}', 'address', $this->string(255)->after('email'));
        $this->addColumn('{{%user}}', 'fullname', $this->string(255)->after('username'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'phone2');
        $this->dropColumn('{{%user}}', 'phone1');
        $this->dropColumn('{{%user}}', 'address');
        $this->dropColumn('{{%user}}', 'fullname');
        $this->dropColumn('{{%user}}', 'role');
    }
}
