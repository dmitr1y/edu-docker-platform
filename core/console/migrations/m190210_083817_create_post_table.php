<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%post}}`.
 */
class m190210_083817_create_post_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%post}}', [
            'id' => $this->primaryKey(),
            'creator' => $this->integer(),
            'category' => $this->tinyInteger(5),
            'title' => $this->string(255),
            'annotation' => $this->string(255),
            'body' => $this->text(),
            'slug' => $this->integer(),
            'deleted' => $this->tinyInteger(1),
            'created' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated' => 'timestamp ON UPDATE CURRENT_TIMESTAMP',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%post}}');
    }
}
