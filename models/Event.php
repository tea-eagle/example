<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "event".
 *
 * @property int $id
 * @property string $name
 * @property int $user_id
 * @property string|null $place
 * @property int|null $image_id
 * @property string $date_start
 * @property string $date_end
 * @property string $date_create
 * @property string|null $date_public
 * @property string|null $status
 * @property string|null $email
 * @property int|null $is_send_notofication
 * @property int|null $category_id
 * @property string|null $description
 * @property string|null $organizer
 * @property string|null $age_restrictions
 * @property float|null $price
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $location
 *
 * @property EventCategory $category
 */
class Event extends \yii\db\ActiveRecord
{
    const STATUS_PUBLISH = 'publish';
    const STATUS_PENDING = 'pending';
    const STATUS_DELETED = 'deleted';

    public $fileImage;

    public $latitude;

    public $longitude;

    public $time_start;

    public $time_end;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'date_start', 'date_end', 'place_id'], 'required'],
            [['image_id', 'is_send_notofication', 'category_id', 'user_id', 'place_id'], 'integer'],
            [['date_start', 'date_end', 'date_create', 'date_public', 'time_start', 'time_end'], 'safe'],
            [['description'], 'string'],
            [['name', 'email', 'address', 'location'], 'string', 'max' => 255],
            ['email', 'email'],
            [['organizer', 'age_restrictions', 'phone', 'status', 'price'], 'string', 'max' => 50],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => EventCategory::className(), 'targetAttribute' => ['category_id' => 'id']],
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
            'user_id' => 'Автор',
            'place_id' => 'Место проведения',
            'place.name' => 'Место проведения',
            'image_id' => 'Обложка',
            'date_start' => 'Дата начала',
            'date_end' => 'Дата завершения',
            'date_create' => 'Дата создания',
            'time_start' => 'Время начала',
            'time_end' => 'Время завершения',
            'date_public' => 'Дата публикации',
            'status' => 'Статус',
            'email' => 'Email',
            'is_send_notofication' => 'Отправить уведомление на почту о статусе модерации мероприятия',
            'category_id' => 'Category ID',
            'description' => 'Описание',
            'organizer' => 'Организатор',
            'age_restrictions' => 'Возрастные ограничения',
            'price' => 'Стоимость',
            'phone' => 'Телефон',
            'address' => 'Адрес',
            'location' => 'Расположение на карте',
        ];
    }

    public function fields()
    {
        return [
            'id',
            'name',
            'description',
            'cost' => function($model) {
                if ($model->price) {
                    return $model->price;
                }
            },
            'address',
            'ageRating' => 'age_restrictions',
            'date' => function($model) {
                return $model->date_start ? date('d.m.Y', strtotime($model->date_start)) : '';
            },
            'isFavorite' => function($model) {
                return true;
            },
            'placeID' => function($model) {
                return $model->place_id;
            },
            'place' => function($model) {
                return $this->place;
            },
            'image' => function($model) {
                return $this->image ? $this->image->getSrc() : null;
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
        ];
    }

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

        $this->date_start = date('Y-m-d ', strtotime($this->date_start));
        $this->date_end   = date('Y-m-d ', strtotime($this->date_end));

        $this->date_start .= $this->time_start;
        $this->date_end .= $this->time_end;
        
        return parent::beforeSave($insert);
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

        $this->time_start = explode(' ', $this->date_start);
        $this->time_start = mb_substr($this->time_start[1], 0, -3);
        $this->date_start = date('d.m.Y', strtotime($this->date_start));

        $this->time_end = explode(' ', $this->date_end);
        $this->time_end = mb_substr($this->time_end[1], 0, -3);
        $this->date_end = date('d.m.Y', strtotime($this->date_end));
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
        return $this->hasOne(EventCategory::className(), ['id' => 'category_id']);
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
    public function getPlace()
    {
        return $this->hasOne(Place::className(), ['id' => 'place_id']);
    }
}
