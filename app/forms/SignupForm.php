<?php

namespace app\forms;

use app\models\User;
use app\services\UploadUserPhotoService;
use yii\base\Model;

class SignupForm extends Model
{
    public $email;
    public $password;
    public $name;
    public $age;
    public $gender;
    public $photo;

    public $imageWebPath;

    /**
     * @var User|null
     */
    public $user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::class],
            [['name', 'email', 'password'], 'required'],
            [['name', 'email'], 'string', 'max' => 255],
            ['password', 'string', 'min' => User::MIN_PASSWORD_LENGTH, 'max' => User::MAX_PASSWORD_LENGTH],
            ['photo', 'string'],
            ['gender', 'in', 'range' => [User::GENDER_FEMALE, User::GENDER_MALE]],
            ['age', 'integer', 'min' => User::MIN_AGE, 'max' => User::MAX_AGE],
        ];
    }

    /**
     * @return bool
     */
    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = new User;
        $user->email = $this->email;
        $user->password = $this->password;
        $user->name = $this->name;
        $user->age = $this->age;
        $user->gender = $this->gender;

        $uploadService = new UploadUserPhotoService($this->photo);

        if (!empty($this->photo)) {
            if (!$uploadService->save()) {
                $this->addError('Error upload photo');
                return false;
            }
            $this->imageWebPath = $uploadService->getWebPath();
        }

        if (!$user->save()) {
            $this->addErrors($user->getErrors(['email', 'password', 'name', 'age', 'gender']));
            return false;
        }

        $this->user = $user;

        return true;
    }
}