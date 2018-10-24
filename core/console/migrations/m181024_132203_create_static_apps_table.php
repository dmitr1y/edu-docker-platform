<?php

use yii\db\Migration;

/**
 * Handles the creation of table `static_apps`.
 */
class m181024_132203_create_static_apps_table extends Migration
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

        $this->createTable('static_apps', [
            'id' => $this->primaryKey(),
            'app_id' => $this->integer(),
            'path_to_index' => $this->string(),
            'index_name' => $this->string(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('static_apps');
    }
}
