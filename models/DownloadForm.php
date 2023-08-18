<?php

namespace app\models;

use Yii;
use yii\base\Model;

class DownloadForm extends Model
{
    /**
     * @var Upload from Url
     */
    public $url;

    /**
     * @var Uploaded image
     */
    private $image;

    public function rules()
    {
        return [
            [['url'], 'url'],
        ];
    }
    
    public function execute()
    {
        if ($this->validate()) {
            $this->loadImage();

            if ($this->image) {
                $media = new Media();
                $media->name      = $this->image->name;
                $media->base_name = $this->image->baseName;
                $media->extension = $this->image->extension;
                $media->type      = $this->image->type;
                $media->size      = $this->image->size;

                if ($media->save()) {
                    return $media->id;
                }
            }
        }
        return false;
    }

    private function loadImage()
    {
        if ($curl = curl_init()) {
            curl_setopt_array($curl, array(
                CURLOPT_URL            => $this->url,
                CURLOPT_HEADER         => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true
            ));

            if ($result = curl_exec($curl)) {
                $type = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);
                $size = curl_getinfo($curl, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
                $file_content = $result;

                $parsed_url = parse_url($this->url, PHP_URL_PATH);
                $basename = pathinfo($parsed_url, PATHINFO_BASENAME);
                $extension = pathinfo($parsed_url, PATHINFO_EXTENSION);

                $newname = uniqid('media_' . mt_rand(11, 99));

                if (stripos($type, 'image') !== false) {
                    if (file_put_contents(Yii::getAlias('@webroot/uploads/' . $newname . '.' . $extension), $file_content)) {
                        $this->image = new \stdClass();
                        $this->image->name      = $newname;
                        $this->image->baseName  = $basename;
                        $this->image->extension = $extension;
                        $this->image->type      = $type;
                        $this->image->size      = $size;
                    }
                }
            }
        }
    }
}