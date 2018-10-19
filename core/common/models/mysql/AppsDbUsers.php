<?php

namespace common\models\mysql;

use Yii;

/**
 * This is the model class for table "apps_db_users".
 *
 * @property int $id
 * @property string $username
 * @property string $user_password
 * @property string $permissions
 * @property string $database
 * @property int $owner_id
 * @property string $timestamp
 */
class AppsDbUsers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'apps_db_users';
    }

    /**
     * {@inheritdoc}
     * @return AppsDbUsersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AppsDbUsersQuery(get_called_class());
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'user_password'], 'required'],
            [['permissions'], 'string'],
            [['owner_id'], 'integer'],
            [['timestamp'], 'safe'],
            [['username', 'user_password', 'database'], 'string', 'max' => 255],
            [['username'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'user_password' => Yii::t('app', 'User Password'),
            'permissions' => Yii::t('app', 'Permissions'),
            'database' => Yii::t('app', 'Database'),
            'owner_id' => Yii::t('app', 'Owned ID'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        return parent::save($runValidation, $attributeNames) && $this->createUserDb();
    }

    public function delete()
    {
        return parent::delete() && $this->removeUserDb();
    }

    private function createUserDb()
    {
        $query = "CREATE DATABASE IF NOT EXISTS " . $this->database . ";" .
            "CREATE USER IF NOT EXISTS'" . $this->username . "'@'%' IDENTIFIED BY '" . $this->user_password . "';" .
            "GRANT ALL PRIVILEGES ON " . $this->database . ".* TO '" . $this->username . "'@'%';" .
            "FLUSH PRIVILEGES;";
        return Yii::$app->db2->createCommand($query)->execute();
    }

    private function removeUserDb()
    {
        $query = "DROP DATABASE IF EXISTS " . $this->database . ";" .
            "DROP USER IF EXISTS '" . $this->username . "'@'%';" .
            "FLUSH PRIVILEGES;";
        return Yii::$app->db2->createCommand($query)->execute();
    }
}