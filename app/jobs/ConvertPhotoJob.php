<?php

namespace app\jobs;

use app\models\User;
use Imagine\Image\Box;
use Imagine\Image\BoxInterface;
use Imagine\Image\ImageInterface;
use Imagine\Image\ManipulatorInterface;
use Yii;
use yii\base\BaseObject;
use yii\imagine\Image;
use yii\queue\JobInterface;

class ConvertPhotoJob extends BaseObject implements JobInterface
{
    public $userId;

    /**
     * @var ImageInterface
     */
    protected $image;

    protected $webDir;
    protected $fileName;

    /**
     * @var User
     */
    protected $user;

    /**
     * @param \yii\queue\Queue $queue
     */
    public function execute($queue)
    {
        $this->user = User::findIdentity($this->userId);

        $this->image = Image::getImagine()->load(Yii::$app->fs->read($this->user->photo_origin_path));

        $this->webDir = dirname($this->user->photo_origin_path);
        $this->fileName = pathinfo($this->user->photo_origin_path, PATHINFO_FILENAME);

        $this->saveSmallPhoto();
        $this->savePreviewPhoto();

        if (!$this->user->save()) {
            $errorText = "Save failed: User id - $this->user->id , errors - " . print_r($this->user->errors, true);
            Yii::error($errorText);
            echo $errorText . PHP_EOL;
        }

        echo 'Finish id - ' . $this->user->id . PHP_EOL;
    }

    /**
     *
     */
    protected function saveSmallPhoto()
    {
        $imageBinary = $this->image->resize($this->getSizeForSmallImage())->get('jpeg');
        Yii::$app->fs->put($this->getSmallPath(), $imageBinary);

        $this->user->photo_small_path = $this->getSmallPath();
    }

    /**
     *
     */
    protected function savePreviewPhoto()
    {
        $imageBinary = $this->image->thumbnail($this->getSizeForPreviewImage(), ManipulatorInterface::THUMBNAIL_OUTBOUND)->get('jpeg');
        Yii::$app->fs->put($this->getPreviewPath(), $imageBinary);

        $this->user->photo_preview_path = $this->getPreviewPath();
    }

    /**
     * @return string
     */
    public function getSmallPath()
    {
        return $this->webDir . '/' . $this->fileName . '_small.jpg';
    }

    /**
     * @return string
     */
    public function getPreviewPath()
    {
        return $this->webDir . '/' . $this->fileName . '_preview.jpg';
    }

    /**
     * @return BoxInterface
     */
    protected function getSizeForSmallImage(): BoxInterface
    {
        $newSize = $this->image->getSize();

        if ($newSize->getHeight() > User::PHOTO_MAX_SIDE_SIZE) {
            $newSize = $newSize->heighten(User::PHOTO_MAX_SIDE_SIZE);
        }

        if ($newSize->getWidth() > User::PHOTO_MAX_SIDE_SIZE) {
            $newSize = $newSize->widen(User::PHOTO_MAX_SIDE_SIZE);
        }

        return $newSize;
    }

    /**
     * @return BoxInterface
     */
    protected function getSizeForPreviewImage(): BoxInterface
    {
        return new Box(User::PHOTO_PREVIEW_SIZE, User::PHOTO_PREVIEW_SIZE);
    }
}