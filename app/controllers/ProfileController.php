<?php

namespace app\controllers;

use sizeg\jwt\JwtHttpBearerAuth;
use Yii;
use yii\rest\Controller;

class ProfileController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => JwtHttpBearerAuth::class,
        ];

        return $behaviors;
    }

    /**
     *
     */
    public function actionView()
    {
        return Yii::$app->user->identity;
    }

    /**
     *
     */
    public function actionUpdate()
    {

    }
}