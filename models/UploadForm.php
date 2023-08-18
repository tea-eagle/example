<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $image;

    public function rules()
    {
        return [
            [['image'], 'file', 'skipOnEmpty' => false, 'extensions' => ['jpg', 'png', 'jpeg', 'gif']],
        ];
    }
    
    public function upload()
    {
        if ($this->validate()) {
            $media = new Media();
            $media->name = uniqid('media_' . mt_rand(11, 99));
            $media->base_name = $this->image->baseName;
            $media->extension = $this->image->extension;
            $media->type      = $this->image->type;
            $media->size      = $this->image->size;

            if ($this->image->saveAs('uploads/' . $media->name . '.' . $this->image->extension) && $media->save()) {
                return $media->id;
            }
        }
        return false;
    }
}