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
 * @property int $owned_id
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
            [['username', 'user_password', 'owned_id'], 'required'],
            [['permissions'], 'string'],
            [['owned_id'], 'integer'],
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
            'owned_id' => Yii::t('app', 'Owned ID'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }
}
