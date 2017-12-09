<?php

namespace app\forms;

use app\models\User;
use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $email;
    public $password;

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
            [['email', 'password'], 'required'],
            ['email', 'email'],
            ['password', 'string', 'max' => 60],
        ];
    }

    /**
     * @return bool
     */
    public function login()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = User::findOne(['email' => $this->email]);

        if (!$user || !Yii::$app->security->validatePassword($this->password, $user->password_hash)) {
            return false;
        }

        $this->user = $user;

        return true;
    }
}