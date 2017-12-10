<?php

namespace app\controllers;

use app\forms\LoginForm;
use app\forms\SignupForm;
use app\jobs\ConvertPhotoJob;
use Yii;
use yii\rest\Controller;
use yii\web\ErrorAction;

class SiteController extends Controller
{
    /**
     * @return array
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
        ];
    }

    /**
     *
     */
    public function actionLogin()
    {
        $model = new LoginForm();
        $model->load(Yii::$app->request->bodyParams, '');

        if ($model->login()) {
            return [
                'success' => 1,
                'token'   => (string)$model->user->getJWT()
            ];
        }

        return [
            'success' => 0,
            'message' => 'Wrong email or password',
        ];
    }

    /**
     *
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        $model->load(Yii::$app->request->bodyParams, '');

        if ($model->save()) {
            if (!empty($model->imageWebPath)) {
                Yii::$app->queue->push(new ConvertPhotoJob([
                    'userId'       => $model->user->id,
                    'imageWebPath' => $model->imageWebPath
                ]));
            }

            return [
                'success' => 1,
                'model'   => $model->user,
                'token'   => $model->user->getJWT(),
            ];
        }

        return [
            'success' => 0,
            'errors'  => $model->errors,
        ];
    }
}