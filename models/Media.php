<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * This is the model class for table "media".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $base_name
 * @property string|null $extension
 * @property string|null $type
 * @property int|null $size
 */
class Media extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'media';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['size'], 'integer'],
            [['name', 'type'], 'string', 'max' => 50],
            [['base_name'], 'string', 'max' => 255],
            [['extension'], 'string', 'max' => 10],
            [['image'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'        => 'ID',
            'name'      => 'Название',
            'base_name' => 'Название при загрузке',
            'extension' => 'Расширение',
            'type'      => 'Тип',
            'size'      => 'Размер',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasMany(News::className(), ['image_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['photo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewsMedia()
    {
        return $this->hasMany(NewsMedia::className(), ['media_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Event::className(), ['image_id' => 'id']);
    }

    public function getSrc()
    {
        return Url::home(true) . 'uploads/' . $this->name . '.' . $this->extension;
    }

    public function getHtml($class = '')
    {
        return Html::img(
            Url::home(true) . 'uploads/' . $this->name . '.' . $this->extension,
            [
                'id'        => 'media_' . $this->id,
                'alt'       => 'Медиа ' . $this->id,
                'data-type' => $this->type,
                'data-size' => $this->size,
                'class'     => $class,
            ]
        );
    }

    public function getTumbnail()
    {
        return $this->getHtml('img-thumbnail');
    }
}
