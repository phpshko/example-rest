<?php

namespace app\services;

use Yii;
use yii\helpers\FileHelper;

class UploadUserPhotoService
{
    protected $webDir;
    protected $fileName;
    protected $content;

    /**
     * @param $photo string in base64
     */
    public function __construct($photo)
    {
        $this->webDir = '/uploads/photos/' . date('Y_m_d');
        $this->content = base64_decode($photo);
        $this->fileName = time() . '_' . sha1($this->content) . '.jpg';//TODO get extension from image
    }

    /**
     * @return string
     */
    public function getWebPath()
    {
        return $this->webDir . '/' . $this->fileName;
    }

    /**
     * @return bool
     */
    public function save()
    {
        return Yii::$app->fs->put($this->getWebPath(), $this->content);
    }
}