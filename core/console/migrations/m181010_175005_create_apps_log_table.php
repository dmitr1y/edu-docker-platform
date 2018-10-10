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
        $this->createTable('apps_log', [
            'id' => $this->primaryKey(),
            'appId' => $this->string()->notNull()->unique(),
            'build' => $this->text(),
            'run' => $this->text(),
            'error' => $this->text()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('apps_log');
    }
}
