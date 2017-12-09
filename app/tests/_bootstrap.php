<?php
define('YII_ENV', 'test');
defined('YII_DEBUG') or define('YII_DEBUG', true);

require_once __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';
require __DIR__ .'/../vendor/autoload.php';

require __DIR__ .'/../config/bootstrap.php';

/**
 * Return array of fixtures from fixtures/data/{name}.php
 * @param $name
 * @return array
 */
function getFixtures($name)
{
    return require __DIR__ . '/fixtures/data/' . $name . '.php';
}