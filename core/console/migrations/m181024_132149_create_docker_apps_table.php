<?php

use yii\db\Migration;

/**
 * Handles the creation of table `docker_apps`.
 */
class m181024_132149_create_docker_apps_table extends Migration
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

        $this->createTable('docker_apps', [
            'id' => $this->primaryKey(),
            'app_id' => $this->integer(),
            'image' => $this->string(),
            'dockerfile' => $this->string(),
            'port' => $this->integer(),
            'status' => $this->integer(),
            'service_name' => $this->string(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('docker_apps');
    }
}
