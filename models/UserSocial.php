<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_social".
 *
 * @property int $id
 * @property int $user_id
 * @property string $social_user_id
 * @property string $type
 * @property string|null $token
 *
 * @property User $user
 */
class UserSocial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_social';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'social_user_id', 'type'], 'required'],
            [['user_id'], 'integer'],
            [['social_user_id', 'type', 'token'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'social_user_id' => 'Social User ID',
            'type' => 'Type',
            'token' => 'Token',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Finds user by social_user_id and type
     *
     * @param string $social_user_id
     * @param string $type
     * @return static|null
     */
    public static function findBySocialId($social_user_id, $type)
    {
        $socialUser = static::findOne(['social_user_id' => $social_user_id, 'type' => $type]);
        if ($socialUser) {
            return User::findOne(['id' => $socialUser->user->id, 'status' => User::STATUS_ACTIVE]);
        }
        return null;
    }
}
