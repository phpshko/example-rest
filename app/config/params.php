<?php

$config = [
    'adminEmail' => 'admin@example.com',
];

$config = mergeConfig($config, __DIR__ . '/local/params.php');

return $config;
