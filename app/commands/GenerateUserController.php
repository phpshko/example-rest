<?php

namespace app\commands;

use app\models\User;
use yii\console\Controller;

class GenerateUserController extends Controller
{
    /**
     * Insert user to db
     */
    public function actionIndex()
    {
        $user = new User();
        $user->email = 'admin@gmail.com';
        $user->password = '123';
        $user->name = 'Test Admin';
        $user->age = 32;
        $user->gender = User::GENDER_MALE;
        $user->role = User::ROLE_ADMIN;

        try {
            $user->save(false);
        } catch (\Exception $e) {
            echo $e->getMessage();
            return;
        }

        if ($user->errors) {
            echo print_r($user->errors, true) . "\n";
            return;
        }

        echo "Success\n";
    }
}