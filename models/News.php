<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "news".
 *
 * @property int $id
 * @property string $title
 * @property int $user_id
 * @property int $category_id
 * @property string $date_create
 * @property string|null $date_public
 * @property string|null $status
 * @property string|null $body
 *
 * @property User $user
 * @property NewsCategory $category
 */
class News extends \yii\db\ActiveRecord
{
    const STATUS_PUBLISH = 'publish';
    const STATUS_PENDING = 'pending';
    const STATUS_DELETED = 'deleted';

    public $fileImage;

    public function fields()
    {
        return [
            'id',
            'title',
            'author' => function($model) {
                $author = new \stdClass();
                $author->id = $model->user->id;
                $author->name = $this->user->name;
                return $author;
            },
            'category' => function($model) {
                $category = new \stdClass();
                $category->id = $this->category->id;
                $category->name = $this->category->name;
                return $category;
            },
            'image' => function($model) {
                return $this->image ? $this->image->getSrc() : null;
            },
            'date' => function($model) {
                return $model->date_public ? date('d.m.Y', strtotime($model->date_public)) : '';
            },
            'text' => 'body',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'category_id'], 'required'],
            [['user_id', 'category_id'], 'integer'],
            [['date_create', 'date_public', 'image_id'], 'safe'],
            [['body'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 50],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => NewsCategory::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'user_id' => 'ID автора',
            'category_id' => 'ID категории',
            'date_create' => 'Дата создания',
            'date_public' => 'Дата публикации',
            'status' => 'Статус',
            'body' => 'Текст',
            'image_id' => 'ID изображения',
        ];
    }

    /**
     * {@inheritdoc}
     */
    /*public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            return true;
        }
        return false;
    }*/

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        date_default_timezone_set('Asia/Vladivostok');
        if ($this->isNewRecord) {
            $this->user_id = \Yii::$app->user->identity->id;
            $this->date_create = date('Y-m-d H:i:s');
        }

        if ($this->status === self::STATUS_PUBLISH) {
            $this->date_public = date('Y-m-d H:i:s');
        }

        if (!$this->status) {
            $this->status = self::STATUS_PENDING;
        }

        if (parent::beforeSave($insert)) {
            return true;
        }
        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImage()
    {
        return $this->hasOne(Media::className(), ['id' => 'image_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(NewsCategory::className(), ['id' => 'category_id']);
    }
}
