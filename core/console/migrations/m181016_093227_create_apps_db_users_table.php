<?php

use yii\db\Migration;

/**
 * Handles the creation of table `apps_db_users`.
 */
class m181016_093227_create_apps_db_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('apps_db_users', [
            'id' => $this->primaryKey(),
            'username' => $this->string(255)->notNull()->unique(),
            'user_password' => $this->string(255)->notNull(),
            'permissions' => $this->text(),
            'database' => $this->string(255),
            'owner_id' => $this->integer(),
            'timestamp' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP'
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('apps_db_users');
    }
}
