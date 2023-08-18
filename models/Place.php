<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "place".
 *
 * @property int $id
 * @property string $name
 * @property int $category_id
 * @property string|null $description
 * @property int|null $image_id
 * @property string|null $address
 * @property string|null $phone
 * @property string|null $site
 * @property string|null $location
 * @property string|null $menu
 * @property string|null $timetable
 * @property float|null $average_check
 *
 * @property PlaceCategory $category
 * @property Media $image
 */
class Place extends \yii\db\ActiveRecord
{
    public $fileImage;

    public $latitude;

    public $longitude;

    public function fields()
    {
        return [
            'id',
            'name',
            'description',
            'address',
            'phone',
            'site',
            'instagram',
            'facebook',
            'vk',
            'image' => function($model) {
                if ($this->image) {
                    return $this->image->getSrc();
                }
            },
            'averageSpend' => function($model) {
                if ($this->average_check) {
                    return $this->average_check . ' ₽';
                }
            },
            'category' => function($model) {
                $category = new \stdClass();
                $category->id = $this->category->id;
                $category->name = $this->category->name;
                return $category;
            },
            'location' => function($model) {
                if ($this->latitude && $this->longitude) {
                    $loc = new \stdClass();
                    $loc->latitude = $this->latitude;
                    $loc->longitude = $this->longitude;
                    return $loc;
                }
            },
            'menu',
            'timetable',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'place';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'category_id'], 'required'],
            [['category_id', 'image_id'], 'integer'],
            [['description', 'menu', 'timetable'], 'string'],
            [['average_check'], 'number'],
            [['name', 'address', 'phone', 'location', 'site', 'instagram', 'facebook', 'vk'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlaceCategory::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['image_id'], 'exist', 'skipOnError' => true, 'targetClass' => Media::className(), 'targetAttribute' => ['image_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'category_id' => 'Категория',
            'description' => 'Описание',
            'image_id' => 'Изображение',
            'address' => 'Адрес',
            'phone' => 'Телефон',
            'site' => 'Сайт',
            'location' => 'На карте',
            'menu' => 'Меню',
            'timetable' => 'График работы',
            'average_check' => 'Средний чек',
            'instagram' => 'Ссылка на страницу «Instagram»',
            'facebook' => 'Ссылка на страницу «Facebook»',
            'vk' => 'Ссылка на страницу «Вконтакте»',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(PlaceCategory::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImage()
    {
        return $this->hasOne(Media::className(), ['id' => 'image_id']);
    }

    public function afterFind()
    {
        if (preg_match('/^\d+\.\d+\,\d+\.\d+$/', $this->location)) {
            $location = array_map('trim', explode(',', $this->location));
            if (count($location) == 2) {
                $this->latitude = $location[0];
                $this->longitude = $location[1];
            }
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Event::className(), ['place_id' => 'id']);
    }
}
