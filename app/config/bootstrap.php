<?php
//Yii::setAlias('@webroot', __DIR__ . '/../web/');

function mergeConfig($baseConfig, $file)
{
    return \yii\helpers\BaseArrayHelper::merge($baseConfig, require $file);
}

function readConfig($file)
{
    return require $file;
}