<?php

$config = [
    'class' => \yii\db\Connection::class,
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];

$config = mergeConfig($config, __DIR__ . '/local/db.php');

return $config;