<?php

namespace app\controllers;

use app\forms\CreateUserForm;
use app\jobs\ConvertPhotoJob;
use app\models\User;
use sizeg\jwt\JwtHttpBearerAuth;
use Yii;
use yii\filters\AccessControl;
use yii\rest\Controller;

class UserController extends Controller
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

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'only' => ['create'],
            'rules' => [
                [
                    'allow'         => true,
                    'actions'       => ['create'],
                    'matchCallback' => function () {
                        return Yii::$app->user->identity->isAdmin;
                    }
                ]
            ]
        ];

        return $behaviors;
    }

    /**
     *
     */
    public function actionCreate()
    {
        $model = new CreateUserForm();
        $model->load(Yii::$app->request->bodyParams, '');

        if ($model->save()) {
            if (!empty($model->user->photo_origin_path)) {
                Yii::$app->queue->push(new ConvertPhotoJob([
                    'userId' => $model->user->id,
                ]));
            }

            return ['success' => 1, 'model' => $model->user];
        }

        return [
            'success' => 0,
            'errors'  => $model->errors,
        ];
    }

    public function actionView($id)
    {
        return User::findIdentity($id);
    }

}