<?php

namespace common\models;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property int $creator
 * @property int $category
 * @property string $title
 * @property string $annotation
 * @property string $body
 * @property int $slug
 * @property int $deleted
 * @property string $created
 * @property string $updated
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * {@inheritdoc}
     * @return PostQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PostQuery(get_called_class());
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['creator', 'category', 'slug', 'deleted'], 'integer'],
            [['body'], 'string'],
            [['created', 'updated'], 'safe'],
            [['title', 'annotation'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'creator' => 'Creator',
            'category' => 'Category',
            'title' => 'Title',
            'annotation' => 'Annotation',
            'body' => 'Body',
            'slug' => 'Slug',
            'deleted' => 'Deleted',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
