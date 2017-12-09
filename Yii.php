<?php

/**
 * Yii bootstrap file.
 * Used for enhanced IDE code autocompletion.
 */
class Yii extends \yii\BaseYii
{
    /**
     * @var BaseApplication|WebApplication|ConsoleApplication the application instance
     */
    public static $app;
}

/**
 * Class BaseApplication
 * Used for properties that are identical for both WebApplication and ConsoleApplication
 *
 */
abstract class BaseApplication extends yii\base\Application
{
}

/**
 * Class WebApplication
 * Include only Web application related components here
 *
 * @property WebUser $user
 * @property \yii\queue\amqp\Queue $queue
 * @property \sizeg\jwt\Jwt $jwt
 * @property \creocoder\flysystem\LocalFilesystem $fs
 */
class WebApplication extends yii\web\Application
{
}

/**
 * Class WebUser
 *
 * @property \app\models\User|null $identity The identity object associated with the currently logged-in
 */
class WebUser extends \yii\web\User
{
}

/**
 * Class ConsoleApplication
 * Include only Console application related components here
 *
 */
class ConsoleApplication extends yii\console\Application
{
}