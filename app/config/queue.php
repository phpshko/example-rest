<?php

$config = [
    'class' => \yii\queue\amqp\Queue::class,
    'as log' => \yii\queue\LogBehavior::class,
    'port'  => 5672,
];

$config = mergeConfig($config, __DIR__ . '/local/queue.php');

return $config;
