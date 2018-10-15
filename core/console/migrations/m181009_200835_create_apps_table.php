<?php

use yii\db\Migration;

/**
 * Handles the creation of table `apps`.
 */
class m181009_200835_create_apps_table extends Migration
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

        $this->createTable('apps', [
            'id' => $this->primaryKey(),
            'name' => $this->string(32)->notNull(),
            'description' => $this->text(),
            'file' => $this->text(),
            'image' => $this->string(255),
            'url' => $this->text(),
            'port' => $this->integer(),
            'status' => $this->integer()
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('apps');
    }
}