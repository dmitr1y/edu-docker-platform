<?php

use yii\db\Migration;

/**
 * Handles the creation of table `apps_log`.
 */
class m181010_175005_create_apps_log_table extends Migration
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

        $this->createTable('apps_log', [
            'id' => $this->primaryKey(),
            'appId' => $this->string()->notNull()->unique(),
            'log' => $this->text(),
            'timestamp' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP'
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('apps_log');
    }
}
